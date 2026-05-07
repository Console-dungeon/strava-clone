<?php

namespace App\Http\Controllers;

use Alexoid\GarminConnect\GarminConnect;
use Alexoid\GarminConnect\TokenStore\LaravelCacheTokenStore;
use App\Models\Activity;
use App\Models\ActivityGpx;
use App\Services\ActivityLogger;
use App\Services\GpxParser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = auth()->user()
            ->activities()
            ->latest()
            ->get();

        return Inertia::render('Activities/Index', [
            'activities' => $activities,
        ]);
    }

    public function create()
    {
        return Inertia::render('Activities/Create');
    }

    private function formatDuration(int $minutes): string
    {
        if ($minutes < 60) {
            return "{$minutes} min";
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return $mins > 0 ? "{$hours}h {$mins}min" : "{$hours}h";
    }

    private function formatPace(int $minutes, float $distance): ?string
    {
        if ($distance <= 0) {
            return null;
        }

        $paceSeconds = ($minutes * 60) / $distance;
        $paceMin = (int) ($paceSeconds / 60);
        $paceSec = (int) ($paceSeconds % 60);

        return sprintf('%d:%02d /km', $paceMin, $paceSec);
    }

    public function main(Request $request)
    {
        $user = auth()->user();
        $type = $request->query('type');

        return Inertia::render('Activities/Main', [
            'stats' => [
                'distance' => $user->activities()->sum('distance'),
                'duration' => $user->activities()->sum('duration'),
                'avgSpeed' => 0,
            ],
            'activities' => $user->activities()
                ->with('gpx')
                ->when($type, fn ($q) => $q->where('type', $type))
                ->orderBy('date', 'desc')
                ->paginate(15)
                ->through(fn ($a) => [
                    'id' => $a->id,
                    'date' => $a->date,
                    'type' => $a->type,
                    'distance' => $a->distance,
                    'duration' => $this->formatDuration($a->duration),
                    'has_map' => $a->gpx !== null || $a->garmin_activity_id !== null,
                    'avg_hr' => $a->avg_hr,
                    'max_hr' => $a->max_hr,
                    'avg_speed' => $a->avg_speed,
                    'max_speed' => $a->max_speed,
                    'avg_pace' => $this->formatPace($a->duration, (float) $a->distance),
                    'calories' => $a->calories,
                ])
                ->appends($request->query()),
            'filters' => ['type' => $type],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'distance' => 'required|numeric',
            'duration' => 'required|integer',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->activities()->create($data);

        ActivityLogger::log(ActivityLogger::ACTIVITY_CREATED);

        return redirect()->route('dashboard');
    }

    public function importGpx(Request $request)
    {
        $request->validate([
            'gpx_file' => 'required|file|mimes:gpx,xml|max:10240',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('gpx_file');
        $parser = new GpxParser;
        $parsed = $parser->parse($file->getRealPath());

        $type = $parsed['type'] ?? $request->type;

        if (! $type) {
            return back()->withErrors(['type' => __('Nie udało się wykryć typu aktywności. Wybierz go ręcznie.')]);
        }

        $filePath = $file->store('gpx/'.auth()->id(), 'local');

        $activity = auth()->user()->activities()->create([
            'type' => $type,
            'distance' => $parsed['distance'],
            'duration' => $parsed['duration'],
            'date' => $parsed['date'],
            'notes' => $request->notes,
        ]);

        $activity->gpx()->create([
            'route_points' => $parsed['route_points'],
            'gpx_file_path' => $filePath,
            'elevation_gain' => $parsed['elevation_gain'],
        ]);

        return redirect()->route('activities.main');
    }

    public function gpxData(Activity $activity)
    {
        abort_if($activity->user_id !== auth()->id(), 403);

        if ($activity->gpx) {
            return response()->json([
                'route_points' => $activity->gpx->route_points,
                'elevation_gain' => $activity->gpx->elevation_gain,
            ]);
        }

        if (! $activity->garmin_activity_id) {
            return response()->json(['route_points' => [], 'elevation_gain' => null]);
        }

        $userId = auth()->guard()->id();
        $garmin = new GarminConnect(
            email: '',
            password: '',
            tokenStore: new LaravelCacheTokenStore(prefix: "garmin_tokens_{$userId}_")
        );
        $garmin->login();

        $details = $garmin->getActivityDetails($activity->garmin_activity_id);
        $rawPoints = $details['geoPolylineDTO']['polyline'] ?? [];

        if (empty($rawPoints)) {
            return response()->json(['route_points' => [], 'elevation_gain' => null]);
        }

        $routePoints = array_map(fn ($p) => [
            'lat' => $p['lat'],
            'lng' => $p['lon'],
        ], $rawPoints);

        ActivityGpx::create([
            'activity_id' => $activity->id,
            'route_points' => $routePoints,
        ]);

        return response()->json(['route_points' => $routePoints, 'elevation_gain' => null]);
    }

    public function destroy(Activity $activity)
    {
        abort_if($activity->user_id !== auth()->id(), 403);

        $activity->delete();

        ActivityLogger::log(ActivityLogger::ACTIVITY_DELETED);

        return redirect()->back();
    }
}

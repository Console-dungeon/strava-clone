<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
                ->when($type, fn ($q) => $q->where('type', $type))
                ->orderBy('date', 'desc')
                ->paginate(15)
                ->through(fn ($a) => [
                    'id' => $a->id,
                    'date' => $a->date,
                    'type' => $a->type,
                    'distance' => $a->distance,
                    'duration' => $this->formatDuration($a->duration),
                    'has_gpx' => $a->gpx()->exists(),
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

        $gpx = $activity->gpx;

        if (! $gpx) {
            return response()->json(['route_points' => [], 'elevation_gain' => null]);
        }

        return response()->json([
            'route_points' => $gpx->route_points,
            'elevation_gain' => $gpx->elevation_gain,
        ]);
    }

    public function destroy(Activity $activity)
    {
        abort_if($activity->user_id !== auth()->id(), 403);

        $activity->delete();

        return redirect()->back();
    }
}

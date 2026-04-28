<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
            'activities' => $activities
        ]);
    }

    public function create(){
        return Inertia::render('Activities/Create');
    }

    private function formatDuration(int $minutes): string
    {
        if ($minutes < 60)
            return "{$minutes} min";

        $hours = intdiv($minutes, 60);
        $mins  = $minutes % 60;

        return $mins > 0 ? "{$hours}h {$mins}min" : "{$hours}h";
    }

    public function main(Request $request){

        $user = auth()->user();
        $type = $request->query('type');

        return Inertia::render('Activities/Main', [
            'stats' => [
                'distance' => $user->activities()->sum('distance'),
                'duration' => $user->activities()->sum('duration'),
                'avgSpeed' => 0,
            ],
            'activities' => $user->activities()
                ->when($type, fn($q) => $q->where('type', $type))
                ->orderBy('date', 'desc')
                ->paginate(15)
                ->through(fn($a) => [
                    'id'       => $a->id,
                    'date'     => $a->date,
                    'type'     => $a->type,
                    'distance' => $a->distance,
                    'duration' => $this->formatDuration($a->duration),
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

    public function destroy(Activity $activity)
    {
        abort_if($activity->user_id !== auth()->id(), 403);

        $activity->delete();

        return redirect()->back();
    }
}
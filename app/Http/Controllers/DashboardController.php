<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Activity;


class DashboardController extends Controller
{

    private function formatDuration(int $minutes): string{
        if ($minutes <= 59)
            return "{$minutes} min";

        $hours = intdiv($minutes, 60);
        $mins = $minutes%60;

        return $minutes > 0 ? "{$hours}h {$mins}min" : "{$hours}h";
    }
    
    public function __invoke()
    {
        $user = auth()->user();

        $totalDistance = round($user->activities()->sum('distance'), 2);
        $totalDuration = $user->activities()->sum('duration');
        $avgSpeed = $totalDuration > 0
            ? round($totalDistance / ($totalDuration / 60), 2)
            : 0;

        return Inertia::render('Dashboard', [
            'stats' => [
                'distance' => $totalDistance,
                'duration' => $this->formatDuration($totalDuration),
                'avgSpeed' => $avgSpeed,
                'recent' => Activity::latest('date')
                ->where('user_id', $user->id)
                ->take(5)
                ->get(['id', 'date', 'type', 'distance', 'duration'])
                ->map(function ($activity) {
        $activity->duration = $this->formatDuration($activity->duration);
        return $activity;
                }),
            ],
        ]);
    }
}

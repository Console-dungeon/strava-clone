<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Activity;


class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        $totalDistance = $user->activities()->sum('distance');
        $totalDuration = $user->activities()->sum('duration');
        $avgSpeed = $totalDuration > 0
            ? round($totalDistance / ($totalDuration / 60), 2)
            : 0;

        return Inertia::render('Dashboard', [
            'stats' => [
                'distance' => $totalDistance,
                'duration' => $totalDuration,
                'avgSpeed' => $avgSpeed,
                'recent' => Activity::latest()
                ->where('user_id', $user->id)
                ->take(5)
                ->get(['id', 'date', 'type', 'distance', 'duration']),
            ],
        ]);
    }
}

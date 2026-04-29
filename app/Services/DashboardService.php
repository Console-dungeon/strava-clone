<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function getData(User $user): array
    {
        $totalDistance = round($user->activities()->sum('distance'), 2);
        $totalDurationMin = (int) $user->activities()->sum('duration');

        $recent = $user->activities()
            ->latest('date')
            ->take(10)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'date' => $a->date,
                'type' => $a->type,
                'distance' => $a->distance,
                'duration' => $this->formatDuration($a->duration),
            ]);

        $chartData = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($user) {
                $day = now()->subDays($daysAgo);

                return [
                    'label' => $day->format('d.m'),
                    'distance' => (float) $user->activities()
                        ->whereDate('date', $day->toDateString())
                        ->sum('distance'),
                ];
            })
            ->values();

        return [
            'hasActivities' => true,
            'stats' => [
                'distance' => $totalDistance,
                'duration' => $this->formatDuration($totalDurationMin),
                'avgSpeed' => round((float) $user->activities()->avg('avg_speed'), 2),
            ],
            'recent' => $recent,
            'chartData' => $chartData,
        ];
    }

    private function formatDuration(int $minutes): string
    {
        if ($minutes < 60) {
            return "{$minutes} min";
        }

        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        return $m > 0 ? "{$h}h {$m}min" : "{$h}h";
    }
}

<?php

namespace App\Services;

use Alexoid\GarminConnect\GarminConnect;
use Alexoid\GarminConnect\TokenStore\LaravelCacheTokenStore;

class GarminService
{
    private GarminConnect $garmin;

    public function __construct()
    {
        $user = auth()->guard()->user();
        // INFO: This "env" props are just a temporary approach to test the Garmin API. The modal is needed on the Frontend Side to ask the user for his Garmin credentials, just wrote it here to don't forget ;p
        $this->garmin = new GarminConnect(
            email: env('GARMIN_EMAIL'),
            password: env('GARMIN_PASSWORD'),
            tokenStore: new LaravelCacheTokenStore(prefix: "garmin_tokens_{$user->id}_")
        );
        $this->garmin->login();
    }

    private function formatDuration(int $minutes): string
    {
        if ($minutes <= 59) {
            return "{$minutes} min";
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return $minutes > 0 ? "{$hours}h {$mins}min" : "{$hours}h";
    }

    public function getLastActivity()
    {
        $garminActivity = $this->garmin->getLastActivity();
        if (! $garminActivity) {
            return null;
        }
        // dd($garminActivity);

        $partialActivity = [
            'id' => $garminActivity['activityId'],
            'name' => $garminActivity['activityName'],
            'startDate' => $garminActivity['startTimeGMT'],
            'endDate' => $garminActivity['endTimeGMT'],
            'activityType' => $garminActivity['activityType'],
            'startTime' => $garminActivity['startTimeGMT'],
            'duration' => $this->formatDuration((int) ($garminActivity['duration'] / 60)),
            'distance' => round($garminActivity['distance'] / 1000, 2),
            'avarageSpeed' => round($garminActivity['averageSpeed'] * 3.6, 2),
        ];

        return $partialActivity;
    }
}

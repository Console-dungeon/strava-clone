<?php

namespace App\Services;

use Alexoid\GarminConnect\GarminConnect;

class GarminService
{
    public function __construct()
    {
        $garmin = new GarminConnect(
            email: env('GARMIN_EMAIL'),
            password: env('GARMIN_PASSWORD'),
        );
        $garmin->login('./garminconnect');
        $summary = $garmin->getUserSummary('2026-04-24');
        $activities = $garmin->getHeartRates('2026-04-23');
        dd($activities, $summary);

    }
}

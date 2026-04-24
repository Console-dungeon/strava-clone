<?php

namespace App\Http\Controllers;

use App\Services\GarminService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(private GarminService $garmin) {}

    public function __invoke()
    {
        $garminActivities = $this->garmin->getLastActivity();

        return Inertia::render('Dashboard', $garminActivities);
    }
}

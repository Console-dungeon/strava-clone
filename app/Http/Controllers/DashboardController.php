<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->guard()->user();

        if (! $user->activities()->exists()) {
            return Inertia::render('Dashboard', ['hasActivities' => false]);
        }

        return Inertia::render('Dashboard', $this->dashboard->getData($user));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GarminService;
use Inertia\Inertia;

class GarminSyncController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->guard()->user();

        if (! $user->garmin_email || ! $user->garmin_password) {
            return back()->withErrors(['sync' => 'Brak zapisanych danych Garmin Connect.']);
        }

        try {
            $service = new GarminService($user->garmin_email, $user->garmin_password, $user->id);
            $count = $service->syncActivities($user);
        } catch (\Exception) {
            return back()->withErrors(['sync' => 'Błąd połączenia z Garmin Connect.']);
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $count > 0
                ? "Zsynchronizowano {$count} nowych aktywności."
                : 'Brak nowych aktywności.',
        ]);

        return redirect()->route('activities.main');
    }
}

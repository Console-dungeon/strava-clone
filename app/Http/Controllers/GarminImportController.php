<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GarminService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GarminImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var User $user */
        $user = auth()->guard()->user();

        try {
            $service = new GarminService($data['email'], $data['password'], $user->id);
            $service->syncActivities($user, 1000);
        } catch (\Exception) {
            return back()->withErrors(['email' => 'Nieprawidłowe dane logowania Garmin Connect.']);
        }

        $user->update([
            'garmin_email' => $data['email'],
            'garmin_password' => $data['password'],
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Aktywności zostały zaimportowane',
        ]);

        return redirect()->route('dashboard');
    }
}

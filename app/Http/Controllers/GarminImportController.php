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

        if ($user->activities()->exists()) {
            return back()->withErrors(['import' => 'Aktywności już zostały zaimportowane.']);
        }

        try {
            $service = new GarminService($data['email'], $data['password']);
            $service->importActivities($user);
        } catch (\Exception) {
            return back()->withErrors(['email' => 'Nieprawidłowe dane logowania Garmin Connect.']);
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Aktywności zostały zaimportowane',
        ]);

        return redirect()->route('dashboard');
    }
}

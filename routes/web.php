<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GarminImportController;
use App\Http\Controllers\GarminSyncController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::get('/activities/main', [ActivityController::class, 'main'])->name('activities.main');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::post('/activities/import-gpx', [ActivityController::class, 'importGpx'])->name('activities.import-gpx');
    Route::get('/activities/{activity}/gpx-data', [ActivityController::class, 'gpxData'])->name('activities.gpx-data');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::post('/garmin/import', GarminImportController::class)->name('garmin.import');
    Route::post('/garmin/sync', GarminSyncController::class)->name('garmin.sync');
});

require __DIR__.'/auth.php';

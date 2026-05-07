<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\GarminService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncGarminActivities implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        if (! $this->user->garmin_email || ! $this->user->garmin_password) {
            return;
        }

        $service = new GarminService(
            $this->user->garmin_email,
            $this->user->garmin_password,
            $this->user->id
        );

        $service->syncActivities($this->user);
    }
}

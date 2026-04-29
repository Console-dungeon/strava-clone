<?php

namespace App\Services;

use Alexoid\GarminConnect\GarminConnect;
use Alexoid\GarminConnect\TokenStore\LaravelCacheTokenStore;
use App\Models\User;
use Carbon\Carbon;

class GarminService
{
    private GarminConnect $garmin;

    public function __construct(string $email, string $password)
    {
        $user = auth()->guard()->user();
        $this->garmin = new GarminConnect(
            email: $email,
            password: $password,
            tokenStore: new LaravelCacheTokenStore(prefix: "garmin_tokens_{$user->id}_")
        );
        $this->garmin->login();
    }

    public function importActivities(User $user, int $limit = 1000): int
    {
        $garminActivities = $this->garmin->getActivities(0, $limit);
        $count = 0;

        foreach ($garminActivities as $activity) {
            $user->activities()->create([
                'type'      => $activity['activityType']['typeKey'] ?? 'other',
                'distance'  => round(($activity['distance'] ?? 0) / 1000, 2),
                'duration'  => (int) (($activity['duration'] ?? 0) / 60),
                'avg_speed' => round(($activity['averageSpeed'] ?? 0) * 3.6, 2),
                'date'      => Carbon::parse($activity['startTimeGMT'])->toDateString(),
                'notes'     => null,
            ]);
            $count++;
        }

        return $count;
    }
}

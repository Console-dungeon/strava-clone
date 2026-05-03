<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public const GARMIN_SYNC_STARTED = 'garmin.sync.started';

    public const GARMIN_SYNC_COMPLETED = 'garmin.sync.completed';

    public const ACTIVITY_CREATED = 'activity.created';

    public const ACTIVITY_DELETED = 'activity.deleted';

    public static function log(string $action, ?int $userId = null): void
    {
        $resolvedUserId = $userId ?? auth()->guard()->id();

        if ($resolvedUserId === null) {
            return;
        }

        ActivityLog::create([
            'user_id' => $resolvedUserId,
            'action' => $action,
            'created_at' => now(),
        ]);
    }
}

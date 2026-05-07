<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'distance', 'duration', 'avg_speed', 'date', 'notes', 'garmin_activity_id',
        'avg_hr', 'max_hr', 'max_speed', 'calories',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gpx()
    {
        return $this->hasOne(ActivityGpx::class);
    }
}

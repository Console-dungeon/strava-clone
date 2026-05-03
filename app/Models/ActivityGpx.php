<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGpx extends Model
{
    protected $table = 'activity_gpx';

    protected $fillable = [
        'activity_id',
        'route_points',
        'gpx_file_path',
        'elevation_gain',
    ];

    protected $casts = [
        'route_points' => 'array',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

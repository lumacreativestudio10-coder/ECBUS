<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'from_location_id', 'to_location_id', 'distance', 'estimated_duration_minutes', 'description', 'image', 'starting_price', 'is_popular', 'status'];

    public function getDurationStringAttribute()
    {
        if (!$this->estimated_duration_minutes) return 'N/A';
        $hours = floor($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;
        if ($hours > 0 && $minutes > 0) {
            return "{$hours} Hours {$minutes} Minutes";
        } elseif ($hours > 0) {
            return "{$hours} Hours";
        }
        return "{$minutes} Minutes";
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function boardingPoints()
    {
        return $this->hasMany(BoardingPoint::class);
    }

    public function droppingPoints()
    {
        return $this->hasMany(DroppingPoint::class);
    }
}

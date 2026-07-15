<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'from_location_id', 'to_location_id', 'distance', 'estimated_duration', 'description', 'image', 'starting_price', 'is_popular', 'status'];

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

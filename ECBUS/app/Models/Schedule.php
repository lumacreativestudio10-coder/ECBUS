<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bus_id', 'route_id', 'date', 'departure_time', 'arrival_time', 'price', 'status', 'available_seats'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

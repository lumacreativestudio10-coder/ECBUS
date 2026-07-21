<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bus_id', 'route_id', 'date', 'departure_time', 'arrival_time', 'price', 'status', 'available_seats', 'driver_id', 'conductor_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('company', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->company_id) {
                $builder->whereHas('bus', function($q) {
                    $q->where('bus_company_id', auth()->user()->company_id);
                });
            }
        });
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operator_id', 'bus_type_id', 'name', 'bus_number', 'registration_number',
        'total_seats', 'seat_layout', 'image', 'facilities', 'description', 'status'
    ];

    protected $casts = [
        'seat_layout' => 'array',
        'facilities' => 'array',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function busType()
    {
        return $this->belongsTo(BusType::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'passenger_name',
        'phone',
        'passenger_count',
        'seat_numbers',
        'total_amount',
        'payment_receipt_path',
        'status',
    ];

    protected $casts = [
        'seat_numbers' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}

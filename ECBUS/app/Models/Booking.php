<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_reference',
        'schedule_id',
        'customer_name',
        'email',
        'phone',
        'passenger_count',
        'seat_numbers',
        'boarding_point',
        'dropping_point',
        'total_amount',
        'payment_receipt_path',
        'booking_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->booking_reference)) {
                $latest = static::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->booking_reference = 'ECB' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'seat_numbers' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}

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
        'booking_status',
        'is_verified',
        'boarding_statuses'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->booking_reference)) {
                $maxId = \Illuminate\Support\Facades\DB::table('bookings')->max('id');
                $nextId = $maxId ? $maxId + 1 : 1;
                // Add a small random string or timestamp to prevent race conditions during concurrent requests
                $randomSuffix = strtoupper(\Illuminate\Support\Str::random(3));
                $model->booking_reference = 'ECB' . str_pad($nextId, 5, '0', STR_PAD_LEFT) . $randomSuffix;
            }
        });

        static::addGlobalScope('company', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->company_id) {
                $builder->whereHas('schedule.bus', function($q) {
                    $q->where('bus_company_id', auth()->user()->company_id);
                });
            }
        });
    }

    protected $casts = [
        'seat_numbers' => 'array',
        'boarding_statuses' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getTicketNumberAttribute()
    {
        return $this->booking_reference;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class BookingCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'company_id',
        'rule_id',
        'commission_amount',
    ];

    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->company_id) {
                $builder->where('company_id', auth()->user()->company_id);
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function company()
    {
        return $this->belongsTo(BusCompany::class);
    }

    public function rule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}

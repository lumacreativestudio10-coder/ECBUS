<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class CompanySettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'period_start',
        'period_end',
        'total_revenue',
        'total_commission',
        'net_payable',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->company_id) {
                $builder->where('company_id', auth()->user()->company_id);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(BusCompany::class);
    }

    public function payments()
    {
        return $this->hasMany(SettlementPayment::class, 'settlement_id');
    }
}

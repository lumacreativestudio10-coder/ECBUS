<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'settlement_id',
        'amount',
        'payment_date',
        'receipt_path',
        'transaction_id',
        'reference_number',
        'notes',
    ];

    public function settlement()
    {
        return $this->belongsTo(CompanySettlement::class);
    }
}

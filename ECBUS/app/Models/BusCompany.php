<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class BusCompany extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'company_code',
        'registration_number',
        'license_number',
        'contact_person',
        'whatsapp_number',
        'mobile_number',
        'telephone',
        'email',
        'website',
        'address',
        'city',
        'district',
        'logo',
        'description',
        'commission_per_seat',
        'bank_name',
        'branch_name',
        'account_name',
        'account_number',
        'branch_code',
        'swift_code',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
}

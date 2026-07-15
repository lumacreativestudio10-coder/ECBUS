<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'logo', 'operating_routes', 'total_buses', 'owner_name', 'phone', 'whatsapp', 'email', 
        'address', 'description', 'status', 'commission_percentage'
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}

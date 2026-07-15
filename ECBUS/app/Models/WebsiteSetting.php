<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'website_name', 'logo', 'favicon', 'phone', 'whatsapp', 'email', 
        'address', 'facebook', 'instagram', 'hero_title', 'hero_description'
    ];
}

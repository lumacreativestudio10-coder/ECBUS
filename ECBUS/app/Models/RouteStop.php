<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    protected $fillable = ['route_id', 'stop_name', 'stop_order', 'time_offset_minutes'];
}

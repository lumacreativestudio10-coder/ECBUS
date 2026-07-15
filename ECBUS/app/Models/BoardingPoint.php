<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BoardingPoint extends Model
{
    protected $fillable = ['name', 'route_id', 'location', 'pickup_time', 'description', 'status'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

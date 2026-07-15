<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DroppingPoint extends Model
{
    protected $fillable = ['name', 'route_id', 'location', 'drop_time', 'description', 'status'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

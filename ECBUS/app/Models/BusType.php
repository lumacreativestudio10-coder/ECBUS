<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BusType extends Model
{
    protected $fillable = ['name', 'description', 'status'];
}

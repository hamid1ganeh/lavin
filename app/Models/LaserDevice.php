<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LaserDevice extends Model
{
    use SoftDeletes;
     protected $table = 'laser_devices';
     protected $fillable = ['code','name','brand','model','year','tube_id','shot'];

     public function tube()
     {
         return $this->belongsTo('App\Models\Tube','tube_id', 'id');
     }
}

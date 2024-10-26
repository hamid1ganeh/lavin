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
         return $this->belongsTo( Goods::class,'tube_id', 'id');
     }

     public function good()
     {
         if(is_null($this->tube_id))
         {
             return null;
         }

         return $this->tube->title.' '.$this->tube->brand.' ('.$this->tube->value_per_count.' '.$this->unit.')';
     }

     public function device()
     {
         return $this->name.' '.$this->brand.' '.$this->model .' (شات موجود '.$this->shot.' )';
     }
}

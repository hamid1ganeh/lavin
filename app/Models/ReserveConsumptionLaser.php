<?php

namespace App\Models;

use App\Services\ReserveService;
use Illuminate\Database\Eloquent\Model;

class ReserveConsumptionLaser extends Model
{
     protected $fillable = ['reserve_id','service_laser_id','laser_device_id','recent_shot_number','shot_number','shot','started_at','finished_at'];

     public function reserve()
     {
         return $this->belongsTo(ReserveService::class, 'reserve_id', 'id');
     }

     public function  device()
     {
         return $this->belongsTo(ServiceLaser::class , 'service_laser_id', 'id');
     }

     public function service()
     {
         return $this->belongsTo(ServiceLaser::class, 'service_laser_id', 'id');
     }
}

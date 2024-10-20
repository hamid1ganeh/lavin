<?php

namespace App\Models;

use App\Services\ReserveService;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ReserveConsumptionLaser extends Model
{
     protected $fillable = ['reserve_id','service_laser_id','laser_device_id','recent_shot_number','shot_number','shot','started_at','finished_at'];

     public function reserve()
     {
         return $this->belongsTo(ReserveService::class, 'reserve_id', 'id');
     }

     public function  device()
     {
         return $this->belongsTo(LaserDevice::class , 'laser_device_id', 'id');
     }

     public function service()
     {
         return $this->belongsTo(ServiceLaser::class, 'service_laser_id', 'id');
     }

    public function startedAt()
    {
        return Jalalian::forge($this->started_at)->format('d %B Y ساعت H:i');
    }

    public function finishedAt()
    {
        return Jalalian::forge($this->finished_at)->format('d %B Y ساعت H:i');
    }

}

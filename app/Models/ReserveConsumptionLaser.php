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
        return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
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

    public function getStart()
    {
        return   CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d H:i:s',strtotime($this->started_at))) ;
    }

    public function getEnd()
    {
        return   CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d H:i:s',strtotime($this->finished_at))) ;
    }

    public function scopeFilter($query)
    {
        //فیلتر زمان مصرف از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d H:i:s', $since)->toCarbon("Y-m-d H:i:s");
            $query->where('started_at','>=', $since);
        }

        //فیلتر زمان مصرف تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d H:i:s', $until)->toCarbon("Y-m-d H:i:s");
            $query->where('finished_at','<=', $until);
        }


        $fullname = request('fullname');
        if(isset($fullname) && $fullname!='')
        {
            $query->whereHas('reserve',function($qc) use($fullname){
                $qc->whereHas('user',function ($q) use($fullname){
                    $q->where('firstname','like','%'.$fullname.'%');
                });
            });
        }

        $mobile = request('mobile');
        if(isset($mobile) && $mobile!='')
        {
            $query->whereHas('reserve',function($qc) use($mobile){
                $qc->whereHas('user',function ($q) use($mobile){
                    $q->where('mobile','like','%'.$mobile.'%');
                });
            });
        }

        $services = request('services');
        if(isset($services) && $services !=''){
            $query->whereIn('service_laser_id',$services);
        }

        $lasers = request('lasers');
        if(isset($lasers) && $lasers !=''){
            $query->whereIn('laser_device_id',$lasers);
        }


        return $query;
    }

}

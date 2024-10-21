<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class LaserTubeHistory extends Model
{
     protected $fillable=['laser_device_id','goods_id','good_title','good_brand','changed_by','shot','waste','description'];

     public function device()
     {
         return $this->belongsTo(LaserDevice::class,'laser_device_id','id');
     }

     public function good()
     {
         return $this->belongsTo(Goods::class,'goods_id','id');
     }

     public function changedBy()
     {
         return $this->belongsTo(Admin::class,'changed_by','id');
     }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('d %B Y H:i:s',strtotime($this->crated_at)));
    }
}

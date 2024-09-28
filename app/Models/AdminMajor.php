<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class AdminMajor extends Model
{
     protected $fillable=['admin_id','major','orientation','level','center_name','start','end','grade','city_id','province_id'];

     public function admin()
     {
         return $this->hasOne(Admin::class)->withTrashed();
     }

    public function proviance()
    {
        return $this->hasOne(Province::class,'id','province_id');
    }

    public function city()
    {
        return $this->hasOne(City::class,'id','city_id');
    }

    public function startAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->start)));
    }

    public function endAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->end)));
    }
}

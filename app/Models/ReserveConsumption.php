<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ReserveConsumption extends Model
{
   protected $fillable = ['reserve_id','warehouse_id','goods_id','unit','value','price_per_unit','total_price',];

   public function reserve()
   {
     return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
   }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
    }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('d %B Y ساعت H:i:s',strtotime($this->created_at)));
    }

    public function scopeFilter($query)
    {
        //فیلتر زمان مصرف از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d H:i:s', $since)->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان مصرف تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d H:i:s', $until)->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','<=', $until);
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

        $goods = request('goods');
        if(isset($goods) && $goods !=''){
            $query->whereIn('goods_id',$goods);
        }

        $warehouses = request('warehouses');
        if(isset($warehouses) && $warehouses !=''){
            $query->whereIn('warehouse_id',$warehouses);
        }


        return $query;
    }

}

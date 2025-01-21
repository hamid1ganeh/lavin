<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ReserveInvoice extends Model
{
    protected $fillable=['reserve_id','number','price','discount_id','discount_price','discount_description','sum_upgrades_price','final_price','settlement'];

    public function reserve()
    {
        return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class,'discount_id','id');
    }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->created_at)));
    }


    public function scopeFilter($query)
    {
        $user = request('user');
        if(isset($user) && $user!='')
        {
            $query->whereHas('reserve',function($q) use($user){
                $q->whereHas('user',function($qr) use($user){
                    $qr->where('firstname','like','%'.$user.'%')
                                ->orWhere('lastname','like','%'.$user.'%')
                                ->orWhere('mobile','like','%'.$user.'%');
                    });
            });
        }

        $services = request('services');
        if(isset($services) && $services!='')
        {
            $query->whereHas('reserve',function($q) use($services){
                $q->whereIn('detail_id',$services);
            });
        }

        $code = request('code');
        if(isset($code) && $code!='')
        {
            $query->whereHas('reserve',function($q) use($code){
                $q->whereHas('reception',function ($qr) use($code){
                    $qr->where('code',$code);
                });
            });
        }

        $branches = request('branches');
        if(isset($branches) && $branches!='')
        {
            $query->whereHas('reserve',function($q) use($branches){
                $q->whereIn('branch_id',$branches);
            });
        }

        //فیلتر شماره فاکتور
        $number = request('number');
        if(isset($number) &&  $number!='')
        {
            $query->where('number','like','%'.$number.'%');
        }

        //فیلتر زمان ایجاد از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =   faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d H:i:s', $since.' 00:00:00')->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان ایجاد تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d H:i:s', $until.' 23:59:59')->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','<=', $until);
        }

        return $query;
    }

}

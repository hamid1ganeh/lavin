<?php

namespace App\Models;

use App\Services\FunctionService;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ComplicationItem extends Model
{
  protected $fillable = ['reserve_id','prescription','explain','status'];

    public function reserve()
    {
        return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
    }

    public function complications()
    {
        return $this->belongsToMany(Complication::class,'complication_complication_item','item_id','complication_id');
    }

    public function reserves()
    {
        return $this->hasMany(ServiceReserve::class,'complication_id','id');
    }


    public function register_at()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('d %B Y',strtotime($this->created_at)));
    }


    public function scopeFilter($query)
    {
        $name = request('name');
        if(isset($name) && $name!='')
        {
            $query->whereHas('reserve',function ($q) use ($name){
                $q->whereHas('user', function ($qr) use ($name){
                    $qr->where('firstname','like','%'.$name.'%')
                        ->orWhere('lastname','like','%'.$name.'%');
                });
            });
        }

        $mobile = request('mobile');
        if(isset($mobile) && $mobile!='')
        {
            $query->whereHas('reserve',function ($q) use ($mobile){
                $q->whereHas('user', function ($qr) use ($mobile){
                    $qr->where('mobile','like','%'.$mobile.'%');
                });
            });
        }

        $services = request('services');
        if(isset($services) && $services!='')
        {
            $query->whereHas('reserve',function ($q) use ($services){
                $q->whereIn('detail_id',$services);
            });
        }

        $status = request('status');
        if(isset($status) && $status!='')
        {
            $query->whereIn('status',$status);
        }

        $prescription = request('prescription');
        if(isset($prescription) &&  $prescription!='')
        {
            $query->where('prescription','like', '%'.$prescription.'%');
        }

        $complications = request('complications');
        if(isset($complications) && $complications!='')
        {
            $query->whereHas('complications',function ($q) use ($complications){
                $q->whereIn('id',$complications);
            });
        }

        //فیلتر زمان ثبت از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $fuctionService = new FunctionService();
            $since   =  $fuctionService->faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان ثبت تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $fuctionService = new FunctionService();
            $until =  $fuctionService->faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
            $query->where('created_at','<=', $until);
        }

        return $query;

    }

}

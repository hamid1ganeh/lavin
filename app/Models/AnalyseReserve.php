<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ReserveStatus;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class AnalyseReserve extends Model
{
     protected $fillable = ['user_id','analyse_id','doctor_id','response','voice','image_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function doctor()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function responseImage()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }

    public function ask_date_time()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($this->created_at)));
    }

    public function getStatus()
    {
        switch ($this->status) {
            case ReserveStatus::waiting:
                $res = "درانتظار";
                break;
            case ReserveStatus::confirm:
                $res = "تایید";
                break;
            case ReserveStatus::cancel:
                $res = "لغو";
                break;
            case ReserveStatus::secratry:
                $res = "ارجاع به منشی";
                break;
            case ReserveStatus::done:
                $res = "انجام شده";
                break;
            case ReserveStatus::paid:
                $res = "پرداخت شده";
                break;
        }

        return  $res;
    }

    public function scopeFilter($query)
    {
        $user = request('user');
        if(isset($user) && $user!='')
        {
            $query->whereHas('user',function($q) use($user){
                $q->where('firstname','like','%'.$user.'%')
                    ->orWhere('lastname','like','%'.$user.'%')
                    ->orWhere('mobile','like','%'.$user.'%');
            });
        }

        $services = request('services');
        if(isset($services) && $services!='')
        {
            $query->whereIn('analyse_id',$services);
        }

        $doctors = request('doctors');
        if(isset($doctors) && $doctors!='')
        {
            $query->whereIn('doctor_id',$doctors);
        }

        $status = request('status');
        if(isset($status) && $status!='')
        {
            $query->whereIn('status',$status);
        }

        //فیلتر زمان درخواست از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d H:i");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان درخواست تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d H:i");
            $query->where('created_at','<=', $until);
        }

        return $query;
    }

}

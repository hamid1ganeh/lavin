<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class Adviser extends Model
{
    protected $fillable = ['number_id','service_id','adviser_id','operator_id','arrangement_id','management_id','adviser_description','status','festival_id'];

    public function number()
    {
        return $this->belongsTo(Number::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceDetail::class,'service_id','id')->withTrashed();
    }

    public function advisers()
    {
        return $this->hasMany(AdviserHistory::class);
    }

    public function adviser()
    {
        return $this->belongsTo(Admin::class,'adviser_id','id')->withTrashed();

    }

    public function operator()
    {
        return $this->belongsTo(Admin::class,'operator_id','id')->withTrashed();
    }

    public function arrangement()
    {
        return $this->belongsTo(Admin::class,'arrangement_id','id')->withTrashed();
    }

    public function management()
    {
        return $this->belongsTo(Admin::class,'management_id','id')->withTrashed();
    }

    public function reserve()
    {
        return $this->hasOne(ServiceReserve::class,'adviser_id','id');
    }

    public function reviews()
    {
        return  $this->morphMany(Review::class,'reviewable');
    }

    public function review()
    {
        return  $this->morphOne(Review::class,'reviewable');
    }

    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }
    public function adviser_date_time()
    {
        if($this->adviser_date_time === null)
        {
            return "";
        }
        else
        {
            return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s l Y/m/d',strtotime($this->adviser_date_time)));
        }
    }

    public function scopeFilter($query)
    {
        $firstname = request('firstname');
        if(isset($firstname) && $firstname != '')
        {
            $query->whereHas('number',function ($q) use ($firstname){
              $q->where('firstname','like','%'.$firstname.'%');
            });
        }

         $lastname = request('lastname');
        if(isset($lastname) && $lastname != '')
        {
            $query->whereHas('number',function ($q) use ($lastname){
                $q->where('lastname','like','%'.$lastname.'%');
            });
        }

        $mobile = request('mobile');
        if(isset($mobile) && $mobile != '')
        {
            $query->whereHas('number',function ($q) use ($mobile){
                $q->where('mobile','like','%'.$mobile.'%');
            });
        }

        $status = request('status');
        if(isset($status) && $status != '')
        {
            $query->whereIn('status',$status);
        }

        $type = request('type');
        if(isset($type) && $type != '')
        {
            $query->whereIn('in_person',$type);
        }

    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Poll extends Model
{
    protected $fillable=['user_id','admin_id','reserve_id','service','text','duration','serviceQuality'];

    function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function reserve()
    {
        return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
    }

    public function created_at()
    {
        return Jalalian::forge($this->time)->format('d %B Y ساعت H:i');
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


        $admins = request('admins');
        if(isset($admins) && $admins!='')
        {
            $query->whereIn('admin_id',$admins);
            if(in_array('0',$admins)){
                $query->orWhereNull('admin_id');
            }
        }

        //فیلتر زمان  از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان  تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
            $query->where('created_at','<=', $until);
        }

        $services = request('services');
        if(isset($services) && $services!='')
        {
            $query->whereHas('reserve',function ($q) use ($services){
                    $q->whereIn('detail_id',$services);
            });
        }

        return $query;

    }
}

<?php

namespace App\Models;

use App\Enums\ReserveStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class ReserveUpgrade extends Model
{
    protected $fillable=['reserve_id','service_id','service_name','detail_id','detail_name',
    'price','asistant1_id','asistant2_id','desc','status','doneTime'];

    public function service()
    {
       return $this->belongsTo(Service::class);
    }

    public function detail()
    {
       return $this->belongsTo(ServiceDetail::class);
    }

    public function reserve()
    {
        return $this->belongsTo(ServiceReserve::class);
    }

    public function asistant1()
    {
       return $this->belongsTo(Admin::class,'asistant1_id')->withTrashed();
    }

    public function asistant2()
    {
       return $this->belongsTo(Admin::class,'asistant2_id')->withTrashed();
    }

    public function created_at()
    {
        return Jalalian::forge($this->created_at)->format('d %B Y ساعت H:i');
    }

    public function done_time()
    {
        if(is_null($this->doneTime)){
            return null;
        }
        return Jalalian::forge($this->doneTime)->format('d %B Y ساعت H:i');
    }

    public function duration()
    {

        if(is_null($this->doneTime)){
            return null;
        }

        return Carbon::parse($this->created_at)->diffInHours(Carbon::parse($this->doneTime)). ' ساعت ';
    }

    public function getStatus()
    {
        switch ($this->status) {
            case ReserveStatus::waiting:
                $res = "درانتظار";
                break;
            case ReserveStatus::done:
                $res = "انجام شده";
                break;
            case ReserveStatus::cancel:
                $res = "لغو شده";
                break;
        }

        return $res;
    }

    public function scopeFilter($query)
    {
        $user = request('user');
        if(isset($user) && $user!='')
        {
            $query->whereHas('reserve',function($qry) use($user){
                $qry->whereHas('user',function($q) use($user){
                    $q->where('firstname','like','%'.$user.'%')
                        ->orWhere('lastname','like','%'.$user.'%')
                        ->orWhere('mobile','like','%'.$user.'%');
                    });
            });
        }

        $services = request('services');
        if(isset($services) &&  $services!='')
        {
            $query->whereIn('detail_id',$services);
        }

        $services = request('services');
        if(isset($services) &&  $services!='')
        {
            $query->whereIn('detail_id',$services);
        }

        $secretaries = request('secretaries');
        if(isset($secretaries) &&  $secretaries!='')
        {
            $query->whereHas('reserve',function ($q) use ($secretaries) {
                $q->whereIn('secratry_id',$secretaries);
            });
        }

        $status = request('status');
        if(isset($status) &&  $status!='')
        {
            $query->whereIn('status',$status);
        }

        $asistants1 = request('asistants1');
        if(isset($asistants1) &&  $asistants1!='')
        {
            $query->whereIn('asistant1_id',$asistants1);
        }

        $asistants2 = request('asistants2');
        if(isset($asistants2) &&  $asistants2!='')
        {
            $query->whereIn('asistant2_id',$asistants2);
        }

        //فیلتر تاریخ از
        $since = request('since_done');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d H:i");
            $query->where('created_at','>=',$since);
        }

        //فیلتر تاریخ تا
        $until = request('until_done');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d H:i");
            $query->where('created_at','<=',$until);
        }


        $branches = request('branches');
        if(isset($branches) &&  $branches!='')
        {
            $query->whereHas('reserve',function ($q) use ($branches) {
                $q->whereIn('branch_id',$branches);
            });
        }

        $receptions = request('receptions');
        if(isset($receptions) && $receptions!='')
        {
            $query->whereHas('reserve',function($qry) use($receptions){
                $qry->whereHas('reception',function($q) use($receptions){
                    $q->where('reception_id',$receptions);
                });
            });
        }


        $code = request('code');
        if(isset($code) && $code!='')
        {
            $query->whereHas('reserve',function($qry) use($code){
                $qry->whereHas('reception',function($q) use($code){
                    $q->where('code',$code);
                });
            });
        }


    }

}

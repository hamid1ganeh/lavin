<?php

namespace App\Models;

use App\Services\ReserveService;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PaymentStatus;
use App\Enums\ReserveStatus;
use App\Enums\ReserveType;
use App\Enums\AnaliseStatus;
use Carbon\Carbon;

class ServiceReserve extends Model
{
   use SoftDeletes;

    protected $fillable = [ 'user_id','branch_id','service_id',
                            'service_name','detail_id','detail_name',
                            'doctor_id','secratary_id','asistant_id',
                            'reception_id','adviser_id','time','complication_id',
                            'status','type','code','doneTime','reception_desc',
                            'total_price','price_description'];

    public function user()
    {
       return $this->belongsTo(User::class)->withTrashed();
    }

    public function service()
    {
       return $this->belongsTo(Service::class);
    }

    public function detail()
    {
       return $this->belongsTo(ServiceDetail::class);
    }

    public function reviews()
    {
       return  $this->morphMany(Review::class,'reviewable');
    }

    public function doctor()
    {
       return $this->belongsTo(Admin::class,'doctor_id','id')->withTrashed();
    }

    public function poll()
    {
        return $this->hasOne(Poll::class,'reserve_id');
    }

    public function complicationItem()
    {
        return $this->bel(ComplicationItem::class,'complication_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function payment()
    {
        return $this->hasOne(ReservePayment::class,'reserve_id');
    }

    public function reception()
    {
        return $this->belongsTo(Reception::class,'reception_id');
    }

    public function invoice()
    {
        return $this->hasOne(ReserveInvoice::class,'reserve_id','id');
    }

    public function review()
    {
       return  $this->morphOne(Review::class,'reviewable');
    }

    public function adviser()
    {
        return $this->belongsTo(Adviser::class,'adviser_id','id');
    }

    public function complication()
    {
        return $this->hasOne(RegisterComplication::class,'reserve_id','id');
    }


    public  function upgrades()
    {
        return $this->hasMany(ReserveUpgrade::class,'reserve_id','id');
    }

    public  function confirmedUpgrades()
    {
        return $this->hasMany(ReserveUpgrade::class,'reserve_id','id')->where('status',ReserveStatus::confirm);
    }

    public function reserve_time()
    {
        if(is_null($this->created_at)){
            return null;
        }

        return Jalalian::forge($this->created_at)->format('d %B Y ساعت H:i');
    }

    public function round_time()
    {
        if(is_null($this->time)){
            return null;
        }

        return Jalalian::forge($this->time)->format('d %B Y ساعت H:i');
    }

    public function round_time2()
    {
        if(is_null($this->time)){
            return null;
        }

      return Jalalian::forge($this->time)->format('Y/m/d H:i');
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

        if(is_null($this->doneTime) || is_null($this->time)){
            return null;
        }

        return Carbon::parse($this->time)->diffInHours(Carbon::parse($this->doneTime)). 'ساعت ';
    }

    public function getStatus()
    {
        switch ($this->status) {
            case ReserveStatus::waiting:
                $res = "در انتظار رزرو";
                break;
            case ReserveStatus::confirm:
                $res = "تایید";
                break;
            case ReserveStatus::cancel:
                $res = "لغو";
                break;
            case ReserveStatus::accept:
                $res = "پذیرش";
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
            case ReserveStatus::end:
                $res = "پایان کار";
                break;
            case ReserveStatus::wittingForAdviser:
                $res = "در انتظار مشاور";
                break;
            case ReserveStatus::Adviser:
                $res = "مشاور";
                break;
        }
        return  $res;
    }

    public function getType()
    {
        switch ($this->type) {
            case ReserveType::adivser:
                $res = "مشاوره";
                break;
            case ReserveType::inPerson:
                $res = "حضوری";
                break;
            case ReserveType::online:
                $res = "آنلاین";
                break;
            case ReserveType::complication:
                $res = "عوارض";
                break;
        }

        return  $res;
    }
    public function paid()
    {
        if($this->payment == null || $this->payment->status != PaymentStatus::paid)
        {
            return false;
        }
        return true;
    }

    public function upgradesCount()
    {
        return ReserveUpgrade::where('reserve_id',$this->id)->where('status',ReserveStatus::confirm)->count();
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

       $levels = request('levels');
       if(isset($levels) && $levels!='')
       {
            $query->whereHas('user',function($q) use($levels){
               $q->whereIn('level_id',$levels);
            });
       }

       $services = request('services');
       if(isset($services) && $services!='')
       {
            $query->whereIn('detail_id',$services);
       }


        $code = request('code');
        if(isset($code) && $code!='')
        {
            $query->whereHas('reception',function ($q) use($code){
                $q->where('code',$code);
            });
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
           if (in_array(ReserveStatus::paid,$status) ){
               $query->orWhereHas('payment',function($q){
                   $q->where('status',PaymentStatus::paid);
               });
           }
       }

        $type = request('type');
        if(isset($type) && $type!='')
        {
            $query->whereIn('type',$type);
        }


        $branches = request('branches');
        if(isset($branches) && $branches!='')
        {
            $query->whereIn('branch_id',$branches);
        }

        $receptions = request('receptions');
        if(isset($receptions) && $receptions!='')
        {
            $query->whereIn('reception_id',$receptions);
        }

        $complications = request('complications');
        if(isset($complications) && $complications!='')
        {
            $query->whereIn('complication_id',$complications);
        }

        //فیلتر زمان رزرو از
      $since_reserve = request('since_reserve');
      if(isset($since_reserve) &&  $since_reserve!='')
      {
         $since_reserve =  faToEn($since_reserve);
         $since_reserve = Jalalian::fromFormat('Y/m/d H:i', $since_reserve)->toCarbon("Y-m-d H:i");
         $query->where('created_at','>=', $since_reserve);
      }

      //فیلتر زمان رزرو تا
      $until_reserve = request('since_reserve');
      if(isset($until_reserve) &&  $until_reserve!='')
      {
         $until_reserve =  faToEn($until_reserve);
         $until_reserve = Jalalian::fromFormat('Y/m/d H:i', $until_reserve)->toCarbon("Y-m-d H:i");
         $query->where('created_at','<=', $until_reserve);
      }

      //فیلتر زمان رزرو از
      $since = request('since');
      if(isset($since) &&  $since!='')
      {
         $since =  faToEn($since);
         $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d");
         $query->where('created_at','>=', $since);
      }

      //فیلتر زمان رزرو تا
      $until = request('until');
      if(isset($until) &&  $until!='')
      {
         $until =  faToEn($until);
         $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
         $query->where('created_at','<=', $until);
      }

         return $query;
    }

}

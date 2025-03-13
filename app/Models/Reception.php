<?php

namespace App\Models;

use App\Services\ReserveService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use Auth;

class Reception extends Model
{
    protected  $fillable = ['code','user_id','reception_id','end','endTime','found_status','branch_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function reception()
    {
        return $this->belongsTo(Admin::class,'reception_id')->withTrashed();
    }

    public function reserves()
    {
        return $this->hasMany(ServiceReserve::class,'reception_id','id');
    }

    public function founderBranchesReserves()
    {
        return $this->hasMany(ServiceReserve::class,'reception_id','id')->whereIn('branch_id',Auth::guard('admin')->user()->branches->pluck('id')->toArray());
    }

    public function hasOpenReferCode()
    {
         return Reception::where('user_id',$this->user_id)->where('end',false)->first();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function invoie()
    {
        return $this->hasOne(ReceptionInvoice::class,'reception_id','id');
    }

    public function scopeFilter($query)
    {
        $mobile = request('mobile');
        $nationCode = request('nation_code');
        if((isset($mobile) && $mobile!='') || (isset($nationCode) && $nationCode!=''))
        {
            $query->whereHas('user',function($q) use($mobile,$nationCode){
                $q->Where('nationcode',$nationCode)
                    ->orWhere('mobile',$mobile);
            });
        }

        $code = request('code');
        if(isset($code) && $code!='')
        {
          $query->Where('code',$code);
        }

        return $query;

    }

}

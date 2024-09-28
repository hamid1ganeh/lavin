<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Morilog\Jalali\Jalalian;
use Morilog\Jalali\CalendarUtils;
use App\Services\FunctionService;

class SmsHistory extends Model
{
    private $fuctionService;

    public function __construct()
    {
        $this->fuctionService = new FunctionService();
    }


    protected $fillable=['content','mobile','admin_id'];

    public function sender()
    {
        return $this->belongsTo(Admin::class,'admin_id')->withTrashed();
    }

    public function send_date_time()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s l Y/m/d',strtotime($this->created_at)));
    }

    public function scopeFilter($query)
    {
        //فیلتر فرستنده
        $admins = request('admins');
        if(isset($admins) && $admins!='')
        {
             $query->whereHas('sender',function($q) use($admins){
                $q->whereIn('admin_id',$admins);
            });
        }

        //فیلتر شماره موبایل
        $mobile = request('mobile');
        if(isset($mobile) &&  $mobile!='')
        {
            $query->where('mobile','like','%'.$mobile.'%');
        }


        //فیلتر شماره محتوا
        $content = request('content');
        if(isset($content) &&  $content!='')
        {
            $query->where('content','like','%'.$content.'%');
        }



        //فیلتر زمان ارسال از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  $this->fuctionService->faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d H:i:s', $since)->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','>=', $since);
        }

        //فیلتر زمان ارسال تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  $this->fuctionService->faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d H:i:s', $until)->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','<=', $until);
        }


        return  $query;

    }
}

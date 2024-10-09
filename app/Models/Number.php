<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use  App\Enums\NumberStatus;
use  App\Enums\NumberType;
use Morilog\Jalali\Jalalian;

class Number extends Model
{
    protected $fillable = ['user_id','operator_id','management_id','firstname','lastname','mobile','type',
        'information','status','operator_description','adviser_description'];

    public function user()
    {
        return $this->belongsTO(User::class)->withTrashed();
    }

    public function operator()
    {
        return $this->belongsTo(Admin::class,'operator_id','id')->withTrashed();
    }

    public function advisers()
    {
        return $this->hasMany(Adviser::class,'number_id');
    }

    public function management()
    {
        return $this->belongsTo(Admin::class,'management_id','id')->withTrashed();
    }

    public function operators()
    {
        return $this->hasMany(PhoneOperatorHistory::class)->orderBy('created_at','asc');
    }

    public function suggestions()
    {
        return $this->belongsToMany(ServiceDetail::class,'service_suggesteds','number_id','service_id');
    }

    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }

    public function operator_date_time()
    {
        if($this->operator_date_time === null)
        {
            return "";
        }
        else
        {
            return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s l Y/m/d',strtotime($this->operator_date_time)));
        }
    }

    public function accept_date_time()
    {
        if($this->accept_date_time === null)
        {
            return "";
        }
        else
        {
            return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s l Y/m/d',strtotime($this->accept_date_time)));
        }
    }

    public function club()
    {
        return User::where('mobile',$this->mobile)->withTrashed()->exists();
    }

    public function getUserByMobile()
    {
        return User::where('mobile',$this->mobile)->withTrashed()->first();
    }

    public function completeInfo ()
    {
        $user = $this->getUserByMobile();
        if(!is_null($user) &&
            UserInfo::where('user_id',$user->id)->exists() &&
            UserAddress::where('user_id',$user->id)->exists()){
            return  true;
        }

        return false;
    }

    public function getStatus()
    {
        switch ($this->status) {
            case NumberStatus::NoAction:
                $res = "بلاتکلیف";
                break;
            case NumberStatus::Operator:
                $res = "اپراتور";
                break;
            case NumberStatus::NoAnswer:
                $res = "عدم پاسخگویی";
                break;
            case NumberStatus::Answer:
                $res = "پاسخ داده";
                break;
            case NumberStatus::WaitingForAdviser:
                $res = "درخواست مشاور";
                break;
            case NumberStatus::Adviser:
                $res = "مشاور";
                break;
            case NumberStatus::Accept:
                $res = "پذیرش";
                break;
            case NumberStatus::Cancel:
                $res = "لغو";
                break;
            case NumberStatus::WaitnigForDocuments:
                $res = "در انتظار ارسال مدارک";
                break;
            case NumberStatus::RecivedDocuments:
                $res = "دریافت مدارک";
                break;
            case NumberStatus::Reservicd:
                $res = "رزرو شده";
                break;
            case NumberStatus::NextNotice:
                $res = "اطلاع بعدی";
                break;
            default:
                $res = "بلاتکلیف";
        }

        return  $res;
    }

    public function getType()
    {
        switch ($this->type) {
            case NumberType::his:
                $res = "HIS";
                break;
            case NumberType::instagram:
                $res = "اینستاگرام";
                break;
            case NumberType::telegram:
                $res = "تلگرام";
                break;
            case NumberType::sms:
                $res = "پیامک";
                break;
            case NumberType::lahijan:
                $res = "شعبه لاهیجان";
                break;
            case NumberType::tehran:
                $res = "شعبه تهران";
                break;
            case NumberType::hozoori:
                $res = "حضوری";
                break;
            case NumberType::call:
                $res = "تماس های ورودی";
                break;
        }
        return  $res;
    }
    public function scopeFilter($query)
    {
        $firstname = request('firstname');
        if(isset($firstname) && $firstname != '')
        {
            $query->where('firstname','like','%'.$firstname.'%');
        }

        $lastname = request('lastname');
        if(isset($lastname) && $lastname != '')
        {
            $query->where('lastname','like','%'.$lastname.'%');
        }

        $mobile = request('mobile');
        if(isset($mobile) && $mobile != '')
        {
            $query->where('mobile','like','%'.$mobile.'%');
        }

        $information = request('information');
        if(isset($information) && $information != '')
        {
            $query->where('information','like','%'.$information.'%');
        }

        $operators = request('operators');
        if(isset($operators) && $operators != '')
        {
            $query->whereIn('operator_id',$operators);
        }


        $status = request('status');
        if(isset($status) && $status != '')
        {
            $query->whereIn('status',$status);
        }

        $type = request('type');
        if(isset($type) && $type != '')
        {
            $query->whereIn('type',$type);
        }

        $advisers = request('advisers');
        if(isset($advisers) && $advisers != '')
        {
            $query->whereHas('advisers',function($q) use ($advisers){
                $q->whereIn('adviser_id',$advisers);
            });
        }


        $festivals = request('festivals');
        if(isset($festivals) && $festivals != '')
        {
            $query->whereIn('festival_id',$festivals);
        }

        //فیلتر زمان اپراتور  از
        $since_operator = request('since_operator');
        if(isset($since_operator) &&  $since_operator!='')
        {
            $since_operator =  faToEn($since_operator);
            $since_operator = Jalalian::fromFormat('Y/m/d H:i', $since_operator)->toCarbon("Y-m-d H:i");
            $query->where('operator_date_time','>=', $since_operator);
        }

        //فیلتر زمان اپراتور تا
        $until_operator = request('until_operator');
        if(isset($until_operator) &&  $until_operator!='')
        {
            $until_operator =  faToEn($until_operator);
            $until_operator = Jalalian::fromFormat('Y/m/d H:i', $until_operator)->toCarbon("Y-m-d H:i");
            $query->where('operator_date_time','<=', $until_operator);
        }


    }
}

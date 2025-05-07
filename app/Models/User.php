<?php

namespace App\Models;

use App\Enums\genderType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','mobile','verify_code','verify_expire','verified','gender','code','introduced',
        'point','email','level_id','seller','email_verified_at','password','token','nationcode'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }

    public function usedDiscount()
    {
        return $this->HasMany(DiscountUsed::class);
    }

    public function cart()
    {
        return $this->HasMany(Cart::class);
    }

    public function reserves()
    {
        return $this->HasMany(ServiceReserve::class);
    }

    public  function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function bank()
    {
        return $this->hasOne(UserBank::class);
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function email()
    {
        if($this->info==null || $this->info->email==null)
        {
            return '-@gmail.com';
        }

        return  $this->info->email;
    }

    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function birthDate()
    {
        if($this->info != null && $this->info->birthDate != null){
            return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->info->birthDate)));
        }
         return null;
    }

    public function married()
    {
        if($this->info != null && $this->info->married){
            return  'متاهل';
        }
        return 'مجرد';
    }

    public function marriageDate()
    {
        if($this->info != null && $this->info->marriageDate){
            return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->info->marriageDate)));
        }
        return null;
    }


    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketMessages()
    {
        return $this->morphMany(TicketMessage::class,'senderable');
    }

    public function discountsNumber()
    {
        return   Discount::whereHas('users',function($q){
            $q->where('user_id',$this->id);
        })->whereDoesntHave('usedDiscount')->count();
    }


    public function scopeFilter($quary)
    {
        $name = request('name');
        if(isset($name) && $name != '')
        {
            $quary->where('firstname','like','%'.$name.'%')->orWhere('lastname','like','%'.$name.'%');
        }

        $mobile = request('mobile');
        if(isset($mobile) && $mobile != '')
        {
            $quary->where('mobile',$mobile );
        }

        $code = request('code');
        if(isset($code) && $code != '')
        {
            $quary->where('code',$code );
        }

        $introduced = request('introduced');
        if(isset($introduced) && $introduced != '')
        {
            $quary->where('introduced',$introduced );
        }

        //فیلتر براساس جنسیت
        $gender = request('gender');
        if(isset($gender) &&  $gender!='')
        {
            $quary->WhereIn('gender',$gender);
        }

        //فیلتر براساس سطح
        $levels = request('levels');
        if(isset($levels) &&  $levels!='')
        {
            $quary->WhereIn('level_id',$levels);
        }


        //فیلتر براساس ایمیل
        $email = request('email');
        if(isset($email) && $email != '')
        {
            $quary->whereHas('info',function($q) use($email){
                $q->where('email',$email);
            });
        }

       //فیلتر براساس شماره ملی
        $nationcode = request('nationcode');
        if(isset($nationcode) && $nationcode != '')
        {
            $quary->where('nationcode',$nationcode);
        }

        //فیلتر تاریخ ثبت نام از
        $since = request('since');
        if(isset($since) &&  $since!='')
        {
            $since =  faToEn($since);
            $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d H:i");
            $quary->where('created_at','>=',$since);
        }

         //فیلتر تاریخ ثبت نام تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d H:i");
            $quary->where('created_at','<=',$until);
        }

        //فیلتر تاریخ تولد از
        $sinceBirthday = request('since_birthday');
        if(isset($sinceBirthday) &&  $sinceBirthday!='')
        {
            $sinceBirthday =  faToEn($sinceBirthday);
            $sinceBirthday = Jalalian::fromFormat('Y/m/d', $sinceBirthday)->toCarbon("Y-m-d");

            $quary->whereHas('info',function($q) use($sinceBirthday){
                   $q->whereNotNull('birthDate')->where('birthDate','>=', $sinceBirthday);
               });
        }
        //فیلتر تاریخ تولد تا
        $untilBirthday = request('until_birthday');
        if(isset($untilBirthday) &&  $untilBirthday!='')
        {
            $untilBirthday =  faToEn($untilBirthday);
            $untilBirthday = Jalalian::fromFormat('Y/m/d', $untilBirthday)->toCarbon("Y-m-d");
            $quary->whereHas('info',function($q) use($untilBirthday){
                $q->whereNotNull('birthDate')->where('birthDate','<=', $untilBirthday);
            });
        }

        //فیلتر تاریخ ازدواج از
        $sinceMarriageDate = request('since_marriage_date');
        if(isset($sinceMarriageDate) &&  $sinceMarriageDate!='')
        {
            $sinceMarriageDate =  faToEn($sinceMarriageDate);
            $sinceMarriageDate = Jalalian::fromFormat('Y/m/d', $sinceMarriageDate)->toCarbon("Y-m-d");
            $quary->whereHas('info',function($q) use($sinceMarriageDate){
                $q->whereNotNull('marriageDate')->where('marriageDate','>=', $sinceMarriageDate);
            });
        }
        //فیلتر تاریخ ازدواج تا
        $untilMarriageDate = request('until_marriage_date');
        if(isset($untilMarriageDate) &&  $untilMarriageDate!='')
        {
            $untilMarriageDate =  faToEn($untilMarriageDate);
            $untilMarriageDate = Jalalian::fromFormat('Y/m/d', $untilMarriageDate)->toCarbon("Y-m-d");
            $quary->whereHas('info',function($q) use($untilMarriageDate){
                $q->whereNotNull('marriageDate')->where('marriageDate','<=', $untilMarriageDate);
            });
        }

        //فیلتر زمان رزرو تا
        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
            $query->where('created_at','<=', $until);
        }

        //فیلتر براساس مشاغل
        $jobs = request('jobs');
        if(isset($jobs) && $jobs != '')
        {
            $quary->whereHas('info',function($q) use($jobs){
                $q->whereIn('job_id',$jobs);
            });
        }

        //فیلتر براساس استان
        $provinces = request('provinces');
        if(isset($provinces) && $provinces != '')
        {
            $quary->whereHas('address',function($q) use($provinces){
                $q->whereIn('province_id',$provinces);
            });
        }

        //فیلتر براساس شهر
        $cities = request('cities');
        if(isset($cities) && $cities != '')
        {
            $quary->whereHas('address',function($q) use($cities){
                $q->whereIn('city_id',$cities);
            });
        }

    }
}

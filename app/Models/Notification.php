<?php

namespace App\Models;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\seenStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Auth;
use \Morilog\Jalali\Jalalian;
use App\Helpers\Helper;

class Notification extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','message','admin_id','type','status','sms'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTrashed();
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class,'notification_admin','notification_id','admin_id')->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(admin::class)->withTrashed();
    }

    public function seen()
    {
        return $this->belongsToMany(User::class)->where('seen',seenStatus::seen)->withTrashed();
    }

    public function created_at()
    {
       return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d - H:i:s',strtotime($this->created_at)));
    }

    public function seenStatus()
    {
        if(auth('sanctum')->check()){
            $user = Auth('sanctum')->id();
        }else{
            $user = Auth::id();
        }

        return DB::table('notification_user')->where('notification_id',$this->id)->where('user_id',$user)->first()->seen;
    }

    public function adminSeenStatus()
    {
        return DB::table('notification_admin')->where('notification_id',$this->id)->where('admin_id',Auth::guard('admin')->id())->first()->seen;
    }

    public function scopeFilter($query)
    {
        $title = request('title');
        if(isset($title) && $title!='')
        {
            $query->where('title','like','%'.$title.'%');
        }

        $seen = request('seen');
        if(isset($seen) && $seen!='')
        {
            $query->whereHas('users',function($q) use($seen){
               $q->WhereIn('seen',$seen);
            })->orWhereHas('admins',function($q) use($seen) {
                $q->WhereIn('seen', $seen);
            });
        }

       //فیلتر از  تاریخ ثبت ناتیفیکیش
       $since = request('since');
       if(isset($since) &&  $since!='')
       {
           $since =  faToEn($since);
           $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d");
           $query->where('created_at','>=',$since);
       }

       //فیلتر تا  تاریخ ثبت ناتیفیکیش
       $until = request('until');
       if(isset($until) &&  $until!='')
       {
           $until =  faToEn($until);
           $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d");
           $query->where('created_at','<=',$until);
       }

        $admins = request('admins');
        if(isset($admins) && $admins!='')
        {
            $query->whereIn('admin_id',$admins);
        }

        $receivers = request('receivers');
        if(isset($receivers) && $receivers!='')
        {
            $query->whereHas('admins',function ($q) use ($receivers){
                $q->whereIn('admin_id',$receivers);
            });
        }

        $users = request('users');
        if(isset($users) && $users!='')
        {
            $query->whereHas('users',function ($q) use ($users){
                $q->whereIn('user_id',$users);
            });
        }

       return $query;
    }
}

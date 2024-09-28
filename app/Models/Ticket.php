<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Part;
use \Morilog\Jalali\Jalalian;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;

class Ticket extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'department_id','admin_id','user_id','number','title','sender_type','sender_id','audience_type','audience_id','status','priority'
    ];


    function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    function messages()
    {
        return $this->hasMany(TicketMessage::class)->orderBy('created_at','asc');
    }

    function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function getPriority()
    {
        switch ($this->status) {
            case TicketPriority::Low;
                $res = "کم";
                break;
            case TicketPriority::Medium:
                $res = "متوسط";
                break;
            case TicketPriority::High;
                $res = "زیاد";
                break;
        }
        return  $res;
    }

    public function getStatus()
    {
        switch ($this->status) {
            case TicketStatus::Waiting;
                $res = "در انتظار پاسخ";
                break;
            case TicketStatus::Pending:
                $res = "درحال بررسی";
                break;
            case TicketStatus::Answerd;
                $res = "پاسخ داده شده";
                break;
            case TicketStatus::Close;
                $res = "بسته شده";
                break;
        }
        return  $res;
    }

    function scopeFilter($query)
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


        //فیلتر نام
         $title = request('title');
         if(isset($title) &&  $title!='')
         {
             $query->where('title','like', '%'.$title.'%');
         }

          //فیلتر نام
          $number = request('number');
          if(isset($number) &&  $number!='')
          {
              $query->where('number','like', '%'.$number.'%');
          }

        //فیلتر اولویت
        $priorities = request('priorities');
        if(isset($priorities) &&  $priorities!='')
        {
                $query->whereIn('priority',$priorities);
        }

        //فیلتر وضعیت
        $status = request('status');
        if(isset($status) &&  $status!='')
        {
            $query->whereIn('status',$status);
        }

        //فیلتر واحد
        $departments = request('departments');
        if(isset($departments) &&  $departments!='')
        {
            $query->whereIn('department_id',$departments);
            }

    }

    function scopeFilterDashboard($query)
    {

         $since = request('since');
         //فیلتر تاریخ سفارش
         if(isset($since) &&  $since!='')
         {
             $since =  faToEn($since);
             $since = Jalalian::fromFormat('Y/m/d', $since)->toCarbon("Y-m-d H:i:s");
             $query->where('created_at','>=',$since);
         }


        $until = request('until');
        if(isset($until) &&  $until!='')
        {
            $until =  faToEn($until);
            $until = Jalalian::fromFormat('Y/m/d', $until)->toCarbon("Y-m-d H:i:s");
            $query->where('created_at','<=',$until);
        }


        $levels = request('levels');
        if(isset($levels) && $levels!='')
        {
             $query->whereHas('user',function($q) use($levels){
                $q->whereIn('level_id',$levels);
             });
        }

    }

}

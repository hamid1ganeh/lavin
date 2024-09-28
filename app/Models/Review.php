<?php

namespace App\Models;

use App\Enums\ReviewGroupType;
use App\Services\ReserveService;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use App\Models\ServiceReserve;
use App\Models\Adviser;
use App\Models\Product;


class Review extends Model
{
    protected $fillable=['user_id','reviewable_type','reviewable_id','content','reviews'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function reserve()
    {
        return $this->belongsTo(ReserveService::class);
    }

    public function date()
    {
      return Jalalian::forge($this->created_at)->format('d %B Y');
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

        $categories = request('categories');
        if(isset($categories) && $categories!='')
        {
            $array =[];
            if(in_array(ReviewGroupType::Service,$categories)){
             array_push($array,ServiceReserve::class);
            }

            if(in_array(ReviewGroupType::Shop,$categories)){
                array_push($array,Product::class);
            }

            if(in_array(ReviewGroupType::Adviser,$categories)){
                array_push($array,Adviser::class);
            }

          $query->whereIn('reviewable_type',$array);
        }

        $admins = request('admins');
        if(isset($admins) && $admins!='')
        {
            $query->whereIn('admin_id',$admins);
            if(in_array('0',$admins)){
                $query->orWhereNull('admin_id');
            }
        }

        return $query;

    }

}

<?php

namespace App\Models;
use App\Services\ReserveService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Enums\Status;
use App\Enums\CommentStatus;
use App\Models\Review;


class ServiceDetail extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable =['service_id','name','price','porsant','point','breif','desc','status'];

    public function service()
    {
        return $this->hasOne(Service::class,'id','service_id');
    }

    public function reserves()
    {
        return $this->hasMany(ServiceReserve::class,'detail_id','id');
    }

    public function documents()
    {
        return $this->hasMany(ServiceDocument::class,'service_id')->where('status',Status::Active)->orderBy('title','asc');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }

    public function validUserDiscounts($userId)
    {
        return Discount::whereHas('services', function ($query) {
            $query->where('expire','>', Carbon::now('+3:30')->format('Y-m-d H:i:s'))->orWhereNull('expire');
            $query->where('service_detail_id',$this->id);
        })->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id',$userId);
        }) ->get();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')->where('approved',CommentStatus::approved);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function videos()
    {
        return $this->hasMany(ServiceDetailVideo::class, 'detil_id');
    }

    public function luck()
    {
        return $this->morphOne(Luck::class, 'luckable');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'detil_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(Admin::class,'doctor_service','service_id','doctor_id')->withTrashed();
    }

    public function advisers()
    {
        return $this->belongsToMany(Admin::class,'adviser_service','service_id','adviser_id')->withTrashed();
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class,'branch_service','service_id','branch_id');
    }

    public function activeBranches()
    {
        return $this->belongsToMany(Branch::class,'branch_service','service_id','branch_id')->where('status',Status::Active);
    }

    public function reviews()
    {
        return  Review::where('reviewable_type','App\Models\ServiceReserve')->whereIn('reviewable_id',$this->reserves->pluck('id'))->get();
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function scopeFilter($query)
    {
         //فیلتر عنوان
         $name = request('name');
         if(isset($name) &&  $name!='')
         {
             $query->where('name','like', '%'.$name.'%');
         }

       //فیلتر سرگروه خدمات
        $services = request('services');
        if(isset($services) &&  $services!='')
        {
            $query->whereIn('service_id',$services);
        }

        return $query;
    }

   public function getStatus()
    {
        switch ($this->status) {
            case Status::Active:
                $res = "فعال";
                break;
            case Status::Deactive:
                $res = "غیرفعال";
                break;
            case Status::Pending:
                $res = "درانتظار";
                break;
        }
        return  $res;

    }
}

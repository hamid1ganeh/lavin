<?php

namespace App\models;

use App\Enums\ArticleStatus;
use App\Enums\SendState;
use App\Enums\Status;
use App\Models\Image;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;
use Cviebrock\EloquentSluggable\Sluggable;

class Service extends Model
{
    use SoftDeletes;
    use Sluggable;
    protected $fillable =['name','parent','child','status','displayed','desc','slogan','label'];

    public function parent_cat()
    {
        return $this->hasOne(ServiceCategory::class,'id','parent');
    }

    public function child_cat()
    {
        return $this->hasOne(ServiceCategory::class,'id','child');
    }

    public Function details()
    {
        return $this->hasMany(ServiceDetail::class,'service_id');
    }

    public Function activeServices()
    {
        return $this->hasMany(ServiceDetail::class,'service_id')->where('status',Status::Active);
    }

    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function publishedArticles()
    {
        return $this->belongsToMany(Article::class)
                ->where('status',ArticleStatus::publish)
                ->where('publishDateTime','<=',Carbon::now('+3:30'));
    }


    public function get_thumbnail($size)
    {
        return $this->thumbnail->path.$this->thumbnail->name[$size];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}

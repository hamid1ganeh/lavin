<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategories extends Model
{
    use SoftDeletes;
    use Sluggable;

     protected $fillable=['name','slug','status'];


     public function articles()
     {
         return $this->belongsToMany(Article::class);
     }

    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function get_thumbnail($size)
    {
        if (is_null($this->thumbnail)){
            return "نامحدود";
        }
        return url('/').'/'.$this->thumbnail->path.$this->thumbnail->name[$size];
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
        $name = request('name');
        if(isset($name) && $name!='')
        {
            $query->where('name','like','%'.$name.'%');
        }
     }
}

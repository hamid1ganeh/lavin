<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Analyse extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable=['title','slug','description','min_price','max_price','status'];

     public function images()
     {
        return $this->hasMany(AnalyseImage::class,'analyse_id');
     }
    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function get_thumbnail($size)
    {
        return url('/').'/'.$this->thumbnail->path.$this->thumbnail->name[$size];
    }

    public function getThumbnail()
    {
        return Image::where('imageable_id',$this->id)->where('imageable_type',get_class($this))->first(['name','alt','title']);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ProductCategory extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable = ['parent_id','name','slug','status'];

    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getThumbnail($size)
    {
        if (is_null($this->thumbnail)){
            return null;
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

}

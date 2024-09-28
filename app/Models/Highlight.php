<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $fillable = ['title','status'];

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function activeStories()
    {
        return $this->hasMany(Story::class)->where('status',Status::Active);
    }

    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getThumbnail($size)
    {
        return url('/').'/'.$this->thumbnail->path.$this->thumbnail->name[$size];
    }
}

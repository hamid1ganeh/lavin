<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyseImage extends Model
{
    protected $fillable=['title','analyse_id','description','required'];

    public function Analyse()
    {
        return $this->belongsTo(Analyse::class);
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
}

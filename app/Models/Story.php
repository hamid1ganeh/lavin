<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
     protected $table = 'stories';
     protected $fillable = ['highlight_id','title','link','status'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function highlight()
    {
        return $this->belongsTo(Highlight::class);
    }

    public function getImage($size)
    {
        return url('/').'/'.$this->image->path.$this->image->name[$size];
    }

    function scopeFilter($query)
    {
        //فیلتر هایلایتها
        $highlights = request('highlights');
        if(isset($highlights) &&  $highlights!='')
        {
            if(in_array('0',$highlights) && count($highlights)>1){
                unset($highlights[0]);
                $query->whereNull('highlight_id')->orwhereIn('highlight_id',$highlights);
            }else if(in_array('0',$highlights) && count($highlights)==1){
                $query->whereNull('highlight_id');
            }else{
                $query->whereIn('highlight_id',$highlights);
            }
        }

        //فیلتر وضعیت
        $status = request('status');
        if(isset($status) &&  $status!='')
        {
            $query->whereIn('status',$status);
        }

        return $query;
    }

}

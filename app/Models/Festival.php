<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class Festival extends Model
{
    protected $fillable = ['title','description','start','end','status','display','festival_id'];

    public function end()
    {
        if (is_null($this->end)){
            return  null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($this->end)));
    }

    public function thumbnail()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function get_thumbnail($size)
    {
        if (is_null($this->thumbnail)){
            return null;
        }
        return url('/').'/'.$this->thumbnail->path.$this->thumbnail->name[$size];
    }

}

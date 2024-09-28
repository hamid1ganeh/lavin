<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class AdminRetraining extends Model
{
    protected $fillable=['admin_id','name','duration','institution','reference','date'];

    public function admin()
    {
        return $this->hasOne(Admin::class)->withTrashed();
    }

    public function date()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->date)));
    }


    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function get_image($size)
    {
        if (is_null($this->image)){
            return null;
        }
        return url('/').'/'.$this->image->path.$this->image->name[$size];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complication extends Model
{
    protected $fillable = ['title','status'];


    public function scopeFilter($query)
    {
        //فیلتر عنوان
        $title = request('title');
        if(isset($title) &&  $title!='')
        {
            $query->where('title','like', '%'.$title.'%');
        }

        return $query;
    }
}

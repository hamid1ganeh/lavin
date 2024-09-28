<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentMainCategory extends Model
{
    use SoftDeletes;
    protected $fillable= ['main_id','title','status'];

    public function subs()
    {
        return $this->hasMany(EmploymentMainCategory::class,'main_id','id');
    }
}

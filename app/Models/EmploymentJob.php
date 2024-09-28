<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentJob extends Model
{
    use softDeletes;

    protected $fillable=['main_cat_id','sub_cat_id','title','status'];

    public function main_category()
    {
        return $this->belongsTo(EmploymentMainCategory::class,'main_cat_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo(EmploymentSubCategory::class,'sub_cat_id','id');
    }

}

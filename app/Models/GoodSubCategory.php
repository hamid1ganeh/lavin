<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodSubCategory extends Model
{
    use SoftDeletes;
    protected $fillable= ['main_id','title','status'];

    public function main()
    {
        return $this->belongsTo(GoodsMainCategory::class,'main_id','id');
    }
}

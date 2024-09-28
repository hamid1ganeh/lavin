<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\CalendarUtils;

class Goods extends Model
{
    use SoftDeletes;
    protected $table= 'goods';
    protected $fillable=['title','main_cat_id','sub_cat_id','code','unit','expire_date','stock','price','description'];

    public function main_category()
    {
        return $this->belongsTo(GoodsMainCategory::class,'main_cat_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo(GoodsSubCategory::class,'sub_cat_id','id');
    }

    public function expireDate()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->expire_date)));
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Goods extends Model
{
    use SoftDeletes;
    protected $table= 'goods';
    protected $fillable=['title',
                        'main_cat_id',
                        'sub_cat_id',
                        'factor_number',
                        'code',
                        'unit',
                        'consumption_unit',
                        'brand_id',
                        'count',
                        'value_per_count',
                        'count_stock',
                        'unit_stock',
                        'price',
                        'description',
                        'expire_date',
                        'status'];

    public function main_category()
    {
        return $this->belongsTo(GoodsMainCategory::class,'main_cat_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo(GoodsSubCategory::class,'sub_cat_id','id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function warehouseStock()
    {
        return $this->hasMany(WarehouseStock::class, 'goods_id', 'id');
    }
    public function expireDate()
    {
        if(is_null($this->expire_date)){
            return '';
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->expire_date)));
    }

    public function stockAsUnit()
    {
        return ($this->count_stock*$this->value_per_count)+$this->unit_stock;
    }

    public function getGoodInfo()
    {
        return $this->title.' - برند ('.$this->brand.') - کد ('.$this->code.')';
    }
    public function scopeFilter($query)
    {
        //فیلتر نام کالا
        $name = request('name');
        if(isset($name) &&  $name!='')
        {
            $query->where('title',$name);
        }
        //فیلتر برند کالا
        $brands = request('brands');
        if(isset($brands) &&  $brands!='')
        {
            $query->whereIn('brand_id',$brands);
        }

        //فیلتر نام کالا
        $code = request('code');
        if(isset($code) &&  $code!='')
        {
            $query->where('code',$code);
        }
        //فیلتر شماره فاکتور کالا
        $factorNumber = request('factor_number');
        if(isset($factorNumber) &&  $factorNumber!='')
        {
            $query->where('factor_number',$factorNumber);
        }
        return  $query;
    }

}

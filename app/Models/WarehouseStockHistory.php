<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class WarehouseStockHistory extends Model
{
    protected $fillable=['warehouse_id','goods_id','stock','event','unit','value','count','delivered_by'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(Admin::class,'delivered_by','id');
    }

    public function delivered_at()
    {
        if (is_null($this->delivered_at)){
            return null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($this->delivered_at)));
    }


    public function event()
    {
        if($this->event == '+'){
            return 'افزودن';
        }

        if($this->event == '-'){
            return 'مرجوعی';
        }
    }

    public function stockAsUnit()
    {
        return ($this->count*$this->good->value_per_count)+$this->value;
    }

}

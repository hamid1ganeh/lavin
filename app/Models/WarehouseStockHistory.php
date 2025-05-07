<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class WarehouseStockHistory extends Model
{
    protected $fillable=['warehouse_id','goods_id','event','unit','stock','delivered_by','moved_warehouse_id','number','created_by'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function movedWarehose()
    {
        return $this->belongsTo(Warehouse::class,'moved_warehouse_id','id');
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(Admin::class,'delivered_by','id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class,'created_by','id');
    }


    public function delivered_at()
    {
        if (is_null($this->delivered_at)){
            return null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($this->delivered_at)));
    }


    public function created_at()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($this->crated_at)));
    }

    public function event()
    {
        if($this->event == '+'){
            return 'دریافتی';
        }

        if($this->event == '-'){
            return 'مرجوعی';
        }

        if($this->event == '0'){
            return 'ارسالی';
        }
    }
    public function countStock()
    {
        return (int)($this->stock/$this->good->value_per_count);
    }

    public function remainderStock()
    {
        return fmod($this->stock,$this->good->value_per_count);
    }
    public function stock()
    {
         $count = $this->countStock();
         $remainder = $this->remainderStock();
         $result='';

         if($count>0){
             $result .=$count.' عدد ';

             if($remainder>0){
                 $result .= ' و ';
             }
         }

        if($remainder>0){
            $result .= $remainder.' '.$this->good->unit;
        }

         return   $result;
    }

}

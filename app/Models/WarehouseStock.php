<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $fillable=['warehouse_id','goods_id','stock'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
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

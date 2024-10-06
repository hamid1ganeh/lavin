<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $fillable=['warehouse_id','goods_id','count','value','unit'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
    }

    public function stockAsUnit()
    {
        return ($this->count*$this->good->value_per_count)+$this->value;
    }
}

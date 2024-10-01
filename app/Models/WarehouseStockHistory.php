<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStockHistory extends Model
{
    protected $fillable=['warehouse_id','goods_id','stock','event','unit','value','stock'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function good()
    {
        return $this->belongsTo(Goods::class);
    }
}

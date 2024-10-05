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
        return $this->belongsTo(Goods::class);
    }
}

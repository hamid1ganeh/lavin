<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveConsumption extends Model
{
   protected $fillable = ['reserve_id','warehouse_id','goods_id','unit','value','price_per_unit','total_price',];

   public function reserve()
   {
     return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
   }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'goods_id','id');
    }
}

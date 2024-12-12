<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveInvoice extends Model
{
    protected $fillable=['reserve_id','price','discount_id','discount_price','discount_description','final_price','settlement'];

    public function reserve()
    {
        return $this->belongsTo(ServiceReserve::class,'reserve_id','id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class,'discount_id','id');
    }

}

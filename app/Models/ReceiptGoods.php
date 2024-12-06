<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptGoods extends Model
{
    protected $fillable = ['receipt_id','good_id','count','unit_cost','total_cost'];

    public function receipt()
    {
        return $this->belongsTo(ReceiptGoods::class,'receipt_id','id');
    }

    public function good()
    {
        return $this->belongsTo(Goods::class,'good_id','id');
    }
}

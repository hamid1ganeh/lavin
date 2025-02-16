<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptInvoice extends Model
{
    protected $fillable = ['receipt_id','number','price','discount_price','description','final_price','settlement'];

    public function receipt()
    {
        return $this->belongsTo(WarehouseReceipt::class,'receipt_id','id');
    }
}

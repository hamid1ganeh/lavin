<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class ReceiptInvoice extends Model
{
    protected $fillable = ['receipt_id','number','price','discount_price','description','final_price','settlement'];

    public function receipt()
    {
        return $this->belongsTo(WarehouseReceipt::class,'receipt_id','id');
    }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->created_at)));
    }

}

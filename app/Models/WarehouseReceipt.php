<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class WarehouseReceipt extends Model
{
    protected $fillable=['type','number','seller','seller_id','exporter_id','price','discount','total_cost'];
    public function user()
    {
        return $this->belongsTo(User::class,'seller_id','id');
    }
    public function exporter()
    {
        return $this->belongsTo(Admin::class,'exporter_id','id');
    }

    public function createdAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->crated_at)));
    }

    public function goods()
    {
        return $this->hasMany(ReceiptGoods::class,'receipt_id','id');
    }

}
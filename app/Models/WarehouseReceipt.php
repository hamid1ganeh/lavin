<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class WarehouseReceipt extends Model
{
    protected $fillable=['type','number','seller','seller_id','exporter_id','price','discount','total_cost','description'];
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

    public function invoic()
    {
        return $this->hasOne(ReceiptInvoice::class,'receipt_id','id');
    }

    public function scopeFilter($query)
    {
        $number = request('number');
        if(isset($number) && $number != '')
        {
            $query->where('number','like','%'.$number.'%');
        }

        $seller = request('seller');
        if(isset($seller) && $seller != '')
        {
            $query->where('seller','like','%'.$seller.'%');
        }

        $type = request('type');
        if(isset($type) && $type != '')
        {
            $query->whereIn('type',$type);
        }

        $seller_id = request('seller_id');
        if(isset($seller_id) && $seller_id != '')
        {
            $query->whereIn('seller_id',$seller_id);
        }
    }

}

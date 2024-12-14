<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class CashPayment extends Model
{
    protected $fillable=['payable_type','payable_id','price','paid_at','description','cashier_id','type'];

    public function payable()
    {
        return $this->morphTo();
    }
    public function cashier()
    {
        return $this->belongsTo(Admin::class,'cashier_id','id');

    }
    public function paidAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d H:i',strtotime($this->paid_at)));
    }
}

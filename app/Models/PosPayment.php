<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class PosPayment extends Model
{
     protected $fillable=['payable_id','payable_type','account_id','transaction_number','price','paid_at','description'];

    public function payable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function paidAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d H:i',strtotime($this->paid_at)));
    }

}

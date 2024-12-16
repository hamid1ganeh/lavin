<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class PosPayment extends Model
{
     protected $fillable=['payable_id','payable_type','receiver_account_id','sender_account_id','transaction_number','price','paid_at','description','cashier_id'];

    public function payable()
    {
        return $this->morphTo();
    }

    public function receiverAccount()
    {
        return $this->belongsTo(Account::class,'receiver_account_id','id');
    }

    public function senderAccount()
    {
        return $this->belongsTo(Account::class,'sender_account_id','id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class CardToCardPayment extends Model
{
     protected $fillable=['payable_type','payable_id','sender_account_id','sender_full_name','sender_cart_number','receiver_account_id',
                           'receiver_full_name','receiver_cart_number','transaction_number','cashier_id','price','paid_at','description','type'];

    public function payable()
    {
        return $this->morphTo();
    }
    public function senderAccount()
    {
        return $this->belongsTo(Account::class,'sender_account_id','id');
    }
    public function reciverAccount()
    {
        return $this->belongsTo(Account::class,'receiver_account_id','id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class ChequePayment extends Model
{
     protected $fillable=['payable_type','payable_id','passed_by_account_id','serial_number','sender_full_name','sender_nation_code',
                            'sender_account_number','price','date_of_issue','due_date','cashier_id','status','passed_date','returned_date','description'];

    public function payable()
    {
        return $this->morphTo();
    }
    public function passedByAccount()
    {
        return $this->belongsTo(Account::class,'passed_by_account_id','id');
    }
    public function cashier()
    {
        return $this->belongsTo(Admin::class,'cashier_id','id');

    }
    public function dateOfIssue()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->date_of_issue)));
    }
    public function dueDate()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->due_date)));
    }

    public function passedDate()
    {
        if (is_null($this->passed_date)){
            return null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->passed_date)));
    }

    public function returnedDate()
    {
        if (is_null($this->returned_date)){
            return null;
        }
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->returned_date)));
    }

    public function passedByAccountInfo()
    {
        if (is_null($this->passed_by_account_id)){
            return null;
        }

      return  $this->passedByAccount->bank_name.' ('.$this->passedByAccount->full_name.')';
    }

}

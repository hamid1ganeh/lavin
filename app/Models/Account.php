<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\CalendarUtils;

class Account extends Model
{
    use SoftDeletes;
    protected $fillable=['full_name','bank_name','account_type','opened_at','account_number','cart_number','shaba_number','status'];

    public function openedAt()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->opened_at)));
    }

}

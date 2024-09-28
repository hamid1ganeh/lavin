<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class Employment extends Model
{
    protected $fillable=['fullname','mobile','job_id','role_id','about','result','resume','status','startEducation','endEducation','status'];

    public function job()
    {
        return $this->belongsTo(EmploymentJob::class,'job_id','id');
    }

    public function refer()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function startEducation()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->startEducation)));
    }

    public function endEducation()
    {
        return CalendarUtils::convertNumbers(CalendarUtils::strftime('Y/m/d',strtotime($this->endEducation)));
    }


}

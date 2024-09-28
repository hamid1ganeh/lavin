<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDocument extends Model
{
    protected $fillable = ['admin_id','personal_stats','personal_desc','job_status','job_desc','education_status',
                            'education_desc','bank_status','bank_desc','retraining_status','retraining_desc'];


    public function admin()
    {
        return $this->hasOne(Admin::class)->withTrashed();
    }
}

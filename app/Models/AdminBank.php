<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminBank extends Model
{
    protected $fillable=['admin_id','name','number','shaba'];

    public function admin()
    {
        return $this->hasOne(Admin::class)->withTrashed();
    }
}

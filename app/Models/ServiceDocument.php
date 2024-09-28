<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDocument extends Model
{
    protected $fillable = ['service_id','title','status'];

    public function service()
    {
        return $this->belongsTo(ServiceDetail::class);
    }
}

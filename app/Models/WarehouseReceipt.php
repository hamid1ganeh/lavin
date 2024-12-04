<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseReceipt extends Model
{
    protected $fillable=['type','number','seller','seller_id','receipt_date','exporter_id'];
    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id','id');
    }
    public function exporter()
    {
        return $this->belongsTo(Admin::class,'exporter_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Warehouse extends Model
{
    use SoftDeletes;

     protected $fillable = ['name','description','status'];

     public function admins()
     {
         return $this->belongsToMany(Admin::class);
     }

     public function stocks()
     {
         return $this->hasMany(WarehouseStock::class,'warehouse_id','id');
     }

    public function adminsArrayId()
    {
        return $this->admins->pluck('id')->toArray();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class AdminAddress extends Model
{
     protected $fillable=['admin_id','provance_id','city_id','latitude','longitude','postalCode','address'];

     public function admin()
     {
         return $this->hasOne(Admin::class)->withTrashed();
     }

     public function province()
     {
         return $this->hasOne(Province::class,'id','provance_id');
     }

     public function city()
     {
         return $this->hasOne(City::class,'id','city_id');
     }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
   protected $fillable = ['user_id','title','text','answer','seen'];

   public function user()
   {
       return $this->hasOne(User::class)->withTrashed();
   }
}

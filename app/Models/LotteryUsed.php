<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryUsed extends Model
{
    protected $fillable = ['user_id','discount_id'];

    public function user()
    {
       return $this->belongsTo(User::class)->withTrashed();
    }

    public function discount()
    {
       return $this->belongsTo(Discount::class);
    }
}

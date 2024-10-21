<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ServiceLaser extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','skin','weight','shot'];
}

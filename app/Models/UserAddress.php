<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table= 'user_addresses';
    protected $fillable = ['user_id','province_id','city_id','part_id','area_id','address','postalcode'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function part()
    {
        return $this->belongsTo(CityPart::class,'part_id','id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class,'area_id','id');
    }
}

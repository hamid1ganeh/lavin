<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Area extends Model
{
    use SoftDeletes,Sluggable;
    public $timestamps = false;

    protected $fillable = ['part_id','name','slug','status'];

    public function part()
    {
        return $this->belongsTo(CityPart::class);
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

}

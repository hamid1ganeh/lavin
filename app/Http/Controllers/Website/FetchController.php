<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Enums\Status;
use App\Models\CityPart;

class FetchController extends Controller
{

    public function fetchcities()
    {
      $province_id =  request('province_id');

      $cityes = City::where('province_id',$province_id)->where('status',Status::Active)
      ->orderBy('name','asc')->get();

      return response()->json(['cityes'=>$cityes],200);
    }


    public function fetchparts()
    {
      $city_id =  request('city_id');

      $parts = CityPart::where('city_id',$city_id)->where('status',Status::Active)
      ->orderBy('name','asc')->get();

      return response()->json(['parts'=>$parts],200);
    }

    public function fetchareas()
    {
        $part_id =  request('part_id');

        $areas = Area::where('part_id',$part_id)->where('status',Status::Active)
            ->orderBy('name','asc')->get();

        return response()->json(['areas'=>$areas],200);
    }


}

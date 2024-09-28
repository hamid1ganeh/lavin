<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\ServiceCollection;
use App\Http\Resources\Website\Resources\ServiceDetailResource;
use App\models\Service;
use App\models\ServiceDetail;

class ServiceController extends Controller
{
     public function services()
     {
         $serviceGroups = Service::where('status',Status::Active)->whereHas('activeServices')->get();

         return response()->json(['serviceGroups'=> new ServiceCollection($serviceGroups)],200);
     }

     public  function service($slug)
     {
          $service = ServiceDetail::where('status',Status::Active)
                                    ->where('slug',$slug)
                                    ->first();

          if(is_null($service)){
              return response()->json(['service'=> 'Not found'],404);
          }

         return response()->json(['service'=> new ServiceDetailResource($service)],200);
     }
}

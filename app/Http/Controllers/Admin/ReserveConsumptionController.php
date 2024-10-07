<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceReserve;
use App\Models\ReserveConsumption;
use Illuminate\Http\Request;

class ReserveConsumptionController extends Controller
{
   public function index(ServiceReserve $reserve)
   {
      $consumptions = ReserveConsumption::where('reserve_id',$reserve->id)->orderBy('created_at','desc')->get();
      return  $consumptions;
   }

}

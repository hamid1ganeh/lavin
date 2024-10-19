<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use App\Models\ReserveConsumptionLaser;

class ReserveConsumptionLaserController extends Controller
{

    public function index(ServiceReserve  $reserve)
    {
        $consumptions = ReserveConsumptionLaser::where('reserve_id',$reserve->id)->orderBy('created_at','desc')->get();
        return  view('admin.reserves.consumptions.consumptions',compact('consumptions','reserve'));

    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\LaserDevice;
use App\Models\ReserveConsumption;
use App\Models\ReserveConsumptionLaser;
use App\Models\ServiceLaser;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function consumptions()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.reports');

        $consumptions = ReserveConsumption::with('reserve.user')
                                            ->orderBy('created_at')
                                            ->filter()
                                            ->paginate(10)
                                            ->withQueryString();

        $goods = Goods::orderBy('title','asc')->get();
        $warehouses = Warehouse::orderBy('name','asc')->get();

        return   view('admin.reports.consumptions',compact('consumptions','goods','warehouses'));
    }

    public function lasers()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.reports');

        $consumptions = ReserveConsumptionLaser::with('reserve.user')
            ->orderBy('created_at')
            ->filter()
            ->paginate(10)
            ->withQueryString();

        $laserServices = ServiceLaser::orderBy('title','asc')->get();

        $lasers = LaserDevice::orderBy('name','asc')->get();

        return   view('admin.reports.lasers',compact('consumptions','lasers','laserServices'));
    }
}

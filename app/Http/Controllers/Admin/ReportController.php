<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\ReserveConsumption;
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
}

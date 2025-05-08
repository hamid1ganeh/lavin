<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\WareHoseOrderResult;


class GoodsOrderController extends Controller
{

    public function index()
    {
        $orders = WarehouseStockHistory::with('good')
            ->whereIn('event', ['+', '-'])
            ->filter()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $goods = Goods::where('status', Status::Active)->orderBy('title', 'asc')->get();

        $warehouses = Warehouse::where('status', Status::Active)
            ->orderBy('name', 'asc')
            ->get();


        return view('admin.warehousing.order', compact('goods', 'orders', 'warehouses'));

    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'good' => ['required', 'exists:goods,id'],
                'warehouse' => ['required', 'exists:warehouses,id'],
                'count' => ['nullable', 'integer'],
                'value' => ['nullable', 'integer']
            ],
            [
                'good.required' => ' انتخاب کالا الزامی است.',
                'good.warehouse' => ' انتخاب انبار الزامی است.',
            ]);

        if (is_null($request->count) && is_null($request->value)) {
            alert()->error('خطا', 'انتخاب مقدار واحد یا تعداد الزامی است.');
            return back()->withInput();
        }

        if (is_null($request->count)) {
            $count = 0;
        } else {
            $count = $request->count;
        }

        if (is_null($request->value)) {
            $value = 0;
        } else {
            $value = $request->value;
        }

        $good = Goods::where('id', $request->good)->where('status', Status::Active)->first();
        if (is_null($good)) {
            return back();
        }

        $ask = ($count * $good->value_per_count) + $value;
        if ($request->event == '+' && $ask > $good->stockAsUnit()) {
            alert()->error('خطا', 'مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
            return back()->withInput();
        }

        $lastWarehouseStockHistory = WarehouseStockHistory::orderBy('number', 'desc')->first();
        if (is_null($lastWarehouseStockHistory)) {
            $number = '1000';
        } else {
            $number = $lastWarehouseStockHistory->number + 1;
        }

        $order = new WarehouseStockHistory();
        $order->warehouse_id = $request->warehouse;
        $order->number = $number;
        $order->goods_id = $request->good;
        $order->event = '+';
        $order->stock = ($order->good->value_per_count * $count) + $value;
        $order->created_by = Auth::guard('admin')->id();
        $order->confirmed_by = Auth::guard('admin')->id();
        $order->save();
        toast('حواله شما ثبت شد.', 'success')->position('bottom-end');

        return back();
    }

    public function deliver(WarehouseStockHistory $order,Request $request)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('warehousing.warehouses.orders.delivery');

        if (is_null($order->delivered_by)){
            $good = $order->good;
            $order->delivered_by = Auth::guard('admin')->id();
            $order->delivered_at = Carbon::now('+3:30')->format('Y-m-d H:i:s');
            $order->confirmed_by = Auth::guard('admin')->id();
            $stock = WarehouseStock::where('warehouse_id',$order->warehouse_id )->where('goods_id',$order->goods_id)->first();

            if ($order->event == '-'){

                $less = $request->less_count*$good->value_per_count+$request->less_unit;

                if ($less>=$order->stock){
                    alert()->error('خطا','تعداد کاستی باید از تعداد موجودی کمتر باشد');
                    return back();
                }

                $stockCount = $order->stock-$less;

                if(is_null($stock) || $stockCount > $stock->stock){
                    alert()->error('خطا','مقدار درخواستی در انبار مبدا موجود نمی باشد.');
                    return back();
                }

                $order->less = $less;
                $stock->stock -= $stockCount;
                $good->count_stock += $order->countStock();
                $good->unit_stock += $order->remainderStock();
                $stock->warehouse_id = $order->warehouse_id;
                $stock->goods_id = $order->goods_id;

                DB::transaction(function() use ($stock, $order,$good) {
                    $stock->save();
                    $order->save();
                    $good->save();
                });
               }

            toast('حواله مورد نظر تحویل گرفته شد.','success')->position('bottom-end');
        }
        return back();
    }


    public function less(WarehouseStockHistory $order,Request $request)
    {
        if ($order->less ==0 || !in_array($request->result,WareHoseOrderResult::validKeys())){
            return back();
        }

        $order->less_result = $request->result;
        $order->save();

        toast('وضعیت مغایرت مشخص شد.','success')->position('bottom-end');
        return  back();

    }
}

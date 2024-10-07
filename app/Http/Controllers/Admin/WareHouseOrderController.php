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
use Illuminate\Support\Facades\DB;
use Auth;

class WareHouseOrderController extends Controller
{
    public function index(Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.index');

        $orders = WarehouseStockHistory::with('good.main_category','good.sub_category')
            ->where('warehouse_id',$warehouse->id)
            ->orderBy('created_at','desc')
            ->paginate(10)
            ->withQueryString();


        $goods = Goods::where('status',Status::Active)->orderBy('title','asc')->get();


        return view('admin.warehousing.warehouses.order',compact('goods','warehouse','orders'));
    }


    public function store(Warehouse $warehouse,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.create');

        $request->validate(
            [
                'good' => ['required','exists:goods,id'],
                'count' => ['nullable','integer'],
                'value' => ['nullable','integer'],
            ],
            [
                'good.required' => ' انتخاب کالا الزامی است.',
            ]);

        if (is_null($request->count) && is_null($request->value)){
            alert()->error('خطا','انتخاب مقدار واحد یا تعداد الزامی است.');
            return back()->withInput();
        }

        if (is_null( $request->count)){
            $count = 0;
        }else{
            $count = $request->count;
        }

        if (is_null( $request->value)){
            $value = 0;
        }else{
            $value = $request->value;
        }

        $good = Goods::where('id',$request->good)->where('status',Status::Active)->first();
        if(is_null($good)){
            return back();
        }


        $ask = ($count*$good->value_per_count)+$value;
        if ($request->event == '+' && $ask>$good->stockAsUnit()){
            alert()->error('خطا','مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
            return back()->withInput();
        }

        if ($request->event == '-'){
            $stock = WarehouseStock::where('warehouse_id',$warehouse->id)->where('goods_id',$request->good)->first();
            if(is_null($stock) || $ask>$stock->stockAsUnit()){
                alert()->error('خطا','مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                return back()->withInput();
            }
        }

        if (in_array($request->event,['+','-'])){
            $order= new WarehouseStockHistory();
            $order->warehouse_id = $warehouse->id;
            $order->goods_id = $request->good;
            $order->event = $request->event;
            $order->unit = $good->unit;
            $order->value = $value;
            $order->count = $count;
            $order->save();
            toast('حواله شما ثبت شد.','success')->position('bottom-end');
        }

        return back();
    }

    public function update(Warehouse $warehouse,WarehouseStockHistory $order,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.edit');


        if (is_null($order->delivered_by )) {
            $request->validate(
                [
                    'good' => ['required', 'exists:goods,id'],
                    'count' => ['nullable', 'integer'],
                    'value' => ['nullable', 'integer'],
                ],
                [
                    'good.required' => ' انتخاب کالا الزامی است.',
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

            if ($request->event == '-') {
                $stock = WarehouseStock::where('warehouse_id', $warehouse->id)->where('goods_id', $request->good)->first();
                if (is_null($stock) || $ask > $stock->stockAsUnit()) {
                    alert()->error('خطا', 'مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                    return back()->withInput();
                }
            }


            if (in_array($request->event, ['+', '-'])) {

                $order->goods_id = $request->good;
                $order->event = $request->event;
                $order->unit = $good->unit;
                $order->value = $value;
                $order->count = $count;
                $order->save();

                toast('بروزرسانی انجام شد.', 'success')->position('bottom-end');
            }
        }
        return back();
    }
    public function deliver(Warehouse $warehouse,WarehouseStockHistory $order)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.delivery');

        if (is_null($order->delivered_by )){
            $good = $order->good;
            $order->delivered_by = Auth::guard('admin')->id();
            $order->delivered_at = Carbon::now('+3:30')->format('Y-m-d H:i:s');
            $stock = WarehouseStock::where('warehouse_id',$order->warehouse_id )->where('goods_id',$order->goods_id)->firstOrNew();

            if ($order->event == '+'){
                if($order->stockAsUnit() > $order->good->stockAsUnit()){
                    alert()->error('خطا','مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
                    return back();
                }
                if (is_null($stock)){
                    $stock->count = $order->count;
                    $stock->value = $order->value;
                }else{
                    $stock->count += $order->count;


                    $stock->value += $order->value;
                }

                $good->count_stock -= $order->count;
                $good->unit_stock -= $order->value;
            }


            if ($order->event == '-'){
                if($order->stockAsUnit() > $stock->stockAsUnit()){
                    alert()->error('خطا','مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                    return back();
                }

                if (is_null($stock)){
                    $stock->count = 0;
                    $stock->value = 0;

                }else{
                    if ($order->count > $stock->count){
                        $stock->count = 0;
                    }else{
                        $stock->count -= $order->count;
                    }

                    if ($order->value > $stock->value){
                        $stock->value = 0;
                    }else{
                        $stock->value -= $order->value;
                    }
                }
                $good->count_stock += $order->count;
                $good->unit_stock += $order->value;
            }

            $stock->warehouse_id = $order->warehouse_id;
            $stock->goods_id = $order->goods_id;
            $stock->unit = $order->unit;

            DB::transaction(function() use ($stock, $order,$good) {
                $stock->save();
                $order->save();
                $good->save();
            });

            toast('حواله مورد نظر تحویل گرفته شد.','success')->position('bottom-end');
        }
        return back();
    }

    public function destroy(Warehouse $warehouse,WarehouseStockHistory $order)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.destroy');

        if (is_null($order->delivered_by )) {
            $order->delete();
            toast('حواله مورد نظر حذف شد.', 'error')->position('bottom-end');
        }
        return back();
    }

}

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
            ->orWhere('moved_warehouse_id',$warehouse->id)
            ->orderBy('created_at','desc')
            ->paginate(10)
            ->withQueryString();

        $goods = Goods::where('status',Status::Active)->orderBy('title','asc')->get();

        $warehousesGoods = Goods::whereHas('warehouseStock',function ($query) use ($warehouse){
            $query->where('warehouse_id',$warehouse->id);
        })->orderBy('title','asc')->get();


        $warehouses = Warehouse::where('status',Status::Active)
            ->where('id','<>',$warehouse->id)
            ->orderBy('name','asc')
            ->get();


        return view('admin.warehousing.warehouses.order',compact('goods','warehouse','orders','warehouses','warehousesGoods'));
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

        if($request->event == '0' && is_null($request->warehouse)){
            alert()->error('خطا','لطفا انبار مورد نظر را انتخاب کنید.');
            return back()->withInput();
        }

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
            if(is_null($stock) || $ask>$stock->stock){
                alert()->error('خطا','مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                return back()->withInput();
            }
        }

        $lastWarehouseStockHistory = WarehouseStockHistory::orderBy('number','desc')->first();
        if (is_null($lastWarehouseStockHistory)){
            $number = '1000';
        }else{
            $number = $lastWarehouseStockHistory->number + 1;
        }

        if (in_array($request->event,['+','-','0'])){
            $order= new WarehouseStockHistory();
            $order->warehouse_id = $warehouse->id;
            if($request->event == '0'){
                $order->moved_warehouse_id = $request->warehouse;
            }
            $order->number = $number;
            $order->goods_id = $request->good;
            $order->event = $request->event;
            $order->stock = ($order->good->value_per_count*$count)+$value;
            $order->created_by = Auth::guard('admin')->id();
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

        if (!in_array(Auth::guard('admin')->id(),$warehouse->adminsArrayId())){
            abort(403);
        }

        if (is_null($order->delivered_by )) {

            $request->validate(
                [
                    'count' => ['nullable', 'integer'],
                    'value' => ['nullable', 'integer'],
                    'good' => ['required', 'exists:goods,id']
                ],
                [
                    'good.required' => ' انتخاب کالا الزامی است.',
                ]);


            if($request->event == '0' && is_null($request->warehouse)){
                alert()->error('خطا','لطفا انبار مورد نظر را انتخاب کنید.');
                return back()->withInput();
            }


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
            if ($order->event == '+' && $ask > $good->stockAsUnit()) {
                alert()->error('خطا', 'مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
                return back()->withInput();
            }

            if ($order->event == '-' || $order->event == '0') {
                $stock = WarehouseStock::where('warehouse_id', $warehouse->id)->where('goods_id', $request->good)->first();

                if (is_null($stock) || $ask > $stock->stock) {
                    alert()->error('خطا', 'مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                    return back()->withInput();
                }
            }

                if ($order->event == '0'){
                    $order->moved_warehouse_id = $request->warehouse;
                }
                $order->goods_id = $request->good;
                $order->stock =($order->good->value_per_count*$count)+$value;
                $order->save();
                toast('بروزرسانی انجام شد.', 'success')->position('bottom-end');

        }
        return back();
    }
    public function deliver(Warehouse $warehouse,WarehouseStockHistory $order,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.delivery');

        if (is_null($order->delivered_by)){
            $good = $order->good;
            $order->delivered_by = Auth::guard('admin')->id();
            $order->delivered_at = Carbon::now('+3:30')->format('Y-m-d H:i:s');
            $stock = WarehouseStock::where('warehouse_id',$order->warehouse_id )->where('goods_id',$order->goods_id)->first();

            if ($order->event == '+'){

                if (is_null($order->confirmed_by)){
                    alert()->error('خطا','انبار مرکزی تایید نکرده است.');
                    return back();
                }


                $less = $request->less_count*$good->value_per_count+$request->less_unit;

                if ($less>=$order->stock){
                    alert()->error('خطا','تعداد کاستی باید از تعداد موجودی کمتر باشد');
                    return back();
                }


                $stockCount = $order->stock-$less;

                if($stockCount > $order->good->stockAsUnit()){
                    alert()->error('خطا','مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
                    return back();
                }
                if (is_null($stock)){
                    $stock = new WarehouseStock();
                    $stock->stock = $stockCount;
                }else{
                    $stock->stock += $stockCount;
                }

                $order->less = $less;

                $good->count_stock -= $order->countStock();
                $good->unit_stock -= $order->remainderStock();


                if ($good->count_stock < 0 || $good->unit_stock < 0){
                    alert()->error('خطا','مقدار درخواستی شما در انبار مرکزی موجود نمی باشد.');
                    return back();
                }


                $stock->warehouse_id = $order->warehouse_id;
                $stock->goods_id = $order->goods_id;

                DB::transaction(function() use ($stock, $order,$good) {
                    $stock->save();
                    $order->save();
                    $good->save();
                });

            }

            if ($order->event == '-' || $order->event == '0'){

                if(is_null($stock) || $order->stock > $stock->stock){
                    alert()->error('خطا','مقدار درخواستی شما در انبار شما موجود نمی باشد.');
                    return back();
                }

                $stock->stock -= $order->stock;

                if ($order->event == '-'){
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

                if ($order->event == '0'){

                    $stockMoved = WarehouseStock::where('warehouse_id',$order->moved_warehouse_id)->where('goods_id',$order->goods_id)->first();

                    if(is_null($stockMoved)){
                        $stockMoved = new WarehouseStock();
                        $stockMoved->stock = $order->stock;
                    }else{
                        $stockMoved->stock += $order->stock;
                    }

                    $stockMoved->warehouse_id = $order->moved_warehouse_id;
                    $stockMoved->goods_id = $order->goods_id;

                    $stock->warehouse_id = $order->warehouse_id;
                    $stock->goods_id = $order->goods_id;

                    DB::transaction(function() use ($stock, $order,$stockMoved) {
                        $stock->save();
                        $order->save();
                        $stockMoved->save();
                    });
                }
            }
            toast('حواله مورد نظر تحویل گرفته شد.','success')->position('bottom-end');
        }
        return back();
    }

    public function destroy(Warehouse $warehouse,WarehouseStockHistory $order)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.orders.destroy');

        if (!in_array(Auth::guard('admin')->id(),$warehouse->adminsArrayId())){
            abort(403);
        }

        if (is_null($order->delivered_by )) {
            $order->delete();
            toast('حواله مورد نظر حذف شد.', 'error')->position('bottom-end');
        }
        return back();
    }

}

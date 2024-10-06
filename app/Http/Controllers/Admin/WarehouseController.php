<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Goods;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.index');

        $warehouses = Warehouse::with('admins')->orderBy('name')->withTrashed()->get();
        return view('admin.warehousing.warehouses.all',compact('warehouses'));

    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        $admins = Admin::where('status',Status::Active)->orderBy('fullname','desc')->get();

        return view('admin.warehousing.warehouses.create',compact('admins'));
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        $request->validate(
            [
                'name' => ['required','max:255','unique:warehouses'],
                'description' => ['nullable','max:255'],
                "admins"=>['required'],
            ],
            [
                'name.required' => 'نام انبار الزامی است.',
                'name.unique' => 'نام انبار تکراری است.',
                'name.max' => 'حداکثر  طول مجاز نام انبار 255 کارکتر می باشد.',
                'description.max' => 'حداکثر  طول مجاز توضیحات انبار 255 کارکتر می باشد.',
                'admins.required' => 'انتخاب مسئولین انبار الزامی است.',
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
           $warehouse = Warehouse::create([
                            'name'=>$request->name,
                            'description'=>$request->description,
                            'status'=>$request->status,
                        ]);

           $warehouse->admins()->sync($request->admins);

            toast('انبار جدید افزوده شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.warehouses.index'));
    }


    public function edit(Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.edit');

        $admins = Admin::where('status',Status::Active)->orderBy('fullname','desc')->get();

        return view('admin.warehousing.warehouses.edit',compact('warehouse','admins'));
    }


    public function update(Request $request, Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.edit');

        $request->validate(
            [
                'name' => ['required','max:255','unique:warehouses,name,'.$warehouse->id],
                'description' => ['nullable','max:255']
            ],
            [
                'name.required' => 'نام انبار الزامی است.',
                'name.unique' => 'نام انبار تکراری است.',
                'name.max' => 'حداکثر  طول مجاز نام انبار 255 کارکتر می باشد.',
                'description.max' => 'حداکثر  طول مجاز توضیحات انبار 255 کارکتر می باشد.',
            ]);


        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $warehouse->update(['name'=>$request->name,
                                'description'=>$request->description,
                                 'status'=>$request->status,]);

            $warehouse->admins()->sync($request->admins);

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.warehouses.index'));

    }

    public function destroy( Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.destroy');
        $warehouse->delete();
        toast('انبار مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.recycle');
        $warehouse = Warehouse::withTrashed()->find($id);
        $warehouse->restore();
        toast('انبار مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

    public function stocks(Warehouse $warehouse)
    {
        $stocks = WarehouseStock::where('warehouse_id',$warehouse->id)
                                    ->orderBy('created_at')
                                    ->paginate(10)
                                    ->withQueryString();

        return view('admin.warehousing.warehouses.stock',compact('stocks','warehouse'));
    }

    public function orders(Warehouse $warehouse)
    {

        $orders = WarehouseStockHistory::with('good.main_category','good.sub_category')
                                        ->where('warehouse_id',$warehouse->id)
                                        ->orderBy('created_at','desc')
                                        ->paginate(10)
                                        ->withQueryString();


        $goods = Goods::where('status',Status::Active)->orderBy('title','asc')->get();


        return view('admin.warehousing.warehouses.order',compact('goods','warehouse','orders'));
    }

    public function change(Warehouse $warehouse,Request $request)
    {
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

                $history= new WarehouseStockHistory();
                $history->warehouse_id = $warehouse->id;
                $history->goods_id = $request->good;
                $history->event = $request->event;
                $history->unit = $good->unit;
                $history->value = $value;
                $history->count = $count;
                $history->save();

                toast('حواله شما ثبت شد.','success')->position('bottom-end');
            }

        return back();
    }


    public function deliver(Warehouse $warehouse,WarehouseStockHistory $order)
    {
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
}

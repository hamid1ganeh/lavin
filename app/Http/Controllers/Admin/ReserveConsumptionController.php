<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\ServiceReserve;
use App\Models\ReserveConsumption;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use App\Enums\ReserveStatus;
use Illuminate\Support\Facades\DB;

class ReserveConsumptionController extends Controller
{
   public function index(ServiceReserve $reserve)
   {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('reserves.consumptions.index');

       $consumptions = ReserveConsumption::with('warehouse.stocks.good')->where('reserve_id',$reserve->id)->orderBy('created_at','desc')->get();

       $warehouses = Warehouse::where('status',Status::Active)
                               ->orderBy('name','asc')
                               ->get();
       $goods = [];
       if (!is_null(old('warehouse'))){
           $goods = WarehouseStock::with('good')
               ->where('warehouse_id',old('warehouse'))
               ->get()
               ->pluck('good');
       }

       return  view('admin.reserves.consumptions.consumptions',compact('consumptions','reserve','warehouses','goods'));
   }

   public function store(ServiceReserve $reserve,Request $request)
   {   //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('reserves.consumptions.create');

       if ( $reserve->status ==  ReserveStatus::done){
           return abort(403);
       }

       $request->validate(
           [
               'warehouse' => ['required','exists:warehouses,id'],
               'good' => ['required','exists:goods,id'],
               'value' => ['required','integer'],
           ],
           [
               'warehouse.required' => 'انتخاب انبار الزامی است.',
               'title.max' => 'انتخاب کالا الزامی است.',
               'value.required' => ' واحد مصرفی الزامی است.',
           ]);

       $stock = WarehouseStock::where('warehouse_id',$request->warehouse)->where('goods_id',$request->good)->first();
       if(is_null($stock) || $stock->stock<$request->value){
           alert()->error('خطا','واحد مصرفی کالا در انبار موجود نمی باشد.');
           return back()->withInput();
       }

       if (ReserveConsumption::where('reserve_id',$reserve->id)->where('goods_id',$request->good)->exists()){
           alert()->error('خطا','این کالا مصرفی قبلا برای این رزرو ثبت شده است.');
           return back()->withInput();
       }

       $good = Goods::find($request->good);

       $cunsumption = new ReserveConsumption();
       $cunsumption->reserve_id = $reserve->id;
       $cunsumption->warehouse_id = $request->warehouse;
       $cunsumption->goods_id = $good->id;
       $cunsumption->unit = $good->unit;
       $cunsumption->value = $request->value;
       $cunsumption->price_per_unit = $good->price;
       $cunsumption->total_price = $good->price*$request->value;

       $stock->stock -=$request->value;

       DB::transaction(function() use ($stock,$cunsumption) {
           $stock->save();
           $cunsumption->save();
       });

       toast('موارد مصرفی جدید ثبت شد.','success')->position('bottom-end');
       return back();
   }


    public function update(ServiceReserve $reserve,ReserveConsumption $consumption,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.edit');

        if ( $reserve->status ==  ReserveStatus::done){
            return abort(403);
        }

        $request->validate(
            [
                'warehouse' => ['required','exists:warehouses,id'],
                'good' => ['required','exists:goods,id'],
                'value' => ['required','integer'],
            ],
            [
                'warehouse.required' => 'انتخاب انبار الزامی است.',
                'title.max' => 'انتخاب کالا الزامی است.',
                'value.required' => ' واحد مصرفی الزامی است.',
            ]);

        $oldStock = WarehouseStock::where('warehouse_id',$consumption->warehouse_id)->where('goods_id',$consumption->goods_id)->first();
        $stock = WarehouseStock::where('warehouse_id',$request->warehouse)->where('goods_id',$request->good)->first();
        if(is_null($stock) || ($stock->stock+$consumption->value)<$request->value){
            alert()->error('خطا','واحد مصرفی کالا در انبار موجود نمی باشد.');
            return back()->withInput();
        }

        if (ReserveConsumption::where('reserve_id',$reserve->id)->where('goods_id',$request->good)->where('id','<>',$consumption->id)->exists()){
            alert()->error('خطا','این کالا مصرفی قبلا برای این رزرو ثبت شده است.');
            return back()->withInput();
        }

        $good = Goods::find($request->good);

        $oldStock->stock += $consumption->value;
        $stock->stock -= $request->value;
        $consumption->warehouse_id = $request->warehouse;
        $consumption->goods_id = $good->id;
        $consumption->unit = $good->unit;
        $consumption->value = $request->value;
        $consumption->price_per_unit = $good->price;
        $consumption->total_price =$good->price*$request->value;


        DB::transaction(function() use ($oldStock,$stock, $consumption) {
            $oldStock->save();
            $stock->save();
            $consumption->save();
        });


        toast('موارد مصرفی بروزرسانی  شد.','success')->position('bottom-end');
        return back();
    }

    public function delete(ServiceReserve $reserve,ReserveConsumption $consumption)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.destroy');

        if ( $reserve->status ==  ReserveStatus::done){
            return abort(403);
        }

        $oldStock = WarehouseStock::where('warehouse_id',$consumption->warehouse_id)->where('goods_id',$consumption->goods_id)->first();
        $oldStock->stock += $consumption->value;
        $consumption->delete();


        DB::transaction(function() use ($oldStock, $consumption) {
            $oldStock->save();
            $consumption->delete();
        });
        toast('موارد مصرفی حذف شد.','error')->position('bottom-end');
        return back();
    }

}

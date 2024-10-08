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

       return  view('admin.reserves.consumptions',compact('consumptions','reserve','warehouses','goods'));
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
       if(is_null($stock) || $stock->stockAsUnit()<$request->value){
           alert()->error('خطا','واحد مصرفی کالا در انبار موجود نمی باشد.');
           return back()->withInput();
       }

       if (ReserveConsumption::where('reserve_id',$reserve->id)->where('goods_id',$request->good)->exists()){
           alert()->error('خطا','این کالا مصرفی قبلا برای این رزرو ثبت شده است.');
           return back()->withInput();
       }

       $good = Goods::find($request->good);

       ReserveConsumption::create(['reserve_id'=>$reserve->id,
                                   'warehouse_id'=>$request->warehouse,
                                    'goods_id'=>$good->id,
                                    'unit'=>$good->unit,
                                    'value'=>$request->value,
                                    'price_per_unit'=>$good->price,
                                    'total_price'=>$good->price*$request->value]);


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

        $stock = WarehouseStock::where('warehouse_id',$request->warehouse)->where('goods_id',$request->good)->first();
        if(is_null($stock) || $stock->stockAsUnit()<$request->value){
            alert()->error('خطا','واحد مصرفی کالا در انبار موجود نمی باشد.');
            return back()->withInput();
        }

        if (ReserveConsumption::where('reserve_id',$reserve->id)->where('goods_id',$request->good)->where('id','<>',$consumption->id)->exists()){
            alert()->error('خطا','این کالا مصرفی قبلا برای این رزرو ثبت شده است.');
            return back()->withInput();
        }

        $good = Goods::find($request->good);

        $consumption->update(['warehouse_id'=>$request->warehouse,
                              'goods_id'=>$good->id,
                               'unit'=>$good->unit,
                               'value'=>$request->value,
                               'price_per_unit'=>$good->price,
                               'total_price'=>$good->price*$request->value]);


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

        $consumption->delete();
        toast('موارد مصرفی حذف شد.','error')->position('bottom-end');
        return back();
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Number;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\ReceiptGoods;
use App\Models\WarehouseReceipt;
use App\Enums\ReceiptType;
use Auth;
use Illuminate\Support\Facades\DB;

class WarehouseReceiptController extends Controller
{
    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.receipts.index');

        $goods = Goods::orderBy('title','asc')->get();
        $sellers = User::where('seller',true)->orderBy('firstname','desc')->orderBy('lastname','desc')->get();
        $receipts = WarehouseReceipt::orderby('created_at','desc')->paginate(10);
        return view('admin.warehousing.receipt.all',compact('goods','sellers','receipts'));
    }
    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.receipts.create');

        $goods = Goods::orderBy('title','asc')->get();
        $sellers = User::where('seller',true)->orderBy('firstname','desc')->orderBy('lastname','desc')->get();
         return view('admin.warehousing.receipt.create',compact('goods','sellers'));
    }
    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.receipts.create');

        $request->validate([
            'number' => "required|max:255|unique:warehouse_receipts",
            'seller' => "nullable|max:255",
            'price' => "required|integer",
            'discount' => "required|integer",
        ],
        [
            "number.max"=>"شماره فاکتور طولانی است.",
            "number.required"=>"شماره فاکتور الزامی است.",
            "number.unique"=>"شماره فاکتور قبلا ثبت شده است.",
            "seller.required"=>"متن طرف حساب طولانی است",
        ]);

        if (in_array($request->type,[ReceiptType::received,ReceiptType::returned])){
            if(!isset($request->seller) && !isset($request->seller_id)){
               alert()->error('طرف حساب یا فروشنده را وارد کنید');
               return back()->withInput();
            }
            $goods = $request->goods;
            if(count($goods) != count(array_unique($goods))){
                alert()->error('کالا تکراری در فاکتور شما وجود دارد.');
                return back()->withInput();
            }

            $receipt = new WarehouseReceipt();
            $receipt->type = $request->type;
            $receipt->number = $request->number;
            $receipt->seller = $request->seller;
            $receipt->seller_id  = $request->seller_id ;
            $receipt->exporter_id  =  Auth::guard('admin')->id();
            $receipt->price  = $request->price ;
            $receipt->discount  = $request->discount ;
            $receipt->total_cost  = $request->price-$request->discount ;
            $receipt->save();

            $count = $request->count;
            $unitCost = $request->unit_cost;
            $totalCost = $request->total_cost;
            foreach ($goods as $index=>$good){
                $receiptGoods = new ReceiptGoods();
                $receiptGoods->receipt_id = $receipt->id;
                $receiptGoods->good_id  = $good;
                $receiptGoods->count  = $count[$index];
                $receiptGoods->unit_cost  = $unitCost[$index];
                $receiptGoods->total_cost  = $totalCost[$index];
                $stockGood = Goods::find($good);
                if ($request->type == ReceiptType::received) {
                    $stockGood->count_stock += $receiptGoods->count;
                }elseif($request->type == ReceiptType::returned) {
                    $countStock = $stockGood->count_stock-$receiptGoods->count;
                    $stockGood->count_stock = $countStock<0?0:$countStock;
                }
                $stockGood->unit_stock = $stockGood->count_stock * $stockGood->value_per_count;
                DB::transaction(function() use ($stockGood,$receiptGoods) {
                    $stockGood->save();
                    $receiptGoods->save();
                });
            }
        }
        toast('رسید جدید ثبت شد.','success')->position('bottom-end');
        return redirect(route('admin.warehousing.receipts.index'));
    }
    public function edit(WarehouseReceipt $receipt)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.receipts.edit');

        $goods = Goods::orderBy('title','asc')->get();
        $sellers = User::where('seller',true)->orderBy('firstname','desc')->orderBy('lastname','desc')->get();
        return view('admin.warehousing.receipt.edit',compact('goods','sellers','receipt'));
    }

    public function update(WarehouseReceipt $receipt,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.receipts.edit');

        $request->validate([
            'number' => "required|max:255|unique:warehouse_receipts,number,".$receipt->id,
            'seller' => "nullable|max:255",
            'price' => "required|integer",
            'discount' => "required|integer"
        ],
            [
                "number.max"=>"شماره فاکتور طولانی است.",
                "number.required"=>"شماره فاکتور الزامی است.",
                "number.unique"=>"شماره فاکتور قبلا ثبت شده است.",
                "seller.required"=>"متن طرف حساب طولانی است",
            ]);

            if(!isset($request->seller) && !isset($request->seller_id)){
                alert()->error('طرف حساب یا فروشنده را وارد کنید');
                return back()->withInput();
            }
            $goods = $request->goods;
            if(count($goods) != count(array_unique($goods))){
                alert()->error('کالا تکراری در فاکتور شما وجود دارد.');
                return back()->withInput();
            }

            $receipt->number = $request->number;
            $receipt->seller = $request->seller;
            $receipt->seller_id  = $request->seller_id;
            $receipt->price  = $request->price ;
            $receipt->discount  = $request->discount ;
            $receipt->total_cost  = $request->price-$request->discount ;
            $receipt->save();

            foreach ($receipt->goods as $good){
                $stockGood = Goods::find($good->good_id);
                if ($receipt->type == ReceiptType::received) {
                    $countStock = $stockGood->count_stock-$good->count;
                    $stockGood->count_stock = $countStock<0?0:$countStock;
                }elseif($receipt->type == ReceiptType::returned) {
                    $stockGood->count_stock+=$good->count;
                }
                $stockGood->unit_stock = $stockGood->count_stock * $stockGood->value_per_count;
                DB::transaction(function() use ($stockGood,$receipt,$good) {
                    $stockGood->save();
                    $receipt->save();
                    $good->delete();
                });
            }

            $count = $request->count;
            $unitCost = $request->unit_cost;
            $totalCost = $request->total_cost;
            foreach ($goods as $index=>$good){
                $receiptGoods = new ReceiptGoods();
                $receiptGoods->receipt_id = $receipt->id;
                $receiptGoods->good_id  = $good;
                $receiptGoods->count  = $count[$index];
                $receiptGoods->unit_cost  = $unitCost[$index];
                $receiptGoods->total_cost  = $totalCost[$index];
                $stockGood = Goods::find($good);
                if ($receipt->type == ReceiptType::received) {
                    $stockGood->count_stock += $receiptGoods->count;
                }elseif($receipt->type == ReceiptType::returned) {
                    $countStock = $stockGood->count_stock-$receiptGoods->count;
                    $stockGood->count_stock = $countStock<0?0:$countStock;
                }
                $stockGood->unit_stock = $stockGood->count_stock * $stockGood->value_per_count;
                DB::transaction(function() use ($stockGood,$receiptGoods) {
                    $stockGood->save();
                    $receiptGoods->save();
                });
            }
        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        return redirect(route('admin.warehousing.receipts.index'));
    }
}

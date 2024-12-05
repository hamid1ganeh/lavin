<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Enums\ReceiptType;

class WarehouseReceiptController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        $goods = Goods::orderBy('title','asc')->get();
        $sellers = User::where('seller',true)->orderBy('firstname','desc')->orderBy('lastname','desc')->get();

         return view('admin.warehousing.receipt.create',compact('goods','sellers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'number' => "required|max:255",
            'seller' => "nullable|max:255",
        ],
        [
            "number.max"=>"شماره فاکتور طولانی است.",
            "number.required"=>"شماره فاکتور الزامی است.",
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


            return $goods;

        }

        return $request;
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

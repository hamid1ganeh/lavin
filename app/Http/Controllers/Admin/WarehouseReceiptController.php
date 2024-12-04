<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;

class WarehouseReceiptController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        $goods = Goods::orderBy('title','asc')->get();
         return view('admin.warehousing.receipt.create',compact('goods'));
    }


    public function store(Request $request)
    {
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

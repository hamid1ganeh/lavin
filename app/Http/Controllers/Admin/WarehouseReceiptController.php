<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $sellers = User::where('seller',true)->orderBy('firstname','desc')->orderBy('lastname','desc')->get();

         return view('admin.warehousing.receipt.create',compact('goods','sellers'));
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

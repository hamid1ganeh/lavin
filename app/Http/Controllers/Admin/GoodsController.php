<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;

class GoodsController extends Controller
{

    public function index()
    {
                 $goods = Goods::orderBy('title','asc')
                     ->withTrashed()
                     ->paginate(10)
                     ->withQueryString();

                 return view('admin.warehousing.goods.all',compact('goods'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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

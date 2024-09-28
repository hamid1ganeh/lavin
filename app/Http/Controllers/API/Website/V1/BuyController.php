<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\Website\Collections\BuyCollection;
use Illuminate\Http\Request;
use Auth;

class BuyController extends Controller
{
   public function index()
   {
       $orders = Order::with('items')
           ->where('user_id',Auth('sanctum')->id())
           ->filter()
           ->orderBy('created_at','desc')
           ->get();

       return response()->json(['orders'=>new BuyCollection($orders)],200);
   }
}

<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\DiscountCollection;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::whereHas('users',function($q){
            $q->where('user_id',Auth('sanctum')->id());
        })->get();

        return response()->json(['discounts'=> new DiscountCollection($discounts)],200);

    }
}

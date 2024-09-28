<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\OrdersItemCollection;

class BuyCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'datetime'=>$item->datetime(),
                'answer'=>$item->answer,
                'res_code'=>$item->res_code,
                'status'=>$item->getStatus(),
                'delivery'=>$item->delivery(),
                'items'=> new OrdersItemCollection($item->items),
                'price'=>number_format($item->price),
                'discount'=>number_format($item->discount_price),
                'delivery_cost'=>number_format($item->delivery_cost),
                'total_price'=>number_format($item->total_price),
                'receiver_name'=>$item->full_name,
                'receiver_mobile'=>$item->mobile,
                'receiver_address'=>$item->address,
            ];
        });
    }
}

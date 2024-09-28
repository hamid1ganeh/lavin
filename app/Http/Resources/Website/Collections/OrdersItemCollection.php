<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'product_name'=>$item->product_name,
                'count'=>$item->count,
                'price'=>$item->price,
                'sum'=>$item->sum,
            ];
        });
    }
}

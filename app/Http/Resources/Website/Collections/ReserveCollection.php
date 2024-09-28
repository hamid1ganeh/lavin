<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReserveCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'service'=>$item->service_name,
                'detail'=>$item->detail_name,
                'doctor'=>$item->doctor->fullname,
                'reserve_time'=>$item->reserve_time(),
                'round_time'=> $item->round_time(),
                'status'=>$item->getStatus(),
            ];
        });
    }
}

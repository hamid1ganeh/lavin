<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
class AnalysisCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'analyse'=> $item->analyse->title,
                'doctor'=>$item->doctor->fullname ?? '',
                'price'=>$item->price,
                'askDate'=>$item->ask_date_time(),
                'status'=>$item->getStatus()
            ];
        });
    }
}

<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FaqCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'question'=>$item->question,
                'answer'=>$item->answer,
            ];
        });
    }
}

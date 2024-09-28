<?php

namespace App\Http\Resources\Website\Collections;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\ServiceDetailCollection;

class VideoCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'link'=>$item->link,
            ];
        });

    }
}

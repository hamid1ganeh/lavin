<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;


class HilightCollection extends ResourceCollection
{

    public function toArray($request) :object
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'thumbnail'=> $item->getThumbnail('thumbnail')
            ];
        });
    }
}

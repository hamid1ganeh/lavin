<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GalleryCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'name'=>$item->name,
                'alt'=>$item->name,
                'thumbnail'=> $item->images[0]->getImagePath('medium'),
            ];
        });
    }
}

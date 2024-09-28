<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'alt'=>$item->alt,
                'original'=> $item->getImagePath('original'),
                'large'=> $item->getImagePath('large'),
                'medium'=> $item->getImagePath('medium'),
                'thumbnail'=> $item->getImagePath('thumbnail'),
            ];
        });
    }
}

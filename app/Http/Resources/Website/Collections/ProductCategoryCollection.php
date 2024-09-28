<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\ArticleCollection;

class ProductCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->name,
                'slug'=>$item->slug,
                'thumbnail'=>$item->getThumbnail('thumbnail'),
            ];
        });
    }
}

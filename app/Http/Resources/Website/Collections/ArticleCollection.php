<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\ArticleCategoryCollection;

class ArticleCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'slug'=>$item->slug,
                'tags'=>$item->tags,
                'commentsCount'=> count($item->comments),
                'thumbnail'=>$item->get_thumbnail('thumbnail'),
            ];
        });
    }
}

<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\ArticleCollection;

class ArticleCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'slug'=>$item->slug,
                'thumbnail'=>$item->get_thumbnail('thumbnail'),
                'articles'=> new ArticleCollection($item->articles)
            ];
        });
    }
}

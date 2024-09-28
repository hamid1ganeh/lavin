<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmploymentJobCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title'=>$item->title,
                'main_category'=> $item->main_category->title,
                'sub_category'=> $item->sub_category->title
            ];
        });
    }
}

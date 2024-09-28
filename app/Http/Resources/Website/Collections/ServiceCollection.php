<?php

namespace App\Http\Resources\Website\Collections;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Website\Collections\ServiceDetailCollection;

class ServiceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'name'=>$item->name,
                'slug'=>$item->slug,
                'label'=>$item->label,
                'slogan'=>$item->slogan,
                'services'=> new ServiceDetailCollection($item->activeServices)
            ];
        });

    }
}

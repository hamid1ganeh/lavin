<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'number'=>$item->number,
                'title'=>$item->title,
                'department'=>$item->department->name,
                'priority'=>$item->getPriority(),
                'status'=>$item->getStatus()
            ];
        });
    }
}

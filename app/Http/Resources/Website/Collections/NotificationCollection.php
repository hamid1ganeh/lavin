<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'date'=>$item->created_at(),
                'sender'=>$item->admin->fullname,
                'seen'=>$item->seenStatus(),
            ];
        });
    }
}

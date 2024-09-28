<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'fullname'=>$item->fullname,
                'email'=>$item->email,
                'comment'=>$item->comment,
                'answer'=>$item->answer,
                'publish_date'=>$item->publish_date(),

            ];
        });
    }
}

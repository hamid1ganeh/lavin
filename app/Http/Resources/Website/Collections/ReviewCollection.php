<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            $survey = [];
            foreach (json_decode($item->reviews,true) as $review=>$rate){
                $survey[str_replace('_',' ',$review)]=$rate;
            }

            return [
                'id' => $item->id,
                'user'=> $item->user->getFullName(),
                'content' => $item->content,
                'survey' =>$survey
            ];
        });
    }
}

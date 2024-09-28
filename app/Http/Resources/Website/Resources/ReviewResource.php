<?php

namespace App\Http\Resources\Website\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\TicketMessagesCollection;
class ReviewResource extends JsonResource
{

    public function toArray($request)
    {
        $reviews = [];
        foreach (json_decode($this->reviews,true) as $key=>$review){
            $reviews[str_replace('_',' ',$key)]=$review;
        }

        return [
            'id' => $this->id,
            'user'=>$this->user->getFullName(),
            'admin'=>$this->admin->fullname ?? null,
            'content'=>$this->content,
            'reviews'=> $reviews,
            'date'=> $this->date()
        ];
    }
}

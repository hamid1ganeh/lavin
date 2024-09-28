<?php

namespace App\Http\Resources\Website\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\TicketMessagesCollection;
class TicketResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'number'=>$this->number,
            'title'=>$this->title,
            'department'=>$this->department->name,
            'priority'=> $this->getPriority(),
            'status'=> $this->getStatus(),
            'messages'=> new TicketMessagesCollection($this->messages)
        ];
    }
}

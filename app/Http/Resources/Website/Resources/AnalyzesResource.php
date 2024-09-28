<?php

namespace App\Http\Resources\Website\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\TicketMessagesCollection;
class AnalyzesResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullname'=>$this->user->getFullName(),
            'mobile'=>$this->user->mobile,
            'analyse'=>$this->analyse->title,
            'price'=>$this->price,
            'askDate'=> $this->ask_date_time(),
            'status'=>$this->getSTatus(),
            'doctor'=>$this->doctor->fullname,
            'response'=>$this->response,
            'analysedImage'=> is_null($this->responseImage)?null:$this->responseImage->getImagePath(),
            'voice'=>$this->voice,
            'images'=>$this->images
        ];
    }
}

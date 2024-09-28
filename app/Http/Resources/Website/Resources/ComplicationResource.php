<?php

namespace App\Http\Resources\Website\Resources;

use App\Http\Resources\Website\Collections\ArticleCategoryCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\CommentCollection;
class ComplicationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description'=>$this->description,
            'prescription'=>$this->prescription,
            'status'=>$this->getStatus(),
            'complications'=>$this->complications->pluck('title'),
            'register_at'=> $this->register_at()
        ];
    }
}

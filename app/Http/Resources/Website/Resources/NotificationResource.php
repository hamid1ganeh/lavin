<?php

namespace App\Http\Resources\Website\Resources;

use App\Http\Resources\Website\Collections\ArticleCategoryCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\CommentCollection;
class NotificationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=>$this->title,
            'message'=>$this->message,
            'sender'=>$this->admin->fullname,
            'date'=>$this->created_at(),
            'seen'=> $this->seenStatus(),
        ];
    }
}

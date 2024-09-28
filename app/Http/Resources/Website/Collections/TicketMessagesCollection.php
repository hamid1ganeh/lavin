<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Gravatar;

class TicketMessagesCollection extends ResourceCollection
{
    public function toArray($request)
    {

        return $this->collection->map(function ($item){

            if($item->sender_type == "App\\Models\\User"){
                $senderType = 'user';
                $sender =  $item->senderable->getFullName();
                $avatar = Gravatar::get($item->senderable->email());
            }elseif ($item->sender_type == "App\\Models\\Admin"){
                $senderType = 'admin';
                $sender =  $item->senderable->fullname;
                $avatar = Gravatar::get($item->senderable->email);
            }

            return [
                'id' => $item->id,
                'content'=>$item->content,
                'attach'=> env('APP_URL').$item->attach,
                'sender_type'=> $senderType,
                'sender'=>$sender,
                'avatar'=> $avatar,
                'date'=>$item->date(),
            ];
        });
    }
}

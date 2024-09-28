<?php

namespace App\Http\Resources\Website\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\TicketMessagesCollection;
class PollResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'user'=>$this->user->getFullName(),
            'admin'=>$this->admin->fullname ?? null,
            'text'=>$this->text,
            'زمان انتظار'=> $this->duration,
            'کیفیت سرویس'=> $this->serviceQuality,
            'رفتار پرسنل'=> $this->staffBehavior,
            'رضایت از محصول'=> $this->satisfactionWithProduct
        ];
    }
}

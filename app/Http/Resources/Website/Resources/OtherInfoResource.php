<?php

namespace App\Http\Resources\Website\Resources;


use App\Enums\genderType;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
class OtherInfoResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'job_id'=> $this->job_id,
            'email'=> $this->email,
            'birthDate'=>$this->birth_date(),
            'mobile'=>  $this->married,
            'marriageDate'=> $this->marriage_date(),
        ];
    }
}

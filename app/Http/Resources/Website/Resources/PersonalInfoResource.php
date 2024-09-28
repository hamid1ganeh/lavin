<?php

namespace App\Http\Resources\Website\Resources;


use App\Enums\genderType;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
class PersonalInfoResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'firstname'=> Auth('sanctum')->user()->firstname,
            'lastname'=> Auth('sanctum')->user()->firstname,
            'nationcode'=> Auth('sanctum')->user()->nationcode,
            'mobile'=> Auth('sanctum')->user()->mobile,
            'gender'=> Auth('sanctum')->user()->gender,
            'genderList'=> genderType::getGenderList
        ];
    }
}

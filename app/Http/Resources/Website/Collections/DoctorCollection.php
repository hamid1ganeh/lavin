<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'fullname'=>$item->admin->fullname,
                'speciality'=>$item->speciality,
                'code'=>$item->code,
                'image'=> $item->admin->get_image('thumbnail')
            ];
        });
    }
}

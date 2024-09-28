<?php

namespace App\Http\Resources\Website\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Enums\DiscountType;
use \Morilog\Jalali\CalendarUtils;
class DiscountCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {

            if ($item->unit == DiscountType::percet) {
                $unit = "درصد(%)";
            } elseif ($item->unit == DiscountType::toman){
                $unit = "مبلغ(تومان)";
            }

            if($item->expire!=null){
                $expire =   CalendarUtils::convertNumbers(\Morilog\Jalali\CalendarUtils::strftime('H:i:s - Y/m/d',strtotime($item->expire)));
            }else{
                $expire = 'نامحدود';
            }

            if($item->used() == false){
                $status = 'قابل استفاده';
            }elseif($this->expired()){
                $status = 'منقضی شده';
            }elseif($this->used()){
                $status = 'استفاده شده';
            }
            return [
                'id' =>  $item->id,
                'code'=> $item->code,
                'unit'=> $unit,
                'value'=>$item->value,
                'expire' => $expire,
                'status'=>$status
            ];
        });
    }
}

<?php


namespace App\Services;


use App\Enums\ReserveStatus;
use DOMDocument;
use DOMXPath;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\ServiceReserve;

class ReserveService
{
    public function reserve($branch,$type,$user_id,$service_id,$detail_id,$doctor_id,$adviser_id=null,$reception_desc=null,$status=ReserveStatus::waiting,$complication_id=null)
    {
        $service = Service::find($service_id);
        $detail = ServiceDetail::find($detail_id);

        ServiceReserve::create([
            'user_id'=>$user_id,
            'branch_id'=>$branch,
            'service_id'=>$service->id,
            'service_name'=>$service->name,
            'detail_id'=> $detail->id,
            'detail_name'=> $detail->name,
            'doctor_id'=>$doctor_id,
            'adviser_id'=>$adviser_id,
            'type'=>$type,
            'reception_desc'=>$reception_desc,
            'status'=>$status,
            'complication_id'=>$complication_id,
            'price'=> $detail->price,
        ]);
    }
}

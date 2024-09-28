<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ComplicationStatus;
use App\Enums\ReserveStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Complication;
use App\Models\ComplicationItem;
use App\Models\RegisterComplication;
use App\Models\ServiceDetail;
use App\Models\ServiceReserve;
use App\Services\ReserveService;
use Illuminate\Http\Request;
use App\Enums\ReserveType;

class RegisterComplicationController extends Controller
{
     public  function show(ServiceReserve $reserve)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('complications.show');

         $complication = RegisterComplication::where('reserve_id',$reserve->id)->first();
         $complications = ComplicationItem::with('complications','reserves')->where('reserve_id',$reserve->id)->orderBy('created_at','desc')->get();

         $complicationList = Complication::where('status',Status::Active)->orderBy('created_at','asc')->get();
         $serviceDetails = ServiceDetail::where('status',Status::Active)->orderBy('name','asc')->get();


         return view('admin.reserves.complication',compact('complication','reserve','complications','complicationList','serviceDetails'));
     }

     public function create(ServiceReserve $reserve,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('complications.item.create');

         $request->validate(
             [
                 'complications' => ['required'],
                 'prescription' => ['nullable','max:3600'],
                 'explain' => ['nullable','max:3600'],
                 'status' => ['required'],
             ],
             [
                 "complications.required" => "انتخاب عوارض الزامی است.",
                 "status.required" => " تعیین وضعیت الزامی است.",
                 "prescription.max" => "متن توصیه پزشک طولانی است.",
                 "explain.max" => "متن  توضیحات الزامی است.",
             ]);

            if(in_array($request->status,[ComplicationStatus::followed,ComplicationStatus::following,ComplicationStatus::pending,ComplicationStatus::cancel,ComplicationStatus::treatment])){
                $complication = new ComplicationItem();
                $complication->reserve_id  = $reserve->id;
                $complication->prescription = $request->prescription;
                $complication->explain = $request->explain;
                $complication->status = $request->status;
                $complication->save();
                $complication->complications()->sync($request->complications);
                toast('عارضه جدید ثبت شد.', 'success')->position('bottom-end');
            }else{
                alert()->error('تعیین وضعیت الزامی است.', 'خطا');
            }
            return back();
     }

    public function update(ServiceReserve $reserve,ComplicationItem $complication,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.item.edit');

        $request->validate(
            [
                'complications' => ['required'],
                'prescription' => ['nullable','max:3600'],
                'explain' => ['nullable','max:3600'],
                'status' => ['required'],
            ],
            [
                "complications.required" => "انتخاب عوارض الزامی است.",
                "status.required" => " تعیین وضعیت الزامی است.",
                "prescription.max" => "متن توصیه پزشک طولانی است.",
                "explain.max" => "متن  توضیحات الزامی است.",
            ]);

        if(in_array($request->status,[ComplicationStatus::followed,ComplicationStatus::following,ComplicationStatus::pending,ComplicationStatus::cancel,ComplicationStatus::treatment])){
            $complication->reserve_id  = $reserve->id;
            $complication->prescription = $request->prescription;
            $complication->explain = $request->explain;
            $complication->status = $request->status;
            $complication->save();
            $complication->complications()->sync($request->complications);
            toast('بروزرسانی با موفقیت انجام شد.', 'success')->position('bottom-end');
        }else{
            alert()->error('تعیین وضعیت الزامی است.', 'خطا');
        }
        return back();
    }


    public function delete(ServiceReserve $reserve,ComplicationItem $complication)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.item.delete');

        $complication->delete();
        toastr()->error('عارضه  مورد نظر حذف  شد.');
        return  back();
    }


    public function reserve(ServiceReserve $reserve,ComplicationItem $complication,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.item.reserve');

        $request->validate(
            [
                'services' => ['required'],
            ],
            [
                "services.required" => "انتخاب سرویس الزامی است.",
            ]);


        $services = $request->services;
        $reserveService = new ReserveService();
        foreach ($services as $service){
        $service = ServiceDetail::find($service);

        $reserveService->reserve($reserve->branch_id,
                    ReserveType::complication,
                          $reserve->user_id,
                          $service->service_id,
                          $service->id,
                          $reserve->doctor_id,
                          null,
                          null,
                    ReserveStatus::waiting,
                           $complication->id);

        }

        toast('سرویس رزرو ایجاد شد', 'success')->position('bottom-end');
        return back();
    }

}

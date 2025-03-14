<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ReserveUpgrade;
use App\Models\ServiceReserve;
use App\Models\Admin;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Enums\Status;
use App\Enums\ReserveStatus;

class ReserveUpgradeController extends Controller
{
     public function index(ServiceReserve $reserve)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.index');

        $upgrades = ReserveUpgrade::with('reserve.reception')->where('reserve_id',$reserve->id)->get();

        return view('admin.reserves.upgrade.all',compact('upgrades','reserve'));
     }

     public function create(ServiceReserve $reserve)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.create');

         if (is_null($reserve->asistant_id)){
             abort(403);
         }

         $asistants2 = Admin::whereHas('roles', function($q){$q->where('name', 'asistant2');})->orderBy('fullname','asc')->get();
         $services = Service::orderBy('name','asc')->get();

         return view('admin.reserves.upgrade.create',compact('reserve','asistants2','services'));
     }

     public function store(ServiceReserve $reserve,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.create');

         if(is_null($reserve->secratry_id)){
             alert()->error(' منشی انتخاب نشده است.','خطا!');
             return back();
         }

           $reception = Reception::find($reserve->reception_id);
         if ($reception->end){
             alert()->error(' کد مراجعه '.$reception->code.' بسته شده است.','امکان ارتقاء وجود ندارد!');
             return back();
         }

          $request->validate([
            "service"=>"required|exists:services,id",
            "detail"=>"required|exists:service_details,id",
            "price"=>"required|integer",
            'asistant2'=>"nullable|exists:admins,id",
            'desc'=>"nullable|max:63000"
          ],
         [
            "service.required"=>"سرگروه سرویس الزامی است.",
            "price.required"=>"مبلغ را وارد کنید.",
            "price.integer"=>"مبلغ وارد شده معتبر نیست.",
            "detail.required"=>"انتخاب سرویس الزامی است",
            "desc.max"=>"حداکثر 63000 کارکتر"
         ]);

         $service = Service::find($request->service);
         $detail = ServiceDetail::find($request->detail);


         ReserveUpgrade::create([
            "reserve_id"=>$reserve->id,
            "service_id"=>$service->id,
            "service_name"=>$service->name,
            "detail_id"=>$detail->id,
            "detail_name"=>$detail->name,
            "price"=>$request->price,
            "asistant1_id"=>$reserve->asistant_id,
            "asistant2_id"=>$request->asistant2,
            "desc"=>$request->desc
         ]);

         toastr()->success('ارتقاء جدید افزوده شد.');
         return redirect(route('admin.reserves.upgrade.index',$reserve));
     }



     public function edit(ServiceReserve $reserve,ReserveUpgrade $upgrade)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.edit');

         $asistants2 = Admin::whereHas('roles', function($q){$q->where('name', 'asistant2');})->orderBy('fullname','asc')->get();
         $services = Service::orderBy('name','asc')->get();
         $details = ServiceDetail::where('status',Status::Active)->where('service_id',$upgrade->service_id)->orderBy('name','asc')->get();

         return view('admin.reserves.upgrade.edit',compact('reserve','upgrade','asistants2','services','details'));
     }

     public function update(ServiceReserve $reserve,ReserveUpgrade $upgrade,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.edit');

          $request->validate([
            "service"=>"required|exists:services,id",
            "detail"=>"required|exists:service_details,id",
            "price"=>"required|integer",
            'asistant2'=>"nullable|exists:admins,id",
            'desc'=>"nullable|max:63000"
          ],
         [
            "service.required"=>"سرگروه سرویس الزامی است.",
            "price.required"=>"مبلغ را وارد کنید.",
            "price.integer"=>"مبلغ وارد شده معتبر نیست.",
            "detail.required"=>"انتخاب سرویس الزامی است",
            "desc.max"=>"حداکثر 63000 کارکتر"
         ]);

         $service = Service::find($request->service);
         $detail = ServiceDetail::find($request->detail);

         $upgrade->update([
            "reserve_id"=>$reserve->id,
            "service_id"=>$service->id,
            "service_name"=>$service->name,
            "detail_id"=>$detail->id,
            "detail_name"=>$detail->name,
            "price"=>$request->price,
            "asistant1_id"=>$reserve->asistant_id,
            "asistant2_id"=>$request->asistant2,
            "desc"=>$request->desc
         ]);


         toastr()->success('.بروزرسانی با موفقیت انجام شد');

         return redirect(route('admin.reserves.upgrade.index',$reserve));
     }


     public function confirm(ServiceReserve $reserve,ReserveUpgrade $upgrade,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.confirm');

         if($request->status == ReserveStatus::confirm || $request->status == ReserveStatus::cancel)
         {
             if($request->status == ReserveStatus::confirm){
                 $upgrade->doneTime = Carbon::now('+3:30')->format('Y-m-d H:i:s');
             }

             if($request->status == ReserveStatus::cancel){
                 $upgrade->doneTime = null;
             }
             $upgrade->status =$request->status;
             $upgrade->save();
         }

         return  back();
     }

     public function delete(ServiceReserve $reserve,ReserveUpgrade $upgrade)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reserves.upgrade.delete');

         $upgrade->delete();
         toast(' ارتقاء مورد نظر حذف شد.','error')->position('bottom-end');
         return back();
     }

}

<?php

namespace App\Http\Controllers\Website;

use App\Enums\ReserveStatus;
use App\Enums\ReserveType;
use App\Http\Controllers\Controller;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Enums\Status;
use App\Services\ReserveService;
use App\Services\SMS;
use Auth;

class ServiceController extends Controller
{
   private $reserveService;

   public function __construct()
   {
      $this->reserveService = new ReserveService();
   }

   public function index()
   {
       $pageServices = Service::with('details')
       ->where('status',Status::Active)
       ->paginate(5)
       ->withQueryString();

       return view('website.services.index',compact('pageServices'));
   }

   public function show($slug)
   {
      $service = ServiceDetail::with('images','doctors','branches','videos.poster','service.details','reserves.review','service.parent_cat','service.child_cat')->where('slug',$slug)->where('status',Status::Active)->first();
      if($service==null)
      {
         abort(404);
      }
      return view('website.services.show',compact('service'));
   }

   public function reserve($service,$detail,Request $requast)
   {
      $detail = ServiceDetail::with('doctors','branches')->find($detail);

      if(in_array($requast->doctor_id,$detail->doctors->pluck('id')->toArray()) &&
          in_array($requast->branch_id,$detail->branches->pluck('id')->toArray()))
      {
          if (ServiceReserve::where('detail_id',$detail->id)->where('user_id',Auth::id())->where('branch_id',$requast->branch_id)->whereIn('status',[ReserveStatus::waiting,ReserveStatus::wittingForAdviser,ReserveStatus::Adviser])->exists()){
              alert()->error('این سرویس را در این شعبه قبلا رزرو کرده اید', 'خطا');
              return back();
          }

         $this->reserveService->reserve($requast->branch_id,ReserveType::online,Auth::id(),$service,$detail->id,$requast->doctor_id,null,null,ReserveStatus::waiting,null);

         //ارسال sms
         $sms = new SMS;
         $text = Auth::user()->firstname.' '.Auth::user()->lastname." عزیز\nسرویس شما رزرو شد.\nجهت هماهنگی زمان مراجعه با شما تماس حاصل می شود.\nکلینیک لاوین رشت";
         $sms->send(array(Auth::user()->mobile),$text);

          alert()->success('تبریک','سرویس شما رزرو شد.جهت هماهنگی زمان مراجعه به زودی با شما تماس حاصل خواهد شد.');
         return back();
      }
      else
      {
          if(!in_array($requast->branch_id,$detail->branches->pluck('id')->toArray())){
              $msg = 'شعبه مورد نظر را نتخاب کنید';
          }else if(!in_array($requast->doctor_id,$detail->doctors->pluck('id')->toArray())){

              $msg = 'پزشک مورد نظر را نتخاب کنید';
          }
          alert()->error('خطا!', $msg);
          return back();
      }

   }
}

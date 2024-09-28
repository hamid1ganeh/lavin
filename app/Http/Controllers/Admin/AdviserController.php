<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReserveStatus;
use App\Enums\ReserveType;
use App\Http\Controllers\Controller;
use App\Models\AdviserHistory;
use App\Models\Number;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Adviser;
use App\Models\User;
use App\Models\ServiceDocument;
use App\Models\ServiceReserve;
use Carbon\Carbon;
use App\Services\SMS;
use App\Enums\NumberStatus;
use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use App\Services\ReserveService;
use Auth    ;

class AdviserController extends Controller
{
  public function index()
  {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('numbers.advisers');
      $roles = Auth::guard('admin')->user()->getRoles();
      if(count($roles)==1 && in_array('adviser',$roles))
      {
        $advisers = Adviser::with('service.advisers','service.documents','service.doctors','adviser','advisers.admin','number')
        ->where('adviser_id',Auth::guard('admin')->id())
        ->whereIn('status',[NumberStatus::Operator,NumberStatus::NoAnswer,NumberStatus::NextNotice,NumberStatus::Adviser,NumberStatus::RecivedDocuments,NumberStatus::WaitnigForDocuments])
        ->orderBy('updated_at','desc')
        ->filter()
        ->paginate(50)
        ->withQueryString();

      }
      else if(count($roles)==1 && in_array('adviser-arrangement',$roles))
      {
        $advisers = Adviser::with('service.advisers','service.documents','service.doctors','service.branches','adviser','advisers.admin','number')
        ->where('status',NumberStatus::Accept)
        ->orWhere('status',NumberStatus::WaitnigForDocuments)
        ->orWhere('status',NumberStatus::RecivedDocuments)
        ->orWhere('status',NumberStatus::Reservicd)
        ->orWhere('status',NumberStatus::Confirm)
        ->orWhere('status',NumberStatus::Done)
        ->orderBy('updated_at','desc')
        ->filter()
        ->paginate(50)
        ->withQueryString();

      }
      else if(count($roles)==2 && in_array('adviser-arrangement',$roles) && in_array('adviser',$roles)) {
          $advisers = Adviser::with('service.advisers','service.documents','service.doctors','adviser','advisers.admin','number')
          ->where('adviser_id',Auth::guard('admin')->id())
          ->whereIn('status',[NumberStatus::Operator,NumberStatus::NoAnswer,NumberStatus::NextNotice,NumberStatus::Adviser,NumberStatus::RecivedDocuments,NumberStatus::WaitnigForDocuments])
          ->orwhere('status',NumberStatus::Accept)
          ->orWhere('status',NumberStatus::WaitnigForDocuments)
          ->orWhere('status',NumberStatus::RecivedDocuments)
          ->orWhere('status',NumberStatus::Reservicd)
          ->orWhere('status',NumberStatus::Confirm)
          ->orWhere('status',NumberStatus::Done)
          ->orderBy('updated_at','desc')
          ->filter()
          ->paginate(50)
          ->withQueryString();
      }

    return view('admin.advisers',compact('advisers'));
  }

  public function update_adviser(Adviser $adviser,Request $request)
  {
    //اجازه دسترسی
    config(['auth.defaults.guard' => 'admin']);
    $this->authorize('numbers.adviser-phone');

    $request->validate([
        'information' => "nullable|max:65535",
        'adviser_description' => "required|max:65535",
    ],
    [
        "information.max"=>"متن سایر اطلاعات مشتری طولانی است",
        "adviser_description.max"=>"متن توضیحات مشاور خیلی طولانی است",
        "adviser_description.required"=>"متن توضیحات مشاور الزامی است",
    ]);

    if($request->status == NumberStatus::Accept || $request->status == NumberStatus::NoAnswer
       || $request->status == NumberStatus::NextNotice || $request->status == NumberStatus::Cancel
       || $request->status == NumberStatus::Reservicd)
       {
            $adviser->adviser_description = $request->adviser_description;
            $adviser->status = $request->status;
            $number = $adviser->number;
            $number->information = $request->information;
            if($request->status == NumberStatus::Accept || $request->status == NumberStatus::Reservicd)
            {
                $number->accept_date_time = Carbon::now();
                $number->status = NumberStatus::Accept;
            }

           $history = AdviserHistory::where('admin_id',Auth::guard('admin')->id())->where('adviser_id',$adviser->id)->orderBy('created_at','desc')->first();
           $history->description = $request->adviser_description;


           If($request->status == NumberStatus::Reservicd)
           {
               $request->validate( ['doctor'=>'required|numeric',
                   'branch'=>'required|numeric',],
                   ['doctor.required'=>'انتخاب پزشک الزامی است.',
                       'doctor.numric'=>'انتخاب پزشک الزامی است.',
                       'branch.required'=>'انتخاب شعبه الزامی است.',
                       'branch.numric'=>'انتخاب شعبه الزامی است.']);

               $user = User::where('mobile',$adviser->number->mobile)->first()->id;
               $service = $adviser->service->service_id;
               $detail = $adviser->service->id;
               $doctor = $request->doctor;
               $branch = $request->branch;


               $reserve = ServiceReserve::where('adviser_id',$adviser->id)->first();

               if ($reserve==null){
                   $reserve = new ReserveService();
                   $adviser->status =NumberStatus::Reservicd;
                   $adviser->arrangement_id = Auth::guard('admin')->id();
                   $adviser->adviser_description = $request->adviser_description;

                   $number = $adviser->number;
                   $number->status = NumberStatus::Reservicd;

                   DB::transaction(function() use ($branch,$adviser, $user,$service,$detail,$doctor,$reserve,$number,$history) {
                       $reserve->reserve($branch,ReserveType::adivser,$user,$service,$detail,$doctor,$adviser->id,null,ReserveStatus::Adviser,null);
                       $adviser->save();
                       $number->save();
                       $history->save();
                   });
               }else {
                   $adviser->status =NumberStatus::Reservicd;
                   $adviser->arrangement_id =Auth::guard('admin')->id();
                   $adviser->adviser_description = $request->adviser_description;
                   $number = $adviser->number;
                   $number->status = NumberStatus::Reservicd;
                   $reserve->doctor_id = $doctor;
                   $reserve->branch_id = $branch;
                   $reserve->status = ReserveStatus::Adviser;

                   DB::transaction(function() use ($adviser,$reserve,$number,$history) {
                       $adviser->save();
                       $number->save();
                       $reserve->save();
                       $history->save();
                   });
               }

               toast('رزرو جدید ثبت شد.','success')->position('bottom-end');
               return back();
           }


           DB::transaction(function() use ($adviser,$number,$history) {
               $adviser->save();
               $number->save();
               $history->save();
           });

       }

       return back();
  }

  public function send_documents(Adviser $adviser,Request $request)
  {
    //اجازه دسترسی
    config(['auth.defaults.guard' => 'admin']);
    $this->authorize('numbers.sms');

      if ($adviser->status != NumberStatus::Accept){
          alert()->error('مشاوره انجام نشده است.', 'هشدار');
          return back();
      }

    $documents = ServiceDocument::where([['service_id',$adviser->service_id],['status',Status::Active]])->get('title');

    if($documents->isEmpty())
    {
      alert()->error('برای این سرویس مدارک مورد نیاز ثبت نشده است', 'هشدار');
    }
    else
    {
      $text = $adviser->number->firstname." ".$adviser->number->lastname." عزیز \n مدارک مورد نیاز برای سرویس ".$adviser->service->name. " موارد زیر می باشد:\n ";
      foreach($documents->pluck('title') as $document)
      {
        $text .= "- ".$document."\n";
      }
      $text .= "\n  لطفا مدارک فوق را به آدرس ".$request->platform." زیر ارسال نمایید:\n".$request->id."\n";
      $text .="\n کلینیک لاوین رشت ";

      $sms = new SMS;
      $sms->send(array($adviser->number->mobile),$text);
      $adviser->status = NumberStatus::WaitnigForDocuments;
      $number = $adviser->number;
      $number->status = NumberStatus::WaitnigForDocuments;

    DB::transaction(function() use ($adviser, $number) {
        $adviser->save();
        $number->save();
    });

      $adviser->save();

      toast('پیامک ارسال شد.','success')->position('bottom-end');
    }
    return back();
  }

  public function recive_documents(Adviser $adviser,Request $request)
  {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('numbers.adviser-recive-document');

      if ($adviser->status != NumberStatus::Accept && $adviser->status != NumberStatus::WaitnigForDocuments){
          alert()->error('مشاوره انجام نشده است.', 'هشدار');
          return back();
      }

      $adviser->status = NumberStatus::RecivedDocuments;
      $number = $adviser->number;
      $number->status = NumberStatus::RecivedDocuments;

      DB::transaction(function() use ($adviser, $number) {
          $adviser->save();
          $number->save();
      });

    toast('بروزرسانی باموفقیت انجام شد.','success')->position('bottom-end');
    return back();
  }


  public function reserve(Adviser $adviser,Request $request)
  {
    //اجازه دسترسی
    config(['auth.defaults.guard' => 'admin']);
    $this->authorize('reserves.create');

    $request->validate( ['doctor'=>'required|numeric',
                        'branch'=>'required|numeric',],
                        ['doctor.required'=>'انتخاب پزشک الزامی است.',
                        'doctor.numric'=>'انتخاب پزشک الزامی است.',
                        'branch.required'=>'انتخاب شعبه الزامی است.',
                        'branch.numric'=>'انتخاب شعبه الزامی است.']);

     if ($adviser->status != NumberStatus::RecivedDocuments){
         alert()->error('دریافت مدارک انجام نشده است.', 'هشدار');
         return back();
     }

    $user = User::where('mobile',$adviser->number->mobile)->first()->id;
    $service = $adviser->service->service_id;
    $detail = $adviser->service->id;
    $doctor = $request->doctor;
    $branch = $request->branch;


    $reserve = ServiceReserve::where('adviser_id',$adviser->id)->first();

    if ($reserve==null){
        $reserve = new ReserveService();
        $adviser->status =NumberStatus::Reservicd;
        $adviser->arrangement_id = Auth::guard('admin')->id();

        $number = $adviser->number;
        $number->status = NumberStatus::Reservicd;

        DB::transaction(function() use ($branch,$adviser, $user,$service,$detail,$doctor,$reserve,$number) {
            $reserve->reserve($branch,ReserveType::adivser,$user,$service,$detail,$doctor,$adviser->id);
            $adviser->save();
            $number->save();
        });
    }else {
        $adviser->status =NumberStatus::Reservicd;
        $adviser->arrangement_id =Auth::guard('admin')->id();
        $number = $adviser->number;
        $number->status = NumberStatus::Reservicd;
        $reserve->doctor_id = $doctor;

        DB::transaction(function() use ($adviser,$reserve,$number) {
            $adviser->save();
            $number->save();
            $reserve->save();
        });
    }

    toast('رزرو جدید ثبت شد.','success')->position('bottom-end');

    return back();
  }


    public function destroy(Number $number,Adviser $adviser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.advisers.destroy');

        $adviser->delete();
        toastr()->error('سرویس مشاوره  مورد نظر حذف  شد.');
        return back();
    }

    public function cancel(Number $number,Adviser $adviser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.advisers.cancel');

        $adviser->status = NumberStatus::Cancel;
        $number->status = NumberStatus::Cancel;

        DB::transaction(function() use ($adviser,$number) {
            $adviser->save();
            $number->save();
        });

        toastr()->error('سرویس مشاوره  مورد نظر لغو  شد.');
        return back();
    }

    public function review(Number $number,Adviser $adviser,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.advisers.review.register');

        $request->validate([
            'content'=> 'required|max:63000'
        ],[
            'content.required'=>'* محتوا بازخورد الزامی است.',
            'content.max' => '* حداکثر طول محتوا بازخورد 63000 کارکتر'
        ]);

        $content = $request->content;
        $request = $request->except('_token','content');
        $reviews = json_encode($request,true);


        $review = Review::where('reviewable_id',$adviser->id)->where('reviewable_type',get_class($adviser))->firstOrnew();
        $user = User::where('mobile',$number->mobile)->first();

        $review->user_id = $user->id;
        $review->admin_id = Auth::guard('admin')->id();
        $review->reviewable_type = get_class($adviser);
        $review->reviewable_id = $adviser->id;
        $review->content = $content;
        $review->reviews = $reviews;
        $review->save();

        toast('بازخورد با موفقیت  ثبت شد','success')->position('bottom-end');
        return back();
    }


}

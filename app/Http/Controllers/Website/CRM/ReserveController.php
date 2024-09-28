<?php

namespace App\Http\Controllers\Website\CRM;

use App\Enums\ComplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\RegisterComplication;
use Illuminate\Http\Request;
use App\Models\ServiceReserve;
use App\Models\Admin;
use App\Models\Service;
use App\Models\ReviewGroup;
use App\Models\Review;
use App\Models\ReservePayment;
use App\Enums\Status;
use App\Enums\ReviewGroupType;
use App\Enums\PayWay;
use App\Models\ServiceDetail;
use App\Services\DiscountService;
use Auth;


class ReserveController extends Controller
{

    public function index()
    {
        $doctors = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})->orderBy('fullname','asc')->get();
        $services= Service::orderBy('name','asc')->get();
        $reserves = ServiceReserve::with('review','poll','complication')
        ->where('user_id',Auth::id())
        ->filter()
        ->orderBy('created_at','desc')
        ->paginate(10)
        ->withQueryString();

        $reviewGroups = ReviewGroup::where('type',ReviewGroupType::Service)->where('status',Status::Active)->get();


        return view('crm.reserves.all',compact('reserves','doctors','services','reviewGroups'));
    }

    public function review(ServiceReserve $reserve,Request $request)
    {
        return  $request;
       $request->validate([
           'content'=> 'required|max:63000'
       ],[
           'content.required'=>'* محتوا بازخورد الزامی است.',
           'content.max' => '* حداکثر طول محتوا بازخورد 63000 کارکتر'
       ]);

       $content = $request->content;
       $request = $request->except('_token','content');
       $reviews = json_encode($request,true);


       $review = Review::where('reviewable_id',$reserve->id)->where('reviewable_type',get_class($reserve))->firstOrnew();

        $review->user_id = Auth::id();
        $review->reviewable_type = get_class($reserve);
        $review->reviewable_id = $reserve->id;
        $review->content = $content;
        $review->reviews = $reviews;
        $review->save();

       toast('بازخورد با موفقیت  ثبت شد','success')->position('bottom-end');
       return back();
    }


    public function poll(ServiceReserve $reserve,Request $request)
    {

        $request->validate([
            'text'=> 'nullable|max:63000',
            'duration'=> 'required|integer|between:1,10',
            'serviceQuality'=> 'required|integer|between:1,10',
            'staffBehavior'=> 'required|integer|between:1,10',
            'satisfactionWithProduct'=> 'required|integer|between:1,10',
        ],[
            'text.max' => '* حداکثر طول محتوا بازخورد 63000 کارکتر',
            'duration.required'=>'به زمان انتظار از 1 تا 10 نمره دهید.',
            'duration.integer'=>'به زمان انتظار از 1 تا 10 نمره دهید.',
            'duration.between'=>'به زمان انتظار از 1 تا 10 نمره دهید.',
            'serviceQuality.required'=>'به کیفیت سرویس از 1 تا 10 نمره دهید.',
            'serviceQuality.integer'=>'به کیفیت سرویس از 1 تا 10 نمره دهید.',
            'serviceQuality.between'=>'به کیفیت سرویس از 1 تا 10 نمره دهید.',
            'staffBehavior.required'=>'به رفتار پرسنل از 1 تا 10 نمره دهید.',
            'staffBehavior.integer'=>'به رفتار پرسنل از 1 تا 10 نمره دهید.',
            'staffBehavior.between'=>'به رفتار پرسنل از 1 تا 10 نمره دهید.',
            'satisfactionWithProduct.required'=>'به رضایت پرسنل از 1 تا 10 نمره دهید.',
            'satisfactionWithProduct.integer'=>'به رضایت پرسنل از 1 تا 10 نمره دهید.',
            'satisfactionWithProduct.between'=>'به رضایت پرسنل از 1 تا 10 نمره دهید.',
        ]);


        $poll = Poll::where('reserve_id',$reserve->id)->firstOrnew();
        $poll->user_id = Auth::id();
        $poll->reserve_id = $reserve->id;
        $poll->text = $request->text;
        $poll->duration = $request->duration;
        $poll->serviceQuality = $request->serviceQuality;
        $poll->staffBehavior = $request->staffBehavior;
        $poll->satisfactionWithProduct = $request->satisfactionWithProduct;
        $poll->save();

        toast('نظرسنجی   با موفقیت  ثبت شد','success')->position('bottom-end');
        return back();
    }

    public function payment(ServiceReserve $reserve)
    {

         $payement = ReservePayment::with('reserve.service')->where('reserve_id',$reserve->id)->first();

         if($payement==null)
         {
             $detail = ServiceDetail::find($reserve->detail_id);
            $payement = ReservePayment::with('reserve.service')->create([
                'reserve_id' => $reserve->id,
                'user_id' => $reserve->user_id,
                'price' => $detail->price,
            ]);
         }


        return view('crm.reserves.payment',compact('payement'));
    }

    public function discount(Request $request)
    {
        $discountService =  new DiscountService;

        if(isset($request->code) && $request->code!='')
        {
            $result = $discountService->discount(ServiceReserve::class,$request);
            if($result['status']==false)
            {
                alert()->error($result['msg'],'خطا');
                return back();
            }
            else
            {
               alert()->success($result['msg'],'تبریک');
               $typeDiscount = $result['typeDiscount'];
               $offer = $result['offer'];
               $code = $result['code'];
               return redirect()->back()->with(compact('typeDiscount','offer','code'));
            }
        }
        else
        {
            alert()->error('کد خفیف را وارد نمایید.','خطا');
            return back();
        }
    }

    public function pay(ReservePayment $payment,Request $request)
    {
        $offer =0;
        $discount_id = null;
        $getway = 'zarinpal';
        $discountService =  new DiscountService;
        if(isset($request->code) && $request->code!='')
        {
            $result = $discountService->discount(ServiceReserve::class,$request);
            if($result['status']==false)
            {
                alert()->error($result['msg'],'خطا');
                return back();
            }
            else
            {
               $discount_id = $result['discount_id'];
               $offer = $result['offer'];
            }
        }

        $payment->discount_price = $offer;
        $payment->discount_price = $offer;
        $payment->total_price = $payment->price-$offer;
        $payment->discount_id = $discount_id;
        $payment->payway = PayWay::online;
        $payment->getway = $getway;
        $payment->save();

        return redirect(route('website.payments.pay',
        ['model' => get_class($payment),'model_id'=>$payment->id,'getway'=>'zarinpal']));
    }

    public function complications(ServiceReserve $reserve,Request $request)
    {
        $request->validate([
            'description'=> 'required|max:63000',
        ],[
            'description.max' => '* حداکثر طول محتوا بازخورد 63000 کارکتر',
            'description.required'=>'توضیحات عوارض الزامی است.',
        ]);

        $registered =  RegisterComplication::where('reserve_id',$reserve->id)->first();
        if(is_null($registered)){
            $registered = new RegisterComplication();
            $registered->reserve_id = $reserve->id;
            $registered->description = $request->description;
        }else{
            if($registered->status != ComplicationStatus::pending){
                alert()->error('توضیحات شما قابل ویرایش نمی باشد.','خطا');
                return back();
            }
            $registered->description = $request->description;
        }

        $registered->save();
        alert()->success('درخواست عوارض ثبت شد.','تایید');
        return back();

    }
}

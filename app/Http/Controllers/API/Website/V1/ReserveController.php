<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\ComplicationStatus;
use App\Enums\ReviewGroupType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\ReviewGroup;
use App\Models\ServiceReserve;
use App\Models\RegisterComplication;
use App\Models\Review;
use App\Services\ReserveService;
use Illuminate\Http\Request;
use App\Http\Resources\Website\Collections\ReserveCollection;
use App\Http\Resources\Website\Collections\ReviewGroupsCollection;
use App\Http\Resources\Website\Resources\ComplicationResource;
use App\Http\Resources\Website\Resources\ReviewResource;
use App\Http\Resources\Website\Resources\PollResource;
use Illuminate\Http\Response;
use Validator;

class ReserveController extends Controller
{
    public function index()
    {
        $reserves = ServiceReserve::where('user_id',Auth('sanctum')->id())
            ->orderBy('created_at','desc')
            ->get();
        return response()->json(['reserves'=> new ReserveCollection($reserves)],200);
    }

    public function complications(ServiceReserve $reserve)
    {
        $complication = RegisterComplication::with('complications')
        ->where('reserve_id',$reserve->id)
        ->first();

        return response()->json(['reserves'=> new ComplicationResource($complication)],200);
    }

    public function register_complications(ServiceReserve $reserve,Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'description' => ['required','max:63000'],
            ] ,[
                'description.required' => ' توضیحات الزامی است.',
                'description.max' => 'توضیحات طولانی می باشد.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $registered =  RegisterComplication::where('reserve_id',$reserve->id)->first();
        if(is_null($registered)){
            $registered = new RegisterComplication();
            $registered->reserve_id = $reserve->id;
            $registered->description = $request->description;
        }else{
            if($registered->status != ComplicationStatus::pending){
                alert()->error('توضیحات شما قابل ویرایش نمی باشد.','خطا');
                return response()->json(['message'=> 'توضیحات شما قابل ویرایش نمی باشد.'],409);
            }
            $registered->description = $request->description;
        }

        $registered->save();
        return response()->json(['message'=> 'درخواست عوارض ثبت شد.'],200);
    }

    public function review(ServiceReserve $reserve)
    {
        $reviews = Review::where('reviewable_type',get_class($reserve))
        ->where('reviewable_id',$reserve->id)
        ->first();

        $reviewGroups = ReviewGroup::where('type',ReviewGroupType::Service)->where('status',Status::Active)->get();

        return response()->json(['reviews'=> new ReviewResource($reviews),
                                 'reviewGroups'=> new ReviewGroupsCollection($reviewGroups)],200);
    }

    public function register_review(ServiceReserve $reserve,Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'content' => ['required','max:63000'],
            ] ,[
                'content.required' => ' محتوا الزامی است.',
                'content.max' =>'حداکثر طول محتوا بازخورد 63000 کارکتر'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $content = $request->content;
        $request = $request->except('_token','content');
        $reviews = json_encode($request,true);

        $review = Review::where('reviewable_id',$reserve->id)->where('reviewable_type',get_class($reserve))->firstOrnew();

        $review->user_id = Auth('sanctum')->id();
        $review->reviewable_type = get_class($reserve);
        $review->reviewable_id = $reserve->id;
        $review->content = $content;
        $review->reviews = $reviews;
        $review->save();

        return response()->json(['message'=>'بازخورد با موفقیت  ثبت شد'],200);
    }

    public function poll(ServiceReserve $reserve)
    {
        $polls = Poll::where('reserve_id',$reserve->id)
            ->first();

        return response()->json(['polls'=> new PollResource($polls)],200);
    }

    public function register_poll(ServiceReserve $reserve,Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'text'=> 'nullable|max:63000',
                'duration'=> 'required|integer|between:1,10',
                'serviceQuality'=> 'required|integer|between:1,10',
                'staffBehavior'=> 'required|integer|between:1,10',
                'satisfactionWithProduct'=> 'required|integer|between:1,10',
            ] ,[
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
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $poll = Poll::where('reserve_id',$reserve->id)->firstOrnew();
        $poll->user_id =  Auth('sanctum')->id();
        $poll->reserve_id = $reserve->id;
        $poll->text = $request->text;
        $poll->duration = $request->duration;
        $poll->serviceQuality = $request->serviceQuality;
        $poll->staffBehavior = $request->staffBehavior;
        $poll->satisfactionWithProduct = $request->satisfactionWithProduct;
        $poll->save();

        return response()->json(['message'=> 'نظرسنجی   با موفقیت  ثبت شد.'],200);

    }

}

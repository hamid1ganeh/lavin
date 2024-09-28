<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\genderType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Resources\PersonalInfoResource;
use App\Http\Resources\Website\Resources\OtherInfoResource;
use App\Http\Resources\Website\Collections\JobCollection;
use App\Models\Number;
use App\Models\Province;
use App\Models\UserAddress;
use App\Models\UserBank;
use App\Models\UserInfo;
use App\Models\Job;
use App\Services\FunctionService;
use App\Services\PointService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;
use Validator;
class ProfileController extends Controller
{
    private $fuctionService;

    public function __construct()
    {
        $this->fuctionService = new FunctionService();
    }
    public function info()
    {
        $info = Auth('sanctum')->user();
        return response()->json(['info'=> new PersonalInfoResource($info)],200);
    }

    public function update_info(Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'firstname'=>'required|max:255',
                'lastname'=>'required|max:255',
                'nationcode'=>'required|min:10|max:10|regex:/^[0-9]+$/|unique:users,nationcode,'.Auth('sanctum')->id(),
                'mobile'=>'required|min:11|max:11|regex:/^[0-9]+$/|unique:users,mobile,'.Auth('sanctum')->id(),
            ] ,[
                'firstname.required'=>'* نام الزامی است.',
                'firstname.max'=>'* نام حداکثر 255 کارکتر',
                'lastname.required'=>'* نام خانوادگی الزامی است.',
                'lastname.max'=>'* نام خانوادگی حداکثر 255 کارکتر',
                'mobile.required'=>'* موبایل الزامی است.',
                'mobile.max'=>'* 09********* فرمت درست موبایل',
                'mobile.min'=>'* 09*********فرمت درست موبایل ',
                'mobile.unique'=> "* شماره موبایل قبلا ثبت شده است.",
                'nationcode.required'=> "* کد ملی 10 رقمی را وارد کنید.",
                'nationcode.min'=>  "* کد ملی 10 رقمی را وارد کنید.",
                'nationcode.max'=> "* کد ملی 10 رقمی را وارد کنید.",
                'nationcode.regex'=> "* کد ملی 10 رقمی را وارد کنید.",
                'nationcode.unique'=> "این کدملی قبلا ثبت شده است.",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $user =  Auth('sanctum')->user();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->nationcode = $request->nationcode;

        $number = Number::where("mobile",$request->mobile)->first();
        if($number==null){
            $number = new Number();
        }
        $number->firstname = $request->firstname;
        $number->lastname = $request->lastname;
        $number->mobile = $request->mobile;

        if($user->mobile != $request->mobile)
        {
            $user->verify_code = rand(1000,9999);
            $user->verify_expire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
            $user->verified =false;
            $user->mobile = $request->mobile;
        }

        if($request->gender==genderType::female || $request->gender==genderType::male || $request->gender==genderType::LGBTQ)
        {
            $user->gender = $request->gender;
        }

        DB::transaction(function() use ($user,$number) {
            $user->save();
            $number->save();
        });

        return response()->json(['message'=> "بروزرسانی انجام شد."],200);
    }

    public  function change_password(Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                "password"=>['required','max:255','min:6','confirmed','required_with:password_confirmation|same:password_confirmation'],
            ] ,[
                'password.required' => '* کلمه عبور است.',
                'password.max' => 'حداکثر کلمه 255 کارکتر',
                'password.min' => ' حداقل کلمه 6 کارکتر',
                'password.confirmed' => ' تکرار رمز عبور منطبق نمی باشد ',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = Auth('sanctum')->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message'=> "بروزرسانی انجام شد."],200);
    }

    public function address()
    {
        $address = UserAddress::where('user_id',Auth('sanctum')->id())->first();
        $provinces = Province::with('cities.parts')->get();

        return response()->json(['address' =>$address,
                                'provinces'=> $provinces],200);
    }

    public  function address_update(Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'province_id'=>'required|exists:provinces,id',
                'city_id'=>'required|exists:cities,id',
                'part_id'=>'nullable|exists:city_parts,id',
                'address'=>'required|max:255',
                'postalcode'=>'required|max:10|min:10|regex:/^[0-9]+$/',
            ] ,[
                'province_id.required'=>'* انتخاب استان الزامی است.',
                'city_id.required'=>'* انتخاب شهر است.',
                'address.required'=>'* آدرس الزامی است.',
                'address.max'=>'* حداکثر طول آدرس 255 کارکتر',
                'postalcode.required'=>'* کدپستی الزامی است.',
                'postalcode.max'=>'* کدپستی 10 رقمی است.',
                'postalcode.min'=>'* کدپستی 10 رقمی است.',
                'postalcode.regex'=>'* کدپستی معتبر نیست.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $address = UserAddress::where('user_id',Auth('sanctum')->id())->first();
        if($address == null)
        {
            $address =  new UserAddress;
            $address->user_id = Auth::id();
            $address->province_id = $request->province_id;
            $address->city_id = $request->city_id;
            $address->part_id = $request->part_id;
            $address->postalcode = $request->postalcode;
            $address->address = $request->address;
            $address->save();

            //افزودن 3 امتیاز به کاربر
            $pointService = new PointService();
            $pointService->update(Auth::user(),3);
        }
        else
        {
            $address->province_id = $request->province_id;
            $address->city_id = $request->city_id;
            $address->part_id = $request->part_id;
            $address->postalcode = $request->postalcode;
            $address->address = $request->address;
            $address->save();
        }

        return response()->json(['message'=> "بروزرسانی انجام شد."],200);
    }

    public function bank()
    {
        $bank = UserBank::where('user_id',Auth('sanctum')->id())->first();

        return response()->json(['bank' =>$bank],200);
    }


    public  function bank_update(Request $request)
    {
        $validator = Validator::make(request()->all(),
            [
                'name'=>'required|max:255',
                'cart'=>'required|min:16|max:16|regex:/^[0-9]+$/',
                'account'=>'nullable|max:255|regex:/^[0-9]+$/',
                'shaba'=>'nullable|min:26|max:26|regex:/^[A-Z0-9]+$/',
            ] ,[
                'name.required'=>'* نام بانک است.',
                'name.max'=>'* حداکثر نام بانک 255 کارکتر',
                'cart.required'=>'* شماره کارت است.',
                'cart.min'=>'* شماره کارت 16 رقمی است.',
                'cart.max'=>'* شماره کارت 16 رقمی است.',
                'cart.regex'=>'* شماره کارت غیرمجاز است',
                'account.max'=>'* حداکثر شماره کارت 50 کارکتر.',
                'account.regex'=>'* شماره کارت غیرمجاز است',
                'shaba.min'=>'* شماره شبا 26 رقمی است.',
                'shaba.max'=>'* شماره شبا 26 رقمی است.',
                'shaba.regex'=>'* شماره کارت غیرمجاز است.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = Auth('sanctum')->user();
        $bank = UserBank::where('user_id',$user->id)->first();
        if($bank==null)
        {
            $bank = new UserBank;
            $bank->user_id = $user->id;
            $bank->name = $request->name;
            $bank->cart = $request->cart;
            $bank->account = $request->account;
            $bank->shaba = $request->shaba;
            $bank->save();

            //افزودن 3 امتیاز به کاربر
            $pointService = new PointService();
            $pointService->update($user,3);
        }
        else
        {
            $bank->name = $request->name;
            $bank->cart = $request->cart;
            $bank->account = $request->account;
            $bank->shaba = $request->shaba;
            $bank->save();
        }

        return response()->json(['message'=> "بروزرسانی انجام شد."],200);
    }


    public function other_info()
    {
        $otherInfo = UserInfo::where('user_id',Auth('sanctum')->id())->first();
        $jobs = Job::where('status',Status::Active)->orderBy('title','asc')->get();

        return response()->json(['otherInfo' => new OtherInfoResource($otherInfo),
                                'jobs'=> new JobCollection($jobs)],200);
    }


    public  function other_info_update(Request $request)
    {
        if(isset($request->maried))
        {
            $maried= true;
        }
        else
        {
            $maried= false;
        }

        if($maried && !isset($request->marriageDate))
        {
            Validator::extend('maried_validator',function(){return false;});
        }
        else
        {
            Validator::extend('maried_validator',function(){return true;});
        }

        $validator = Validator::make(request()->all(),
            [
                'job_id'=>'required|exists:jobs,id',
                'email'=>'required|max:255|email',
                'birthDate'=>'required',
                'marriageDate'=>'maried_validator',
            ], [
                'job_id.required'=>'دسته شغلی الزامی است.',
                'email.required'=>'* آدرس ایمیل است.',
                'email.max'=>'حداکثر طول آدرس ایمیل 255 کارکتر',
                'email.email'=>' آدرس ایمیل معتبر نیست.',
                'birthDate.required'=>'الزامی است.',
                'marriageDate.maried_validator'=>'تاریخ ازدواج الزامی است.',
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }


        $birthDate =  $this->fuctionService->faToEn($request->birthDate);
        $birthDate = Jalalian::fromFormat('Y/m/d', $birthDate)->toCarbon("Y-m-d");

        $marriageDate =null;
        if($maried && isset($request->marriageDate))
        {
            $marriageDate =  $this->fuctionService->faToEn($request->marriageDate);
            $marriageDate = Jalalian::fromFormat('Y/m/d', $marriageDate)->toCarbon("Y-m-d");
        }

        $user = Auth('sanctum')->user();

        $info = UserInfo::where('user_id',$user->id)->first();

        if($info==null)
        {
            $info = new UserInfo;
            $info->user_id = $user->id;
            $info->job_id  = $request->job_id;
            $info->email = $request->email;
            $info->birthDate = $birthDate;
            $info->married = $maried;
            $info->marriageDate = $marriageDate;
            $info->save();

            //افزودن 3 امتیاز به کاربر
            $pointService = new PointService();
            $pointService->update($user,3);
        }
        else
        {
            $info->job_id  = $request->job_id;
            $info->email = $request->email;
            $info->birthDate = $birthDate;
            $info->married = $maried;
            $info->marriageDate = $marriageDate;
            $info->save();
        }

        return response()->json(['message'=> "بروزرسانی انجام شد."],200);

    }

}

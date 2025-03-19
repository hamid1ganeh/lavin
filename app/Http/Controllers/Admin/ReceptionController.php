<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FoundStatus;
use App\Enums\genderType;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Reception;
use App\Models\ServiceReserve;
use App\Enums\ReserveStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\CodeService;

class ReceptionController extends Controller
{
     public function index()
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reception.index');

         $mobile = request('mobile');
         $code = request('code');
         $nationCode = request('nation_code');

         if((isset($mobile) && $mobile!='') || (isset($nationCode) && $nationCode!='') || (isset($code) && $code!='')){
             $receptions = Reception::orderBy('created_at','asc')->filter()->get();
         } else{
             $receptions = Reception::where('end',false)->orderBy('created_at','asc')->filter()->get();
         }

         return  view('admin.reception',compact('receptions'));
     }

    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reception.medical.document.create');

        $request->validate([
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'mobile' => 'required|min:11|max:11|regex:/^[0-9]+$/|unique:users',
                'nationcode' => 'required|min:10|max:10|regex:/^[0-9]+$/|unique:users',
                'gender' => 'required',
            ]
            ,
            [
                'firstname.required' => 'نام الزامی است.',
                'firstname.max' => 'حداکثر 255 کارکتر ',
                'lastname.required' => 'نام خانوادگی الزامی است.',
                'lastname.max' => '  *حداکثر 255 کارکتر ',
                'mobile.required' => ' شماره موبایل الزامی است.',
                'mobile.min' => ' فرمت صحیح موبایل  ********091 ',
                'mobile.max' => ' فرمت صحیح موبایل  ********091 ',
                'mobile.regex' => ' فرمت صحیح موبایل  ********091 ',
                'mobile.unique' => ' شماره موبایل قبلا ثبت شده است',
                'nationcode.required' => "کد ملی 10 رقمی را وارد کنید.",
                'nationcode.min' => " کد ملی 10 رقمی را وارد کنید.",
                'nationcode.max' => " کد ملی 10 رقمی را وارد کنید.",
                'nationcode.regex' => " کد ملی 10 رقمی را وارد کنید.",
                'nationcode.unique' => " این کدملی قبلا ثبت شده است .",
                'gender.required' => ' تعیین جنسیت الزامی است.',
            ]);

        if(in_array($request->gender,[genderType::female,genderType::male,genderType::LGBTQ])) {
            $verify_code = rand(1000, 9999);
            $verify_expire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
            $password = Str::random(6);
            $code = new CodeService();
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->mobile = $request->mobile;
            $user->nationcode = $request->nationcode;
            $user->verify_code = $verify_code;
            $user->verify_expire = $verify_expire;
            $user->gender = $request->gender;
            $user->code = $code->create($user, 10);
            $user->introduced = $request->introduced;
            $user->password =Hash::make($password);
            $user->save();

            $sms = new SMS;
            $msg = $user->firstname.' '.$user->lastname." عزیز \n".
                "ثبت نام شما در وبسایت کلینیک لاوین انجام شد.مشخصات ورود شما به صورت زیر است:\n".
                "وبسایت: ".env("APP_URL")."\n".
                "نام کاربری:".$user->mobile."\n".
                "رمز عبور:".$password."\n".
                "\nکلینیک لاوین رشت";

            $sms->send(array($user->mobile),$msg);

            toast('کاربر جدید افزوده شد.', 'success')->position('bottom-end');
            return redirect(route('admin.reserves.create',['user_id'=>$user->id]));
        }

        return back();
    }

     public function end(Reception $reception)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reception.end');

         $reserves = ServiceReserve::where('reception_id',$reception->id)->whereNotIn('status',[ReserveStatus::done,ReserveStatus::cancel])->exists();

         if($reserves){
             alert()->error('برای این کد مراجعه رزرو  انجام نشده وجود دارد', 'خطا');
             return back();
         }

         $upgrades = ServiceReserve::with('upgrades')->where('reception_id',$reception->id)
             ->WhereHas('upgrades',function ($q){
                 $q->where('status',ReserveStatus::waiting);
             })->exists();

         if($upgrades){
             alert()->error('برای این کد مراجعه  ارتقاء انجام نشده وجود دارد', 'خطا');
             return back();
         }

        $reception->end =true;
        $reception->endTime = Carbon::now('+3:30')->format('Y-m-d H:i:s');
        $reception->save();
        return back();
     }

    public function start(Reception $reception)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reception.start');

        $last = $reception->hasOpenReferCode();
        if($last){
            alert()->error(' برای این کاربر یک کد مراجعه باز با شماره '.$last->code.' وجود دارد ', 'خطا');
            return back();
        }
        $reception->end =false;
        $reception->endTime = null;
        $reception->save();
        return back();
    }

    public function found(Reception $reception)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reception.found.refer');

        $reception->found_status = FoundStatus::referred;
        $reception->save();
        toast('پذیرش مورد نظر به صندوق ارجاع داده شد.', 'success')->position('bottom-end');
        return back();
    }
}



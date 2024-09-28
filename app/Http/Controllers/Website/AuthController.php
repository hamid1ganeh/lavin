<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryPassAdmimMail;
use App\Services\SMS;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Validator;
use App\Models\User;
use App\Enums\Status;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Redirect;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public $maxAttempts = 5;
    public $decayMinutes = 3;

    public function username(){
        return 'email';
    }

    public function loginPage()
    {
        if (Auth::check())
        {
            return  redirect()->route('website.home');
        }

        return view('website.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile'=>'required',
            'password'=>'required'
        ],
        [
            'mobile.required'=>'شماره تماس را وارد کنید.',
            'password.required'=>'رمز عبور را وارد کنید.'
        ]);

        if(Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password], $request->get('remember')))
        {
            return  response()->json(['message'=>'ورود با موفقیت انجام شد.'],200);
        }

        //keep track of login attempts from the user.

        $this->incrementLoginAttempts($request);
        //Authentication failed

        return  response()->json(['message'=>'مشخصات ورود صحیح نمی باشد.'],401);

    }


    public function logout()
    {
        Auth::logout();
        return  back();
    }

    public function forgotPass()
    {
        return  view('website.forgotPass');
    }

    public function receive_opt_form()
    {
        return  view('website.auth.receive-otp');
    }

    public function receive_opt(Request $request)
    {

        $request->validate(
            ["mobile"=>['required','max:11','min:11']],
            ["mobile.required"=>"لطفا شماره موبایل خود را وارد کنید",
            "mobile.max"=>"فرمت صحیح موبایل 0911xxxxxxx",
            "mobile.min"=>"فرمت صحیح موبایل 0911xxxxxxx",]);

        $user = User::where('mobile',$request->mobile)->first();
        if($user===null)
        {
            Validator::extend('exists_user',function(){return false;});
        } else {
            Validator::extend('exists_user',function(){return true;});

        }

        $request->validate(
            ["mobile"=>['exists_user']],
            ["mobile.exists_user"=>"کاربری با شماره موبایل وارد شده وجود ندارد."]);


        if($user->verify_expire > Carbon::now()->format('Y-m-d H:i:s')){
            alert()->warning('هشدار','قبلا کد یکبار مصرف برای این شماره ارسال شده است.');
            return  view('website.auth.login-otp',compact('user'));
        }

        $verify_code = rand(1000,9999);
        $verify_expire = Carbon::now()->addMinute(2)->format('Y-m-d H:i:s');
        $user->verify_code = $verify_code;
        $user->verify_expire = $verify_expire;
        $user->save();

        $msg = "کد یکبار مصرف: ".$verify_code."\nاین کد بعد از 5 دقیقه منقضی خواهد شد.\nکلینیک لاوین رشت";
        $sms = new SMS;
        $sms->send(array($user->mobile),$msg);

        return  view('website.auth.login-otp',compact('user'));
    }

    public function login_opt(Request $request)
    {
        $request->validate(
            ["code"=>['required','max:4','min:4']],
            ["code.required"=>"لطفا کد یکبار مصرف 4 رقمی را وارد کنید",
                "code.max"=>"لطفا کد یکبار مصرف 4 رقمی را وارد کنید",
                "code.min"=>"لطفا کد یکبار مصرف 4 رقمی را وارد کنید",]);

           $user = User::where('mobile',$request->mobile)->first();

           if(is_null($user)){
               return back();
           }

           if($user->verify_code!=$request->code){
               Validator::extend('verify_code',function(){return false;});
           }else {
               Validator::extend('verify_code',function(){return true;});
           }
           $request->validate(["code"=>["verify_code"]],["code.verify_code"=>"کد یکبار مصرف صحیح نمی باشد."]);

        if($user->verify_expire > Carbon::now()->format('Y-m-d H:i:s')){
            Validator::extend('verify_expire',function(){return true;});
        }else {
            Validator::extend('verify_expire',function(){return false;});
        }
        $request->validate(["code"=>["verify_expire"]],["code.verify_expire"=>"کد یکبار مصرف منقضی شده است."]);

        $user->verified = true;
        $user->save();
        Auth::login($user);

        return redirect(route('website.account.dashboard'));
    }


    public function recoveyPass(Request $request)
    {
        $email = $request->email;

        $user = User::where('email',$email)->where('status',Status::Active)->first();
        if($user==null)
        {
            alert()->error('خطا','آدرس ایمیل وارد شده در سیستم ثبت نشده است');
            return back();
        }

        $token = Str::random(99);
        $user->remember_token = $token;
        $user->token_expire = Carbon::now()->addDay(1);
        $user->save();

        Mail::to($email)->send(new RecoveryPassAdmimMail($token));

        alert()->success('تبریک','لینک بازیابی به ایمیل شما ارسال شد.');

        return back();
    }

    public function changePass($token)
    {

        return  view('website.email.changePass',compact('token'));
    }

    public function changePassword(Request $request,$token)
    {
        $request->validate(
        [
            "password"=>['min:6'],
            "password_confirm"=>['min:6','required_with:password','same:password'],
        ],
        [
            "password.required"=>"رمز عبور الزامی است",
            "password.min"=>"طول رمز عبور باید حداقل 6  کارکتر باشد." ,
            "password_confirm.required"=>"تکرار رمز عبور الزامی است.",
            "password_confirm.min"=>"طول تکرار رمز عبور باید حداقل 6  کارکتر باشد." ,
            "password_confirm.same"=>"تکرار رمز عبور منطبق نمی باشد." ,
        ]);

        $user = User::where('remember_token',$token)->first();

        if($user==null || $user->token_expire < Carbon::now())
        {
            alert()->error('خطا','لینک بازیابی رمز عبور منقضی شده است. لطفا مجددا درخواست نمایید.');
            return back();
        }

        $token = Str::random(99);
        $user->remember_token = $token;
        $user->token_expire = Carbon::now()->addDay(1);
        $user->password =   Hash::make($request->password);
        $user->save();

        alert()->success('تبریک','رمز عبور شما با موفقیت تغییر یافت.');
        return back();

    }


}

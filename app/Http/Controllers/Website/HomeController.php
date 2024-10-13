<?php

namespace App\Http\Controllers\Website;

use App\Enums\genderType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\ArticleCategories;
use App\Models\Portfolio;
use App\Models\Admin;
use App\Models\Socialmedia;
use App\Models\Phone;
use App\Models\Message;
use App\Models\FAQ;
use App\Enums\Status;
use App\Enums\ArticleStatus;
use Carbon\Carbon;
use App\Models\Number;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\CodeService;


class HomeController extends Controller
{
     public function index()
     {
        $allservices = Service::with('details')->where('displayed',true)->where('status',Status::Active)->get();

        $doctors = Admin::with('doctor')
        ->whereHas('roles', function($q){$q->where('name', 'doctor');})
        ->orderBy('fullname','asc')
        ->paginate(10)
        ->withQueryString();

        $products = Product::with('thumbnail','images')->orderBy('created_at','desc')->where('status',Status::Active)->limit(5)->get();
        $articleCategories = ArticleCategories::with('articles.categories')->where('status',Status::Active)->
         whereHas('articles',function($q){
             $q->where('status',ArticleStatus::publish)->
             where('publishDateTime','<',Carbon::now()->format('Y-m-d H:i:s'))->orderBy('publishDateTime','desc');
         })->orderBy('name','asc')->get();


         $galleries = Gallery::with('images')->orderBy('created_at','desc')->where('status',Status::Active)->limit(9)->get();
         return view('index',compact('products','articleCategories','galleries','doctors','allservices'));
     }

     public function search()
     {
         $portfolios  = Portfolio::where('title','like','%'.request('search').'%')->orWhere('descriotion','like','%'.request('search').'%')->orderBy('created_at','desc')->get();
         $articles = Article::where('title','like','%'.request('search').'%')->orWhere('content','like','%'.request('search').'%')->orderBy('created_at','desc')->get();
         $doctors = Admin::with('doctor')->where('fullname','like','%'.request('search').'%')->whereHas('roles', function($q){$q->where('name', 'doctor');})->orderBy('fullname','asc')->get();
         $products = Product::where('name','like','%'.request('search').'%')->orWhere('description','like','%'.request('search').'%')->orderBy('created_at','desc')->get();
         return view('website.search',compact('articles','portfolios','doctors','products'));
     }

     public function about()
     {
         return view('website.about');
     }

     public function contact()
     {
         $socialmedias = Socialmedia::where('status',Status::Active)->get();
         $phones = Phone::where('status',Status::Active)->get();
         return view('website.contact',compact('socialmedias','phones'));
     }

     public function message(Request $request)
     {
        $request->validate([
            'fullname'=> 'nullable|max:255',
            'mobile'=> 'nullable|min:11|max:11|regex:/^[0-9]+$/',
            'content'=> 'required|max:63000',
        ],
        [
           'fullname.max'=>'* حداکثر 255 کارکتر.',
           'mobile.min'=>'*  شماره موبایل صحیح نیست.',
           'mobile.max'=>'*  شماره موبایل صحیح نیست.',
           'regex.max'=>'*  شماره موبایل صحیح نیست.',
           'content.required'=>'* محتوا پیام را وارد نمایید.',
           'content.max'=>'* حداکثر 63000 کارکتر.',

        ]);

        Message::create([
            'fullname'=>$request->fullname,
            'mobile'=>$request->mobile,
            'content'=>$request->content,
        ]);

        alert()->success('پیام شما ثبت شد.', 'تبریک');
        return back();

     }


     public function doctor($doctor)
     {
        $doctor = Admin::with('doctor','services')->find($doctor);
        return  view('website.doctors.show',compact('doctor'));
     }

     public function faq()
     {
         $faqs = FAQ::where('display',Status::Active)->orderBy('created_at','asc')->get();
         return  view('website.faq',compact('faqs'));
     }

     public function football()
     {
         return view('website.football');
     }

     public function football_register(Request $request)
     {
         $request->validate([
             'firstname'=> 'required|max:255',
             'lastname'=> 'required|max:255',
             'mobile'=> 'nullable|min:11|max:11|regex:/^[0-9]+$/|unique:numbers|unique:users',
             'nationcode'=>'required|min:10|max:10|regex:/^[0-9]+$/|unique:users',
             'g-recaptcha-response' => 'required|recaptcha'
         ],
         [
             'firstname.required'=>'نام و نام خانوادگی را وارد نمایید.',
             'firstname.max'=>'حداکثر طول نام و نام خانوادگی 255 کارکتر',
             'lastname.required'=>'نام و نام خانوادگی را وارد نمایید.',
             'lastname.max'=>'حداکثر طول نام و نام خانوادگی 255 کارکتر',
             'mobile.required'=>'شماره موبایل را وارد نمایید.',
             'mobile.unique'=>'این شماره موبایل قبلا ثبت شده است.',
             'mobile.min'=>'شماره موبایل صحیح نیست.',
             'mobile.max'=>' شماره موبایل صحیح نیست.',
             'mobile.regex'=>' شماره موبایل صحیح نیست.',
             'nationcode.required'=> " کد ملی 10 رقمی را وارد کنید.",
             'nationcode.min'=>  " کد ملی 10 رقمی را وارد کنید.",
             'nationcode.max'=> " کد ملی 10 رقمی را وارد کنید.",
             'nationcode.regex'=> " کد ملی 10 رقمی را وارد کنید.",
             'nationcode.unique'=> " این کدملی قبلا ثبت شده است .",
             'g-recaptcha-response.recaptcha' => 'ریکپچا گوکل معتبر نمیباشد.',
             'g-recaptcha-response.required' => 'لطفا ریکچا گوگل را ثبت کنید.'
         ]);

         if(in_array($request->gender,genderType::getGenderList)){

             $verify_code = rand(1000,9999);
             $verify_expire = Carbon::now("+3:30")->addMinute(5)->format('Y-m-d H:i:s');
             $password = Str::random(6);
             $code = new CodeService();

             $number = new Number();
             $number->firstname = $request->firstname;
             $number->lastname = $request->lastname;
             $number->mobile = $request->mobile;
             $number->festival_id = 8;
             $user = new User();
             $user->firstname = $number->firstname;
             $user->lastname = $number->lastname;
             $user->mobile = $number->mobile;
             $user->nationcode = $request ->nationcode;
             $user->verify_code = $verify_code;
             $user->verify_expire = $verify_expire;
             $user->gender = $request->gender;
             $user->code  = $code->create($user,10);
             $user->password =Hash::make($password);

             DB::transaction(function() use ($number,$user) {
                 $number->save();
                 $user->save();
             });

             toast('مشخصات شما ثبت شد.','success')->position('bottom-end');

         }
         return back();
     }
}

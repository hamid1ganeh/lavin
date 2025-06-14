<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Job;
use App\Models\Number;
use App\Models\Province;
use App\Services\SMS;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use App\Enums\genderType;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\CodeService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;
use App\Services\ExportService;

class UserController extends Controller
{
  private $code;
  public function __construct()
  {
      $this->code = new CodeService;
  }

  public function index()
  {

      //اجازه دسترسی
      if (Auth::guard('admin')->user()->can('users.index')) {

          $exel = request('exel');
          if(isset($exel) && $exel='on') {


              $users = User::with('level','address.province','address.city','address.part','bank','info.job')
                  ->withTrashed()
                  ->filter()
                  ->orderBy('created_at','desc')
                  ->get();

              $titles = "نام" . "\t" ."نام خانوادگی" . "\t" . "موبایل" . "\t"."شماره ملی". "\t"."شغل"."\t"."سطح". "\t"."امتیاز". "\t"."استان". "\t"."شهر". "\t"."بخش";

              $setData = '';
              $rowData = '';
              foreach ($users as $user) {
                  $firstname = $user->firstname ?? '';
                  $lastname = $user->lastname ?? '';
                  $mobile = $user->mobile ?? '';
                  $nationcode = $user->nationcode ?? '';
                  if (!is_null($user->info)){
                      $job = $user->info->job->title ?? '';
                  }else{
                      $job = '';
                  }

                  $level = $user->level->title ?? '';
                  $point = $user->point ?? '';
                  if (!is_null($user->address)) {
                      $province = $user->address->province->name ?? '';
                      $city = $user->address->city->name ?? '';
                      $part = $user->address->part->name ?? '';
                  } else{
                      $province = '';
                      $city = '';
                      $part = '';

                  }

                  $rowData .=  $firstname."\t";
                  $rowData .=  $lastname."\t";
                  $rowData .=  $mobile."\t";
                  $rowData .=  $nationcode."\t";
                  $rowData .=  $job."\t";
                  $rowData .=  $level."\t";
                  $rowData .=  $point."\t";
                  $rowData .=  $province."\t";
                  $rowData .=  $city."\t";
                  $rowData .=  $part."\t"."\n";
              }
              $setData .= $rowData. "\n";

              $filename = 'export_users_'.date('YmdHis') . ".xls";

              $export = new ExportService();
              $export->exel($titles,$setData,$filename);
          }


          $levels = Level::orderBy('point','asc')->get();

          $users = User::with('level','address.province','address.city','address.part','bank','info.job')
          ->withTrashed()
          ->filter()
          ->orderBy('created_at','desc')
          ->paginate(10)
          ->withQueryString();

          $jobs = Job::where('status',Status::Active)->orderBy('title','asc')->get();
          $provinces = Province::where('status',Status::Active)->orderBy('name','asc')->get();
          $cities = City::where('status',Status::Active)->orderBy('name','asc')->get();
          return view('admin.users.all',compact('users','levels','jobs','provinces','cities'));
      }

    abort(403);
  }


  public function create()
  {
      if (Auth::guard('admin')->user()->can('users.create') ||
          Auth::guard('admin')->user()->can('reception.medical.document') ) {
          $levels = Level::orderBy('point', 'asc')->get();
          return view('admin.users.create', compact('levels'));

      }
      abort(403);
  }

  public function store(Request $request)
  {
      //اجازه دسترسی
      if (Auth::guard('admin')->user()->can('users.create')) {
          $request->validate([
                  'firstname' => 'required|max:255',
                  'lastname' => 'required|max:255',
                  'mobile' => 'required|min:11|max:11|regex:/^[0-9]+$/|unique:users',
                  'nationcode' => 'required|min:10|max:10|regex:/^[0-9]+$/|unique:users',
                  'gender' => 'required',
                  'introduced' => 'nullable|exists:users,code',
              ]
              ,
              [
                  'firstname.required' => ' * الزامی است.',
                  'firstname.max' => '  *حداکثر 255 کارکتر ',
                  'lastname.required' => ' * الزامی است.',
                  'lastname.max' => '  *حداکثر 255 کارکتر ',
                  'mobile.required' => '* شماره موبایل الزامی است.',
                  'mobile.min' => ' فرمت صحیح موبایل  ********091 ',
                  'mobile.max' => ' فرمت صحیح موبایل  ********091 ',
                  'mobile.regex' => ' فرمت صحیح موبایل  ********091 ',
                  'mobile.unique' => ' شماره موبایل قبلا ثبت شده است',
                  'nationcode.required' => "* کد ملی 10 رقمی را وارد کنید.",
                  'nationcode.min' => "* کد ملی 10 رقمی را وارد کنید.",
                  'nationcode.max' => "* کد ملی 10 رقمی را وارد کنید.",
                  'nationcode.regex' => "* کد ملی 10 رقمی را وارد کنید.",
                  'nationcode.unique' => "* این کدملی قبلا ثبت شده است .",
                  'gender.required' => ' تعیین جنسیت الزامی است.',
                  'introduced.exists' => '* کد معرف صحیح نمی باشد.',
              ]);

          if(in_array($request->gender,[genderType::female,genderType::male,genderType::LGBTQ])) {
              $verify_code = rand(1000, 9999);
              $verify_expire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
              $password = Str::random(6);
              $user = new User;
              $user->firstname = $request->firstname;
              $user->lastname = $request->lastname;
              $user->mobile = $request->mobile;
              $user->nationcode = $request->nationcode;
              $user->verify_code = $verify_code;
              $user->verify_expire = $verify_expire;
              $user->gender = $request->gender;
              $user->code = $this->code->create($user, 10);
              $user->introduced = $request->introduced;
              $user->seller = isset($request->seller)?true:false;
              $user->password = $password;
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
          }

          return redirect(route('admin.users.index'));
      }
      abort(403);
  }

  public function edit(User $user)
  {
    //اجازه دسترسی
    config(['auth.defaults.guard' => 'admin']);
    $this->authorize('users.edit');
    $levels = Level::orderBy('point','asc')->get();
    return view('admin.users.edit',compact('levels','user'));
  }

  public function update(User $user,Request $request)
  {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('users.edit');

      $request->validate([
        'firstname'=>'required|max:255',
        'lastname'=>'required|max:255',
        'mobile'=>'required|min:11|max:11|regex:/^[0-9]+$/|unique:users,mobile,'.$user->id,
        'nationcode'=>'required|min:10|max:10|regex:/^[0-9]+$/|unique:users,nationcode,'.$user->id,
        'gender'=>'required',
        'level'=>'required|exists:levels,id',
        'introduced'=>'nullable|exists:users,code',
        "password"=>['nullable','max:255','min:6','confirmed','required_with:password_confirmation|same:password_confirmation'],
      ]
      ,
      [
        'firstname.required'=>' * الزامی است.',
        'firstname.max' => '  *حداکثر 255 کارکتر ',
        'lastname.required'=>' * الزامی است.',
        'lastname.max' => '  *حداکثر 255 کارکتر ',
        'mobile.required'=>'* شماره موبایل الزامی است.',
        'mobile.min'=>' فرمت صحیح موبایل  ********091 ',
        'mobile.max'=>' فرمت صحیح موبایل  ********091 ',
        'mobile.regex'=>' فرمت صحیح موبایل  ********091 ',
        'mobile.unique'=>' شماره موبایل قبلا ثبت شده است',
        'nationcode.required'=> "* کد ملی 10 رقمی را وارد کنید.",
        'nationcode.min'=>  "* کد ملی 10 رقمی را وارد کنید.",
        'nationcode.max'=> "* کد ملی 10 رقمی را وارد کنید.",
        'nationcode.regex'=> "* کد ملی 10 رقمی را وارد کنید.",
        'nationcode.unique'=> "* این کدملی قبلا ثبت شده است .",
        'introduced.exists'=>'* کد معرف صحیح نمی باشد.',
        'gender.required'=>' تعیین جنسیت الزامی است.',
        'password.required' => ' رمز عبور الزامی است.',
        'password.max' => ' حداکثر طول رمزعبور 255 کارکتر',
        'password.min' => ' حداقل طول رمزعبور 6 کارکتر',
        'password.confirmed' => ' تکرار رمز عبور منطبق نمی باشد ',
      ]);

      if($request->gender==genderType::female || $request->gender==genderType::male|| $request->gender==genderType::LGBTQ)
      {
          $number = Number::where("mobile",$user->mobile)->first();
          if($number==null){
              $number = new Number();
          }

          $number->firstname = $request->firstname;
          $number->lastname = $request->lastname;
          $number->mobile = $request->mobile;

         $user->firstname = $request->firstname;
         $user->lastname = $request->lastname;
         $user->nationcode = $request->nationcode;
         $user->level_id = $request->level;

        if($user->mobile != $request->mobile)
        {
          $user->verify_code = rand(1000,9999);
          $user->verify_expire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
          $user->verified =false;
          $user->mobile = $request->mobile;
        }

        $user->gender = $request->gender;
        $user->seller = isset($request->seller)?true:false;

        if($user->introduced != $request->introduced)
        {
          $user->introduced  = $this->code->create($user);
          $user->introduced = $request->introduced;
        }

        if(isset($request->password))
        {
          $user->password =Hash::make($request->password);
        }

      DB::transaction(function() use ($user,$number) {
          $user->save();
          $number->save();
      });

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
      }

      return redirect(route('admin.users.index'));
  }

  public function destroy(User $user)
  {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('users.destroy');

      $user->delete();
      toast(' کاربر مورد نظر حذف شد.','error')->position('bottom-end');
      return back();
  }

  public function recycle($id)
  {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('users.destroy');

      $service = User::withTrashed()->find($id);
      $service->restore();
      toast('کاربر مورد نظر بازیابی  شد.','error')->position('bottom-end');
      return back();
  }
  public function fetch()
  {
    $keyword = request('term');
    if(isset($keyword) && strlen($keyword)>2)
    {
      $users = User::where('firstname','like','%'.$keyword.'%')->orWhere('lastname','like','%'.$keyword.'%')->orWhere('mobile','like','%'.$keyword.'%')
      ->orWhere('nationcode','like','%'.$keyword.'%')->get();
    }
    else
    {
      return null;
    }

    return new UserCollection($users);
  }
}

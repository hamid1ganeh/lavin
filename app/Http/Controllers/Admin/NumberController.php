<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReviewGroupType;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Festival;
use App\Models\Job;
use App\Models\Province;
use App\Models\ReviewGroup;
use App\Models\UserAddress;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Models\Number;
use App\Models\PhoneOperatorHistory;
use App\Models\Admin;
use App\Models\User;
use App\Models\ServiceDetail;
use App\Models\Adviser;
use App\Models\AdviserHistory;
use App\Models\SmsHistory;
use App\Services\VideoService;
use App\Services\FunctionService;
use App\Services\ExportService;
use League\Csv\Reader;
use App\Enums\NumberStatus;
use App\Enums\genderType;
use App\Enums\Status;
use Carbon\Carbon;
use App\Services\SMS;
use App\Services\CodeService;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Auth;
use Morilog\Jalali\Jalalian;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\CityPart;

class NumberController extends Controller
{
    private $videoService;
    private $functionService;
    private $code;
    private $notification;


    public function __construct()
    {
        $this->videoService = new VideoService();
        $this->functionService = new FunctionService();
        $this->code = new CodeService;
        $this->notification = new NotificationService;
    }

     public function index()
     {
         //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.index');

        $roles = Auth::guard('admin')->user()->getRoles();
        if(in_array('adviser-operator',$roles))
        {
            $numbers = Number::with(['user','operators.admin','advisers','operator','suggestions'])
            ->where('operator_id',Auth::guard('admin')->id())
            ->orderBy('updated_at','desc')
            ->filter()
            ->paginate(10)
            ->withQueryString();


            $exel = request('exel');
            if(isset($exel) && $exel='on') {

                $numbers = Number::with(['user','operators.admin','operator','suggestions'])
                    ->where('operator_id',Auth::guard('admin')->id())
                    ->orderBy('updated_at','desc')->filter()->get();
                $titles = "نام" . "\t" . "نام خانوادگی" . "\t"."موبایل". "\t"."وضعیت". "\t"."اپراتور". "\t"."سایر اطلاعات";
                $setData = '';
                $rowData = '';
                foreach ($numbers as $number) {
                    $operator =  $number->operator->fullname  ?? '';
                    $rowData .=  $number->firstname . "\t";
                    $rowData .=  $number->lastname  . "\t";
                    $rowData .=  $number->mobile  . "\t";
                    $rowData .=  $number->getStatus()  . "\t";
                    $rowData .=  $operator. "\t";
                    $rowData .=  $number->info  . "\t". "\n";
                }
                $setData .= $rowData. "\n";
                $filename = 'export_numbers_'.date('YmdHis') . ".xls";
                $export = new ExportService();
                $export->exel($titles,$setData,$filename);

            }
        }
        else
        {
            $exel = request('exel');
            if(isset($exel) && $exel='on') {

                $numbers = Number::with(['user','operators.admin','operator','suggestions'])->orderBy('updated_at','desc')->filter()->get();
                $titles = "نام" . "\t" . "نام خانوادگی" . "\t"."موبایل". "\t"."وضعیت". "\t"."اپراتور". "\t"."سایر اطلاعات";
                $setData = '';
                $rowData = '';
                foreach ($numbers as $number) {
                    $operator =  $number->operator->fullname  ?? '';
                    $rowData .=  $number->firstname . "\t";
                    $rowData .=  $number->lastname  . "\t";
                    $rowData .=  $number->mobile  . "\t";
                    $rowData .=  $number->getStatus()  . "\t";
                    $rowData .=  $operator . "\t";
                    $rowData .=  $number->info  . "\t". "\n";
                }
                $setData .= $rowData. "\n";
                $filename = 'export_numbers_'.date('YmdHis') . ".xls";


                $export = new ExportService();
                $export->exel($titles,$setData,$filename);

            }

            $numbers = Number::with(['user','operators.admin','operator','suggestions'])
                ->orderBy('updated_at','desc')
                ->filter()
                ->paginate(50)
                ->withQueryString();

        }

        $operators = Admin::whereHas('roles', function($q){$q->where('name', 'adviser-operator');})->orderBy('fullname','asc')->get();
        $advisers = Admin::whereHas('roles', function($q){$q->where('name', 'adviser');})->orderBy('fullname','asc')->get();
        $servicesDetails = ServiceDetail::where('status',Status::Active)->orderBy('name','asc')->get();
        $festivals = Festival::where('status',Status::Active)->orderBy('title','asc')->get();


        return view("admin.numbers.all",compact('numbers','operators','advisers','servicesDetails','festivals'));
     }

     public function create()
     {
          //اجازه دسترسی
          config(['auth.defaults.guard' => 'admin']);
          $this->authorize('numbers.create');

          return view("admin.numbers.create");
     }

     public function store(Request $request)
     {
          //اجازه دسترسی
          config(['auth.defaults.guard' => 'admin']);
          $this->authorize('numbers.create');

          $request->validate([
               'user'=>'nullable|numeric|exists:users,id',
               'firstname'=> 'required|max:255',
               'lastname'=> 'required|max:255',
               'mobile'=>'required|min:11|max:11|regex:/^[0-9]+$/|unique:numbers',
               'type'=> 'required',
           ],[
               "firstname.required"=>"* نام الزامی است",
               "firstname.max"=>"*  حداکثر 255 کارکتر",
               "lastname.required"=>"* نام خانوادگی الزامی است",
               "lastname.max"=>"*  حداکثر 255 کارکتر",
               'mobile.required'=>'* شماره موبایل الزامی است.',
               'mobile.min'=>' فرمت صحیح موبایل  ********091 ',
               'mobile.max'=>' فرمت صحیح موبایل  ********091 ',
               'mobile.regex'=>' فرمت صحیح موبایل  ********091 ',
               'mobile.unique'=>' شماره موبایل قبلا ثبت شده است',
           ]);

           Number::create(["user_id" => $request->user,
                            "firstname" => $request->firstname,
                            "lastname" => $request->lastname,
                            "mobile" => $request->mobile,
                            "type"=> $request->type]);

          toast('شماره جدید ثبت شد.','success')->position('bottom-end');

          return back();
      }

     public function edit(Number $number)
     {
          //اجازه دسترسی
          config(['auth.defaults.guard' => 'admin']);
          $this->authorize('numbers.edit');
          return view("admin.numbers.edit",compact('number'));
     }


     public function update(Number $number,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('numbers.edit');

          $request->validate([
              'user'=>'nullable|numeric|exists:users,id',
               'firstname'=> 'required|max:255',
               'lastname'=> 'required|max:255',
               'mobile'=>'required|min:11|max:11|regex:/^[0-9]+$/|unique:numbers,mobile,'.$number->id,
               'type'=> 'required',
           ],[
                "firstname.required"=>"* نام الزامی است",
                "firstname.max"=>"*  حداکثر 255 کارکتر",
                "lastname.required"=>"* نام خانوادگی الزامی است",
                "lastname.max"=>"*  حداکثر 255 کارکتر",
                'mobile.required'=>'* شماره موبایل الزامی است.',
                'mobile.min'=>' فرمت صحیح موبایل  ********091 ',
                'mobile.max'=>' فرمت صحیح موبایل  ********091 ',
                'mobile.regex'=>' فرمت صحیح موبایل  ********091 ',
                'mobile.unique'=>' شماره موبایل قبلا ثبت شده است',
           ]);

          $user = $number->user_id;
          if (isset($request->user)){
              $user = $request->user;
          }
          if (isset($request->remove_user)){
              $user = null;

          }
           $number->update(["user_id" => $user,
                            "firstname" => $request->firstname,
                             "lastname" => $request->lastname,
                             "mobile" => $request->mobile,
                             "type"=> $request->type]);

          toast('شماره بروزرسانی شد.','success')->position('bottom-end');

          return redirect(route('admin.numbers.index'));
      }

      public function destroy(Number $number)
      {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('numbers.delete');

          $number->delete();
          return  back();
      }

      public function csv()
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.import');

        return view("admin.numbers.csv");
      }

      public function import(Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.import');

        $request->validate([
            'csv' =>'required|mimes:csv,txt',
        ],
        [
            'csv.required'=>'فایل CSV را ضمیمه کنید',
            "csv.mimes"=>"تنها فرمت CSV مجاز است",
        ]);

        $path = $this->videoService->path();
        $csv = $this->videoService->upload($request->csv,$path);

        $numbers = Reader::createFromPath(public_path($csv))->setHeaderOffset(0);

        foreach($numbers as $number)
        {
            $firstname = $number['firstname'];
            $lastname = $number['lastname'];
             $mobil =$this->functionService->faToEn($number['mobile']);

            if(isset($firstname) && isset($lastname) && isset($mobil))
            {
               if(strlen($mobil) == 11 && strlen($firstname) < 255  && strlen($lastname) < 255)
               {
                    if(! Number::where('mobile',$mobil)->exists())
                    {
                        Number::create(['firstname'=>$firstname,
                                        'lastname'=>$lastname,
                                        'mobile'=>$mobil]);
                    }
               }
            }
        }

       unlink($csv);

        alert()->success(' عملیات درون ریزی با موفقیت انجام شد.', 'تبریک');
        return redirect(route('admin.numbers.index'));
      }


      public function operator(Number $number,Request $request)
      {

        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.definition-operator');

          $request->validate([
              'operator' => "nullable|exists:admins,id"
          ],
          [
              "operator.required"=>"انتخاب اپراتور الزامی است."
          ]);

          if($request->operator !== null)
          {
            $status = NumberStatus::Operator;
          }
          else
          {
            $status = NumberStatus::NoAction;
          }

          $number->operator_id = $request->operator;
          $number->festival_id = $request->festival;
          $number->management_id = Auth::guard('admin')->id();
          $number->status =  $status;
          $number->operator_date_time = Carbon::now("+3:30");
          $number->save();

          $now = Carbon::now("+3:30");
          $last_operator = PhoneOperatorHistory::where([['number_id',$number->id],['until',null]])->first();
          if($last_operator !== null && $last_operator->admin_id != $request->operator)
          {
            $last_operator->until = $now;
            $last_operator->save();
          }


          if($request->operator !== null)
         {
            PhoneOperatorHistory::create(['admin_id'=>$request->operator,'number_id'=>$number->id,'festival_id'=> $request->festival]);
         }


         $number->suggestions()->sync($request->suggestion);

          return back();
      }

      public function referall(Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.definition-operator');

          $request->validate([
              'operator' => "nullable|exists:admins,id",
              'festival' => "nullable|exists:festivals,id",
              'numbers' => "required|array"
          ],
          [
              "numbers.required"=>"شماره های مورد نظر را انتخاب کنید."
          ]);

          $festival =null;
          if($request->operator !== null)
          {
            $status = NumberStatus::Operator;
             $management = Auth::guard('admin')->id();
              if($request->festival !== null){
                  $festival = $request->festival;
              }
          }
          else
          {
            $status = NumberStatus::NoAction;
            $management =null;
          }

         $now = Carbon::now("+3:30");
         DB::table('numbers')->whereIn('id',$request->numbers)->update([
            'operator_id'=> $request->operator,
            'management_id'=> $management,
            'status' => $status,
            'operator_date_time' => $now,
            'festival_id' => $festival
         ]);


        foreach ($request->numbers as $number)
        {
            $last_operator = PhoneOperatorHistory::where([['number_id',$number],['until',null]])->first();
            if($last_operator !== null && $last_operator->admin_id != $request->operator)
            {
              $last_operator->until = $now;
              $last_operator->save();
            }

            if($request->operator !== null && ($last_operator === null || $last_operator->admin_id != $request->operator))
            {
                PhoneOperatorHistory::create(['admin_id'=>$request->operator,'number_id'=>$number,'festival_id'=>$festival]);
            }
        }

        toast('ارجاع دسته جمعی انجام شد.','success')->position('bottom-end');

        return back();
      }

      public function update_oprator(Number $number,Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.operator-phone');

        if($request->status == NumberStatus::WaitingForAdviser && $request->services == null)
        {
          alert()->error('لطفا سرویس های مورد نظر جهت مشاوره را انتخاب کنید.','!خطا');
          return back();
        }
        else if($request->status == null)
        {
          alert()->error('لطفا وضعیت را مشخص کنید','خطا!');
          return back();
        }

        $request->validate([
            'information' => "nullable|max:65535",
            'operator_description' => "required|max:65535"
        ],
        [
            "information.max"=>"متن سایر اطلاعات مشتری طولانی است.",
            "operator_description.max"=>"متن توضیحات اپراتور خیلی طولانی است.",
            "operator_description.required"=>"متن توضیحات اپراتور الزامی است."
        ]);

        if($request->status == NumberStatus::Operator || $request->status == NumberStatus::NextNotice ||
           $request->status == NumberStatus::WaitingForAdviser || $request->status == NumberStatus::NoAnswer||
           $request->status == NumberStatus::Cancel)
           {
                if($request->status == NumberStatus::WaitingForAdviser && !$number->club())
                {
                    alert()->error('لطفا قبل از ارجاع به مشاور عضو باشگاه مشتریان شوید');
                }
                else
                {
                    $number->information = $request->information;
                    $number->operator_description = $request->operator_description;
                    $number->status = $request->status;

                    if($request->services != null)
                    {
                        foreach($request->services as $service)
                        {
                            Adviser::create(['number_id'=>$number->id,
                                             'management_id'=>$number->management_id,
                                             'operator_id'=>$number->operator_id,
                                             'service_id'=>$service]);
                        }
                        $number->status = NumberStatus::WaitingForAdviser ;
                    }
                    $history = PhoneOperatorHistory::where('admin_id',Auth::guard('admin')->id())->where('number_id',$number->id)->orderBy('created_at','desc')->first();
                    $history->description = $request->operator_description;

                    DB::transaction(function() use ($number, $history) {
                        $number->save();
                        $history->save();
                    });

                }
           }

           return back();
      }


      public function sms(Number $number,Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.sms');

        $request->validate([
            'text' => "required|max:140",
            'link' => "nullable",
        ],
        [
            "text.max"=>"حداکثر طول پیامک 140 کارکتر",
            "text.required"=>"متن پیامک الزامی است."]);

            $sms = new SMS;

            $msg = $number->firstname.' '.$number->lastname." عزیز \n".
            $request->text."\n".$request->link."\n\nکلینیک لاوین رشت";
            $sms->send(array($number->mobile),$msg);

            SmsHistory::create(['content'=>$msg,
                                'mobile'=>$number->mobile,
                                'admin_id'=>Auth::guard('admin')->id()]);


          toast('پیامک ارسال شد.','success')->position('bottom-end');
          return back();

      }

      public function register(Number $number,Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.add2club');

        $request->validate([
            'nationcode'=>'required|min:10|max:10|regex:/^[0-9]+$/|unique:users'],
            ['nationcode.required'=> "کد ملی 10 رقمی را وارد کنید.",
            'nationcode.min'=>  "کد ملی 10 رقمی را وارد کنید.",
            'nationcode.max'=> "کد ملی 10 رقمی را وارد کنید.",
            'nationcode.regex'=> "کد ملی 10 رقمی را وارد کنید.",
            'nationcode.unique'=> "این کدملی قبلا ثبت شده است ."]);

            if($request->gender == genderType::male || $request->gender == genderType::female || $request->gender == genderType::LGBTQ)
            {
                if(! User::where('mobile',$number->mobile)->exists())
                {
                    $verify_code = rand(1000,9999);
                    $verify_expire = Carbon::now("+3:30")->addMinute(5)->format('Y-m-d H:i:s');
                    $password = Str::random(6);
                    $user = new User;
                    $user->firstname = $number->firstname;
                    $user->lastname = $number->lastname;
                    $user->mobile = $number->mobile;
                    $user->nationcode = $request ->nationcode;
                    $user->verify_code = $verify_code;
                    $user->verify_expire = $verify_expire;
                    $user->gender = $request->gender;
                    $user->code  = $this->code->create($user,10);
                    $user->password =Hash::make($password);
                    $user->save();

                    $sms = new SMS;
                    $msg = $number->firstname.' '.$number->lastname." عزیز \n".
                    "ثبت نام شما در وبسایت کلینیک لاوین انجام شد.مشخصات ورود شما به صورت زیر است:\n".
                    "وبسایت: ".env("APP_URL")."\n".
                    "نام کاربری:".$number->mobile."\n".
                    "رمز عبور:".$password."\n".
                    "\nکلینیک لاوین رشت";

                    $sms->send(array($number->mobile),$msg);

                    $this->notification->send("عضویت در باشگاه مشتریان",$msg,Status::Active, array($user->id),'user');
                }
            }
            return back();
      }


      public function advisers(Number $number)
      {
          //اجازه دسترسی
          config(['auth.defaults.guard' => 'admin']);
          $this->authorize('numbers.definition-adviser');

            $advisers = Adviser::with(
                'service.advisers',
                'service.documents',
                'service.doctors',
                'adviser',
                'advisers.admin',
                'review')->where('number_id',$number->id)->get();

           $reviewGroups = ReviewGroup::where('type',ReviewGroupType::Adviser)->where('status',Status::Active)->get();

        return view('admin.numbers.advisers',compact('advisers','number','reviewGroups'));
      }

      public function set_adviser(Number $number,Adviser $adviser,Request $request)
      {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.definition-adviser');

          $request->validate([
              'adviser' => "nullable|exists:admins,id"
          ]);


            if($request->adviser==null)
            {
              $adviser->adviser_date_time =null;
              $adviser->status = NumberStatus::WaitingForAdviser;
            }
            else
            {
              $adviser->adviser_date_time = Carbon::now("+3:30");
              $adviser->status = NumberStatus::Adviser;
            }
            $adviser->adviser_id = $request->adviser;
            $adviser->festival_id = $number->festival_id;
            $adviser->save();

            if(Adviser::where([['number_id',$number->id],['status',NumberStatus::WaitingForAdviser]])->exists())
            {
              $number->status = NumberStatus::WaitingForAdviser;
            }
            else
            {
              $number->status = NumberStatus::Adviser;
            }
            $number->save();

            $now = Carbon::now("+3:30");
            $last_adviser = AdviserHistory::where([['adviser_id',$adviser->id],['until',null]])->first();
            if($last_adviser !== null)
            {
              $last_adviser->until = $now;
              $last_adviser->save();
            }

            if($request->adviser !== null)
            {
                AdviserHistory::create(['admin_id'=>$request->adviser,'adviser_id'=>$adviser->id,'festival_id'=>$number->festival_id]);

//                if ($adviser->status == NumberStatus::Adviser) {
//                    $sms = new SMS;
//                    $msg = $number->firstname . ' ' . $number->lastname . " عزیز \n".
//                        "شماره تماس شما جهت ارايه مشاوره تخصصی \n" . $adviser->service->name . " به مشاوره ارجاع داده شده است.".
//                        "\nمشاوررین ما به زودی با شما تماس خواهند گرفت.".
//                        "\nکلینیک لاوین رشت";
//                    $sms->send(array($number->mobile), $msg);
//                }
            }


          return back();
      }

    public function showInfo(Number $number)
    {
        $user =$number->getUserByMobile();
        if($user == null){
             alert()->warning("این شماره عضو باشگاه مشتریان نیست.");
            return back();
        }
        $user =$number->getUserByMobile();
        $provinces = Province::where('status',Status::Active)->orderBy('name','asc')->get();
        $jobs = Job::where('status',Status::Active)->orderBy('title','asc')->get();
        $address = UserAddress::where('user_id',$user->id)->first();
        $info = UserInfo::where('user_id',$user->id)->first();
        $parts = [];
        if(!is_null(old('part_id'))){
            $parts =  CityPart::where('city_id',old('city_id'))->where('status',Status::Active)->orderBy('name','asc')->get();
        }elseIf(!is_null($address)){
            $parts =  CityPart::where('city_id',$address->city_id)->where('status',Status::Active)->orderBy('name','asc')->get();
        }

        $areas = [];
        if(!is_null(old('area_id'))){
            $areas =  Area::where('part_id',old('area_id'))->where('status',Status::Active)->orderBy('name','asc')->get();
        }elseIf(!is_null($address)){
            $areas =  Area::where('part_id',$address->area_id)->where('status',Status::Active)->orderBy('name','asc')->get();
        }


      return view('admin.numbers.info',compact('number','user','provinces','address','jobs','info','parts','areas'));
    }

    public function updateInfo(Number $number,Request $request)
    {
        if(isset($request->maried))
        {
            $maried= true;
        }
        else
        {
            $maried= false;
        }

        $request->validate([
                'firstname'=> 'required|max:255',
                'lastname'=> 'required|max:255',
                'job_id'=>'exists:jobs,id|nullable',
                'email'=>'max:255|email|nullable',
                'birthDate'=>'nullable',
                'nationcode'=>'nullable|max:10,|regex:/^[0-9]+$/',
                'province_id'=>'nullable|exists:provinces,id',
                'city_id'=>'nullable|exists:cities,id',
                'part_id'=>'nullable|exists:city_parts,id',
                'area_id'=>'nullable|exists:areas,id',
                'address'=>'nullable|max:255',
                'postalcode'=>'nullable|max:10|min:10|regex:/^[0-9]+$/',
            ]
            ,
            [
                'firstname.required'=>' نام الزامی است.',
                'firstname.max'=>'حداکثر طول  نام 255 کارکتر',
                'lastname.required'=>' نام خانوادگی الزامی است.',
                'lastname.max'=>'حداکثر طول نام خانوادکی 255 کارکتر',
                'email.required'=>' آدرس ایمیل الزامی است.',
                'email.max'=>'حداکثر طول آدرس ایمیل 255 کارکتر',
                'email.email'=>'آدرس ایمیل معتبر نیست.',
                'nationcode.required'=> " کد ملی  را وارد کنید.",
                'address.max'=>' حداکثر طول آدرس 255 کارکتر',
                'postalcode.max'=>'کدپستی 10 رقمی است.',
                'postalcode.min'=>' کدپستی 10 رقمی است.',
                'postalcode.regex'=>' کدپستی معتبر نیست.'
            ]);


        $birthDate =null;
        if($request->birthDate){
            $birthDate =  $this->functionService->faToEn($request->birthDate);
            $birthDate = Jalalian::fromFormat('Y/m/d', $birthDate)->toCarbon("Y-m-d");
        }

        $marriageDate =null;
        if($maried && isset($request->marriageDate))
        {
            $marriageDate =  $this->functionService->faToEn($request->marriageDate);
            $marriageDate = Jalalian::fromFormat('Y/m/d', $marriageDate)->toCarbon("Y-m-d");
        }
        if($request->gender==genderType::female || $request->gender==genderType::male || $request->gender==genderType::LGBTQ)
        {
            $gender = $request->gender;
        }

        $user =$number->getUserByMobile();
        $info = UserInfo::where('user_id',$user->id)->first();

        $number->firstname = $request->firstname;
        $number->lastname = $request->lastname;
        $number->save();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->nationcode = $request->nationcode;
        $user->gender = $request->gender;
        $user->save();


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

        $address = UserAddress::where('user_id',$user->id)->first();
        if($address == null)
        {
            $address =  new UserAddress;
            $address->user_id =$user->id;
            $address->province_id = $request->province_id;
            $address->city_id = $request->city_id;
            $address->part_id = $request->part_id;
            $address->area_id = $request->area_id;
            $address->postalcode = $request->postalcode;
            $address->address = $request->address;
            $address->save();
        }
        else
        {
            $address->province_id = $request->province_id;
            $address->city_id = $request->city_id;
            $address->part_id = $request->part_id;
            $address->area_id = $request->area_id;
            $address->postalcode = $request->postalcode;
            $address->address = $request->address;
            $address->save();
        }

        toast('سایر مشخصات کاربر ثبت شد.','success')->position('bottom-end');
        return redirect(route('admin.numbers.index'));
    }
    public function  operators_history()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.history.operators');

        $exel = request('exel');
        if(isset($exel) && $exel='on') {

            $histories = PhoneOperatorHistory::with('number', 'admin')
                ->orderBy('created_at', 'desc')
                ->filter()
                ->get();

            $titles = "اپراتور" . "\t" . "توضیحات اپراتور" . "\t" . "نام نام خانوادگی" . "\t" . "موبایل" . "\t" . "از" . "\t" . "تا";
            $setData = '';
            $rowData = '';
            foreach ($histories as $history) {
                $operator = $history->admin->fullname ?? "";
                $desc =    $history->description ?? "";
                $fullname =    $history->number->firstaneme ?? ""." ".$history->number->lastname ?? "";
                $mobile = $history->number->mobile ?? "";
                $since = $history->since();
                $until = $history->until();
                $rowData .= $operator . "\t";
                $rowData .= $desc . "\t";
                $rowData .= $fullname. "\t";
                $rowData .= $mobile. "\t";
                $rowData .= $since . "\t";
                $rowData .= $until . "\t". "\n";
            }
            $setData .= $rowData . "\n";
            $filename = 'export_operator_history_' . date('YmdHis') . ".xls";

            $export = new ExportService();
            $export->exel($titles, $setData, $filename);
        }

        $histories = PhoneOperatorHistory::with('number','admin')
            ->orderBy('created_at','desc')
            ->filter()
            ->paginate(50)
            ->withQueryString();

        $operators = Admin::whereHas('roles', function($q){$q->where('name', 'adviser-operator');})->orderBy('fullname','asc')->get();
        return view('admin.numbers.operator-history',compact('operators','histories'));

    }

    public function advisers_history()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('numbers.history.advisers');

        $exel = request('exel');
        if(isset($exel) && $exel='on') {

            $histories = AdviserHistory::with('adviser.number','admin')
                ->orderBy('created_at','desc')
                ->filter()
                ->get();

            $titles = "مشاور" . "\t" . "توضیحات مشاور" . "\t" . "نام نام خانوادگی" . "\t" . "موبایل" . "\t" . "از" . "\t" . "تا";
            $setData = '';
            $rowData = '';
            foreach ($histories as $history) {
                $operator = $history->admin->fullname ?? "";
                $desc =    $history->description;
                $firstname = $history->adviser->number->firstname??'';
                $lastname = $history->adviser->number->lastname??'';
                $fullname =  $firstname." ".$lastname;
                $mobile = $history->adviser->number->mobile ?? "";
                $since = $history->since();
                $until = $history->until();
                $rowData .= $operator . "\t";
                $rowData .= $desc . "\t";
                $rowData .= $fullname. "\t";
                $rowData .= $mobile. "\t";
                $rowData .= $since . "\t";
                $rowData .= $until . "\t". "\n";
            }
            $setData .= $rowData . "\n";
            $filename = 'export_adviser_history_' . date('YmdHis') . ".xls";

            $export = new ExportService();
            $export->exel($titles, $setData, $filename);
        }

        $histories = AdviserHistory::with('adviser.number','admin')
            ->orderBy('created_at','desc')
            ->filter()
            ->paginate(50)
            ->withQueryString();

        $advisers = Admin::whereHas('roles', function($q){$q->where('name', 'adviser');})->orderBy('fullname','asc')->get();
        return view('admin.numbers.advisers-history',compact('advisers','histories'));

    }
}

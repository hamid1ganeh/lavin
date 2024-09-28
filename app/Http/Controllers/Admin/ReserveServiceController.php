<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NumberType;
use App\Http\Controllers\Controller;
use App\Models\Adviser;
use App\Models\AdviserHistory;
use App\Models\Branch;
use App\Models\Number;
use App\Models\Poll;
use App\Models\Reception;
use App\Models\ReserveUpgrade;
use App\Models\User;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceReserve;
use App\Models\ReservePayment;
use App\Enums\ReserveStatus;
use App\Enums\Status;
use App\Enums\PayWay;
use App\Enums\PaymentStatus;
use App\Enums\NumberStatus;
use App\Enums\ReserveType;
use App\Models\Admin;
use App\Models\ServiceDetail;
use App\Services\FunctionService;
use App\Services\ReserveService;
use App\Services\CodeService;
use Illuminate\Support\Facades\DB;
use \Morilog\Jalali\Jalalian;
use App\Services\DiscountService;
use App\Services\SMS;
use App\Services\PointService;
use Auth;

class ReserveServiceController extends Controller
{
    private $fuctionService;
    private $reserveService;

    public function __construct()
    {
        $this->reserveService = new ReserveService();
        $this->fuctionService = new FunctionService();
    }


    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.index');

        $exel = request('exel');
        if(isset($exel) && $exel='on') {
            $reserves = ServiceReserve::with('user','doctor','adviser.operator','adviser.adviser','adviser.arrangement','adviser.management')
                ->filter()
                ->orderBy('created_at','desc')
                ->get();

            $titles = "کاربر" . "\t" ."شعبه" . "\t" . "موبایل" . "\t"."سرویس". "\t"."جزئیات"."\t"."پزشک".
                 "\t"."زمان رزرو". "\t"."نوبت". "\t"."زمان اجرا". "\t"."مدت زمان اجرا". "\t"." مدیر مشاوره". "\t"."اپراتور".
                "\t"."مشاور". "\t"."ارنجمنت". "\t"."نوع رزرو". "\t"."وضعیت";

            $setData = '';
            $rowData = '';
            foreach ($reserves as $reserve) {
                $fullname = $reserve->user->getFullName()??'';
                $branch = $reserve->branch->name??'';
                $mobile = $reserve->user->mobile??'';
                $serviceName = $reserve->service_name??'';
                $detailName = $reserve->detail_name??'';
                $doctor = $reserve->doctor->fullname??'';
                $reserveTime = $reserve->reserve_time()??'';
                $roundTime = $reserve->round_time()??'';
                $doneTime = $reserve->done_time()??'';
                $duration = $reserve->duration()??'';
                $management = $reserve->adviser->management->fullname??'';
                $operator = $reserve->adviser->operator->fullname??'';
                $adviser = $reserve->adviser->adviser->fullname??'';
                $arrangement = $reserve->adviser->arrangement->fullname??'';
                $type = $reserve->getType()??'';
                $status = $reserve->getStatus()??'';

                $rowData .=  $fullname."\t";
                $rowData .=  $branch."\t";
                $rowData .=  $mobile."\t";
                $rowData .=  $serviceName."\t";
                $rowData .=  $detailName."\t";
                $rowData .=  $doctor."\t";
                $rowData .=  $reserveTime."\t";
                $rowData .=  $roundTime."\t";
                $rowData .=  $doneTime."\t";
                $rowData .=  $duration."\t";
                $rowData .=  $management."\t";
                $rowData .=  $operator."\t";
                $rowData .=  $adviser."\t";
                $rowData .=  $arrangement."\t";
                $rowData .=  $type."\t";
                $rowData .=  $status."\t"."\n";
            }
            $setData .= $rowData. "\n";

            $filename = 'export_reserves_'.date('YmdHis') . ".xls";

            $export = new ExportService();
            $export->exel($titles,$setData,$filename);
        }

        $doctors = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})->orderBy('fullname','asc')->get();
        $secretaries = Admin::whereHas('roles', function($q){$q->where('name', 'secretary');})->orderBy('fullname','asc')->get();
        $asistants = Admin::whereHas('roles', function($q){$q->where('name', 'asistant1');})->orderBy('fullname','asc')->get();
        $serviceDetails= ServiceDetail::orderBy('name','asc')->get();
        $branches= Branch::orderBy('name','asc')->get();
        $receptions = Admin::whereHas('roles', function($q){$q->where('name', 'reception');})->orderBy('fullname','asc')->get();

        $roles = Auth::guard('admin')->user()->getRoles();
        if(count($roles)==1 && in_array('secretary',$roles)) {
            $reserves = ServiceReserve::with('user','branch', 'doctor', 'review','adviser.number.operators.admin','adviser.advisers.admin','reception')
                ->where('secratry_id',Auth::guard('admin')->id())
                ->filter()
                ->orderBy('created_at', 'desc')
                ->paginate(30)
                ->withQueryString();
        }else {
            $reserves = ServiceReserve::with('user','branch', 'doctor', 'review','adviser.number.operators.admin','adviser.advisers.admin','reception')
                ->filter()
                ->orderBy('created_at', 'desc')
                ->paginate(30)
                ->withQueryString();
        }

        return view('admin.reserves.all',compact('reserves','serviceDetails','doctors','secretaries','asistants','branches','receptions'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.create');

        $services = Service::where('status',Status::Active)->whereHas('details')->orderBy('name','asc')->get();
        return view('admin.reserves.create',compact('services'));
    }

    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.create');


        $request->validate([
            'user'=>'required|numeric|exists:users,id',
            'service'=>'required|numeric',
            'detail'=>'required|numeric',
            'branch'=>'required|numeric|exists:branchs,id',
            'doctor'=>'required|numeric|exists:admins,id',
            'adviser'=>'nullable|numeric',
            'reception_desc'=>'nullable|max:500'
        ],
        [
            'user.required'=>'* الزامی است.',
            'user.numric'=>'* معتبر نیست.',
            'service.required'=>'* الزامی است.',
            'service.numric'=>'* معتبر نیست.',
            'detail.required'=>'* الزامی است.',
            'detail.numric'=>'* معتبر نیست.',
            'branch.required'=>'* الزامی است.',
            'branch.numric'=>'* معتبر نیست.',
            'doctor.required'=>'* الزامی است.',
            'doctor.numric'=>'* معتبر نیست.',
            'numeric.numric'=>'* معتبر نیست.',
            'reception_desc.max'=>'* متن طولانی است.',
        ]);

        $adviser =null;
        $status = ReserveStatus::waiting;
        if(isset($request->adviser)){
             $user = User::find($request->user);
            $status = ReserveStatus::wittingForAdviser;
            $number =  Number::where('mobile',$user->mobile)->first();

            if ($number==null){
                $number =  Number::where('mobile',$user->mobile)->firstOrCreate([
                    'firstname'=>$user->firstname,
                    'lastname'=>$user->lastname,
                    'mobile'=> $user->mobile,
                    'status'=> NumberStatus::Adviser,
                    'type'=> NumberType::hozoori,
                ]);
            }else{
                $number->status =NumberStatus::Adviser;
            }

             $adviser = new Adviser();
             $adviser->number_id =  $number->id;
             $adviser->service_id =  $request->detail;
             $adviser->adviser_id = $request->adviser;
             $adviser->status =  NumberStatus::Adviser;
             $adviser->adviser_date_time = Carbon::now();

            DB::transaction(function() use ($adviser,$number) {
                $number->save();
                $adviser->save();
            });

            $adviserHistory = new AdviserHistory();
            $adviserHistory->admin_id = $request->adviser;
            $adviserHistory->adviser_id = $adviser->id;
            $adviserHistory->save();

        }

        $this->reserveService->reserve($request->branch,
                                        ReserveType::inPerson,
                                       $request->user,
                                       $request->service,
                                       $request->detail,
                                       $request->doctor,
                                        $adviser->id??$adviser,
                                       $request->reception_desc,
                                       $status,
                                        null);

        toast('رزرو جدید ثبت شد.','success')->position('bottom-end');

        return redirect(route('admin.reserves.index'));

    }

    public function edit(ServiceReserve $reserve)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.edit');

        $services = Service::where('status',Status::Active)->whereHas('details')->orderBy('name','asc')->get();
        $details = ServiceDetail::where('status',Status::Active)->where('service_id',$reserve->service_id)->orderBy('name','asc')->get();
        $selected = ServiceDetail::with('doctors','branches','advisers')->find($reserve->detail_id);

        return view('admin.reserves.edit',compact('reserve','services','details','selected'));
    }

    public function show(ServiceReserve $reserve)
    {
         //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.determining');


        return view('admin.reserves.show',compact('reserve'));
    }

    public function determining(ServiceReserve $reserve,Request $request)
    {
         //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.determining');

        if(in_array($request->status,ReserveStatus::getReseveStatus))
        {
            if($reserve->adviser_id != null){
                $adviser = Adviser::with('number')->find($reserve->adviser_id);
                if(($request->status == ReserveStatus::accept ||
                    $request->status == ReserveStatus::done) &&
                    ($adviser->status != NumberStatus::Reservicd &&
                        $adviser->status != NumberStatus::Confirm)){
                    alert()->error('خطا','مشاوره انجام نشده است.');
                    return back();
                }
            }

            if(!isset($request->time) && (in_array($request->status,[ReserveStatus::confirm,ReserveStatus::done])))
            {
                alert()->error('خطا','جهت تایید رزور زمان مراجعه را مشخص نمایید.');
                return back();
            }
            else if(isset($request->time))
            {
                $time =  $this->fuctionService->faToEn($request->time);
                $time = Jalalian::fromFormat('Y/m/d H:i', $time)->toCarbon("Y-m-d H:i");
            }
            else
            {
                $time = null;
            }

            $reserve->time = $time;
            $reserve->status = $request->status;
            if($request->status == ReserveStatus::accept){
                $last = Reception::where('user_id',$reserve->user_id)
                    ->where('end',false)
                    ->first();

                $reserve->time = Carbon::now("+3:30")->format('Y-m-d H:i:s');

                if(is_null($last)){
                    $codeService = new CodeService();
                    $reception = new Reception();
                    $code = $codeService->refer_code(get_class($reception));

                    $reception->code = $code;
                    $reception->reception_id = Auth::guard('admin')->id();
                    $reception->user_id = $reserve->user_id;
                    $reception->save();
                    $reserve->reception_id = $reception->id;
                }else{
                    $reserve->reception_id = $last->id;
                }
            }

            if ($reserve->adviser_id !== null){
                $number = $adviser->number;
                if($reserve->status == ReserveStatus::done){
                    $adviser->status = NumberStatus::Done;
                    $number->status= NumberStatus::Done;
                }else if($reserve->status == ReserveStatus::confirm){
                    $adviser->status = NumberStatus::Confirm;
                    $number->status= NumberStatus::Confirm;
                }
                else if($reserve->status == ReserveStatus::cancel){
                    $adviser->status = NumberStatus::Cancel;
                    $number->status= NumberStatus::Cancel;
                }
                else if($reserve->status == ReserveStatus::waiting){
                    $adviser->status = NumberStatus::Reservicd;
                    $number->status= NumberStatus::Reservicd;
                }

                DB::transaction(function() use ($reserve, $adviser,$number) {
                    $reserve->save();
                    $adviser->save();
                    $number->save();
                });
            }else {
                $reserve->save();
            }

            //ارسال sms
//             $sms = new SMS;
//            if($reserve->status == ReserveStatus::confirm && $reserve->time!=null)
//            {
//                $msg = $reserve->user->firstname.' '.$reserve->user->lastname." عزیز \n".
//                "سرویس رزرو شده شما مورد تایید قرار گرفت.\nجهت پرداخت آنلاین به حساب کاربری خود مراجمعه نمایید.\nزمان مراجعه شما به کلینیک:\n".
//                en2fa($request->time)."\n\nکلینیک لاوین رشت";
//                $sms->send(array($reserve->user->mobile),$msg);
//            }
//            else if($reserve->status == ReserveStatus::cancel)
//            {
//                $msg = $reserve->user->firstname.' '.$reserve->user->lastname." عزیز \n".
//                "سرویس رزرو شده شما لغو گردید.\n\nکلینیک لاوین رشت";
//                $sms->send(array($reserve->user->mobile),$msg);
//            }
//            else if($reserve->status == ReserveStatus::done)
//            {
//                $msg = $reserve->user->firstname.' '.$reserve->user->lastname." عزیز \n ضمن تشکر از اعتماد شما،".
//                "لطفا جهت شرکت در نظرسنجی در خصوص سرویس دریافتی به حساب کاربری خود مراجعه نمایید.\n\nکلینیک لاوین رشت";
//                $sms->send(array($reserve->user->mobile),$msg);
//            }

            toast('تعیین وضعیت انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.reserves.index'));

    }

    public function payment(ServiceReserve $reserve)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment');

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

        return view('admin.reserves.payment',compact('payement'));
    }

     public function pay(ReservePayment $payment,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.pay');

        $request->validate([
            'res_code'=>'required'
        ],[
            'res_code.required'=>'* شناسه پرداخت را وارد نمایید.'
        ]);

        $offer =0;
        $discount_id = null;
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
        $payment->total_price = $payment->price-$offer;
        $payment->discount_id = $discount_id;
        $payment->ref_id = $request->res_code;
        $payment->res_code = $request->res_code;
        $payment->payway = PayWay::cash;
        $payment->status = PaymentStatus::paid;
        $payment->save();


        //بروزرسانی امتیاز کاربر
        $detail = $payment->reserve->detail;
        $pointService = new  PointService;
        $pointService->point($payment->user_id,$detail);

        return redirect(route('admin.reserves.index'));
    }


    public function secratry(ServiceReserve $reserve,Request $request)
    {
        $request->validate([
            'secratry' => "required|exists:admins,id"
        ],
        [
            "secratry.required"=>"انتخاب منشی الزامی است."
        ]);

        $reserve->secratry_id = $request->secratry;
        $reserve->status = reserveStatus::secratry;
        $reserve->save();

        return back();
    }

    public function done(ServiceReserve $reserve,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.done');

        $request->validate(
        ["asistant"=> "required|exists:admins,id"],
        ["required"=>"انجام دهنده کار را مشخص کنید."]);



        $now = Carbon::now('+3:30')->format('Y-m-d H:i:s');
//        if($reserve->time > $now){
//            alert()->error('خطا','نوبت اجرا فرا نرسیده است.');
//            return back();
//        }

        $reserve->doneTime = $now;
        $reserve->asistant_id = $request->asistant;
        $reserve->status = ReserveStatus::done;
        $reserve->save();

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
        $poll->user_id =$reserve->user_id;
        $poll->admin_id = Auth::guard('admin')->id();
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

    public function upgrades()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.upgrades');

        $exel = request('exel');
        if(isset($exel) && $exel='on') {

            $upgrades = ReserveUpgrade::with('reserve.reception','reserve.user')
                ->filter()
                ->get();

            $titles = "نام نام خانوادگی" . "\t" ."شماره موبایل" . "\t" . "سرویس" . "\t"."قیمت". "\t"."دستیار اول"."\t"."دستیار دوم".
                "\t"."توضیحات". "\t"."زمان انجام". "\t"."مدت زمان(ساعت)". "\t"."وضعیت";

            $setData = '';
            $rowData = '';
            foreach ($upgrades as $upgrade) {
                $fullname =  $upgrade->reserve->user->getFullName();
                $mobile = $upgrade->reserve->user->mobile??'';
                $service = $upgrade->detail_name??'';
                $price = $upgrade->price??'';
                $asistant1 =  $upgrade->asistant1->fullname??'';
                $asistant2 =  $upgrade->asistant2->fullname??'';
                $desc = $upgrade->desc;
                $doneTime = $upgrade->done_time();
                $duration = $upgrade->duration();
                $status = $upgrade->getStatus();


                $rowData .=  $fullname."\t";
                $rowData .=  $mobile."\t";
                $rowData .=  $service."\t";
                $rowData .=  $price."\t";
                $rowData .=  $asistant1."\t";
                $rowData .=  $asistant2."\t";
                $rowData .=  $desc."\t";
                $rowData .=  $doneTime."\t";
                $rowData .=  $duration."\t";
                $rowData .=  $status."\t"."\n";
            }
            $setData .= $rowData. "\n";

            $filename = 'export_upgrades_'.date('YmdHis') . ".xls";
            $export = new ExportService();
            $export->exel($titles,$setData,$filename);

        }


        $upgrades = ReserveUpgrade::with('reserve.reception','reserve.user')
            ->filter()
            ->paginate(30)
            ->withQueryString();

        $doctors = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})->orderBy('fullname','asc')->get();
        $secretaries = Admin::whereHas('roles', function($q){$q->where('name', 'secretary');})->orderBy('fullname','asc')->get();
        $asistants = Admin::whereHas('roles', function($q){$q->where('name', 'asistant1');})->orderBy('fullname','asc')->get();
        $serviceDetails= ServiceDetail::orderBy('name','asc')->get();
        $branches= Branch::orderBy('name','asc')->get();
        $receptions = Admin::whereHas('roles', function($q){$q->where('name', 'reception');})->orderBy('fullname','asc')->get();

        return view('admin.reserves.upgrades',compact('upgrades','doctors','secretaries','asistants','serviceDetails','branches','receptions'));
    }

}

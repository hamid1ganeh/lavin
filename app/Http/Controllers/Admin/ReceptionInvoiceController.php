<?php

namespace App\Http\Controllers\admin;

use App\Enums\PaymentType;
use App\Enums\ReserveStatus;
use App\Enums\Status;
use App\Enums\FoundStatus;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CardToCardPayment;
use App\Models\CashPayment;
use App\Models\ChequePayment;
use App\Models\Discount;
use App\Models\PosPayment;
use App\Models\Reception;
use App\Models\ReceptionInvoice;
use App\Models\ReserveInvoice;
use App\Models\ReserveUpgrade;
use App\Models\ServiceDetail;
use App\Models\ServiceReserve;
use App\Models\UsedDiscount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\DiscountType;
use Illuminate\Support\Facades\DB;
use Auth;


class ReceptionInvoiceController extends Controller
{
    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.pay.invoices');

        $branches = Auth::guard('admin')->user()->branches->pluck('id')->toArray();
        $invoices = ReceptionInvoice::with('reserve')
                                    ->whereHas('reserve',function ($q) use ($branches){
                                            $q->whereIn('branch_id',$branches);
                                    })
                                    ->filter()
                                    ->orderBy('created_at','desc')
                                    ->paginate(10)
                                    ->withQueryString();

        $serviceDetails= ServiceDetail::orderBy('name','asc')->get();
        $branches= Branch::whereIn('id',$branches)->orderBy('name','asc')->get();

        return view('admin.reserves.invoices.all',compact('invoices','serviceDetails','branches')) ;
    }
    public function show(Reception $reception)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.show');
        $invoice = ReceptionInvoice::where('reception_id',$reception->id)->first();

        $reserves = ServiceReserve::with('upgrades','detail')
            ->where('reception_id',$reception->id)
            ->whereNotIn('status',[ReserveStatus::waiting,ReserveStatus::confirm,ReserveStatus::confirm,ReserveStatus::cancel,ReserveStatus::wittingForAdviser,ReserveStatus::Adviser])
            ->whereDoesntHave('invoice')
            ->get();


        $reserveInvoices = ReserveInvoice::with('reserve')->where('reception_id',$reception->id)
                            ->orderBy('created_at','desc')->get();


        $invoiceSumPrice =0;
        $invoiceSumUpgradesPrice =0;
        $invoiceSumDiscountPrice =0;
        $invoiceFinalPrice =0;
        if (!is_null($reception) && !$reception->end){
            foreach ($reserveInvoices as $reserveInvoice){
                $sumUpgradesPrice = ReserveUpgrade::where('reserve_id',$reserveInvoice->reserve_id)->where('status',ReserveStatus::confirm)->sum('price');
                $finalPrice =  $reserveInvoice->price+$sumUpgradesPrice-$reserveInvoice->discount_price;
                $reserveInvoice->sum_upgrades_price= (integer)$sumUpgradesPrice;
                $reserveInvoice->final_price= $finalPrice;
                $reserveInvoice->save();
                if(!is_null($invoice)) {
                    $invoiceSumPrice += $reserveInvoice->price;
                    $invoiceSumUpgradesPrice += $sumUpgradesPrice;
                    $invoiceSumDiscountPrice += $reserveInvoice->discount_price;
                    $invoiceFinalPrice += $finalPrice;
                }
            }

            if(!is_null($invoice)){
              $invoice->sum_price = $invoiceSumPrice;
              $invoice->sum_upgrades_price = $invoiceSumUpgradesPrice;
              $invoice->final_price = $invoiceFinalPrice;
              $invoice->sum_discount_price = $invoiceSumDiscountPrice;
              $invoice->save();
            }

        }


        return view('admin.accounting.payment.show',compact('reserves','reception','reserveInvoices','invoice'));
    }

    public function store_reserve(Reception $reception,Request $request)
    {

        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.create');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

//        $request->validate(
//            [
//                'number' => ['required','max:255','unique:receipt_invoices'],
//            ],
//            [
//                "number.required" => " شماره فاکتور الزامی است.",
//                "number.max" => " شماره فاکتور طولانی است.",
//                "number.unique" => " شماره فاکتور قبلا ثبت شده است.",
//            ]);

        $reserve = $request->reserve;
        $reserve = ServiceReserve::find($reserve);
        if (is_null($reserve)){
            alert()->error('سرویس رزرو شده معتبر نیست');
            return back()->withInput();
        }
         $invoice = ReserveInvoice::where('reserve_id',$reserve->id)->firstOrNew();
         $sumUpgradesPrice = ReserveUpgrade::where('reserve_id',$reserve->id)->where('status',ReserveStatus::confirm)->sum('price');
         $discountCode =  $request->discount_code;
         if($discountCode=='0'){
                $request->validate(
                    [
                       'discount_price' => ['required','integer'],
                       'discount_description' => ['required','max:255'],
                    ],
                    [
                        "discount_price.required" => " مبلغ تخفیف ویژه الزامی است.",
                        "discount_description.required" => " توضیحات تخفیف ویژه الزامی است.",
                        "discount_description.max" => "حداکثر طول مجاز برای توضیحات تخفیف ویژه 255 کارکتر است.",
                    ]);

                 $discountPrice = $request->discount_price;
                 $discountDescription = $request->discount_description;
                 $discountId = null;
                 $finalPrice =  $reserve->total_price+$sumUpgradesPrice-$discountPrice;

                 if ($finalPrice<0){
                     alert()->error('مبلغ تخفیف نمیتواند از مبلغ پرداختی بیشتر باشد.');
                     return back()->withInput();
                 }

                 $invoice->price = $reserve->total_price;
                 $invoice->reserve_id = $reserve->id;
                 $invoice->reception_id = $reception->id;
                 $invoice->sum_upgrades_price = $sumUpgradesPrice;
                 $invoice->discount_id = $discountId;
                 $invoice->discount_price = $discountPrice;
                 $invoice->discount_description = $discountDescription;
                 $invoice->final_price = $finalPrice>0?$finalPrice:0;
                 $invoice->save();
           }elseIf($discountCode>0){
                $discount = Discount::with('users','services')->find($discountCode);

                if (is_null($discount)){
                    return back()->withInput();
                }


                if (!is_null($discount->expire) && $discount->expire < Carbon::now('+3:30')->format('Y-m-d H:i:s')){
                    alert()->error(" کد تخفیف  $discountCode   منقضی شده است ");
                    return back()->withInput();
                }

                if(UsedDiscount::where('user_id',$reserve->user_id)->where('discount_id',$discount->id)->count()){
                    alert()->error(" کد تخفیف $discountCode قبلا توسط این کاربر استفاده شده است");
                    return back()->withInput();
                }

                 if (!in_array($reserve->user_id,$discount->users->pluck('id')->toArray())){
                     return back()->withInput();
                 }

                if (!in_array($reserve->detail_id,$discount->services->pluck('id')->toArray())){
                    return back()->withInput();
                }
                $discountDescription= " استفاده از کد تخفیف $discount->code";
                $discountId = $discount->id;
                 if($discount->unit==DiscountType::percet){
                     $discountPrice = $reserve->total_price*$discount->value/100;
                 }elseIf($discount->unit==DiscountType::toman){
                     $discountPrice = $discount->value;
                 }

//                 $usedDiscount = new UsedDiscount();
//                 $usedDiscount->user_id = $reserve->user_id;
//                 $usedDiscount->discount_id = $discountId;

                 $finalPrice =  $reserve->total_price+$sumUpgradesPrice- $discountPrice;

                 if ($finalPrice<0){
                     alert()->error('مبلغ تخفیف نمیتواند از مبلغ پرداختی بیشتر باشد.');
                     return back()->withInput();
                 }

                 $invoice->reception_id = $reception->id;
                 $invoice->price = $reserve->total_price;
                 $invoice->reserve_id = $reserve->id;
                 $invoice->sum_upgrades_price = $sumUpgradesPrice;
                 $invoice->discount_id = $discountId;
                 $invoice->discount_price = $discountPrice;
                 $invoice->discount_description = $discountDescription;
                 $invoice->final_price = $finalPrice>0?$finalPrice:0;
                 $invoice->save();
//                DB::transaction(function() use ($invoice, $usedDiscount) {
//
//                    $usedDiscount->save();
//                });

           }else{
                 $discountPrice = 0;
                 $discountDescription=null;
                 $discountId = null;
                 $finalPrice =  $reserve->total_price+$sumUpgradesPrice;
                 $invoice->reception_id = $reception->id;
                 $invoice->price = $reserve->total_price;
                 $invoice->reserve_id = $reserve->id;
                 $invoice->sum_upgrades_price = $sumUpgradesPrice;
                 $invoice->discount_id = $discountId;
                 $invoice->discount_price = $discountPrice;
                 $invoice->discount_description = $discountDescription;
                 $invoice->final_price = $finalPrice>0?$finalPrice:0;
                 $invoice->save();
            }

        return back()->withInput();
//
//        return redirect(route('admin.reserves.payment.invoice',$reserve));
    }

    public function store(Reception $reception,Request $request)
    {
        $request->validate(
            [
                'number' => ['required','max:20','unique:reception_invoices,number'],
            ],
            [
                "number.required" => " شماره فاکتور الزامی است.",
                "number.max" => "حداکثر طول مجاز برای شماره فاکتور 20 کارکتر است.",
                "number.unique" => " این شماره فاکتور قبلا ثبت شده است.",
            ]);

        $reserveInvoices = ReserveInvoice::with('reserve')->where('reception_id',$reception->id)->get();
        $sumPrice=0;
        $sumDiscountPrice=0;
        $sumUpgradesPrice=0;
        $finalPrice=0;
        foreach ($reserveInvoices as $reserveInvoice){
            $sumPrice += $reserveInvoice->price;
            $sumDiscountPrice += $reserveInvoice->discount_price;
            $sumUpgradesPrice += $reserveInvoice->sum_upgrades_price;
            $finalPrice += $reserveInvoice->final_price;

            if (!is_null($reserveInvoice->discount_id)){
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $reception->user_id;
                $usedDiscount->discount_id = $reserveInvoice->discount_id;
                $usedDiscount->save();
            }
        }

        $invoice = new ReceptionInvoice();
        $invoice->reception_id = $reception->id;
        $invoice->number = $request->number;
        $invoice->sum_price = $sumPrice;
        $invoice->sum_discount_price = $sumDiscountPrice;
        $invoice->sum_upgrades_price = $sumUpgradesPrice;
        $invoice->final_price = $finalPrice;
        $invoice->save();

        return back();
    }

    public function invoice(ServiceReserve $reserve)
    {
        return "ok8";
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.show');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

          $invoice = ReceptionInvoice::with('reserve.user')->where('reserve_id',$reserve->id)->first();
          if (is_null($invoice)){
              return redirect(route('admin.reserves.payment.show',$reserve));
          }

          if (!is_null($reserve->reception) && !$reserve->reception->end){
              $sumUpgradesPrice = ReserveUpgrade::where('reserve_id',$reserve->id)->where('status',ReserveStatus::confirm)->sum('price');
              $finalPrice =  $invoice->price+$sumUpgradesPrice-$invoice->discount_price;
              $invoice->sum_upgrades_price= $sumUpgradesPrice;
              $invoice->final_price= $finalPrice;
              $invoice->save();
          }

            $sumCash = CashPayment::where('payable_type',get_class($invoice))
                ->where('payable_id',$invoice->id)
                ->where('type',PaymentType::income)
                ->sum('price');

            $sumCard = CardToCardPayment::where('payable_type',get_class($invoice))
                ->where('payable_id',$invoice->id)
                ->where('type',PaymentType::income)
                ->sum('price');


        $sumPos = PosPayment::where('payable_type',get_class($invoice))
                                ->where('payable_id',$invoice->id)
                                ->where('type',PaymentType::income)
                                ->sum('price');


        $sumCheque = ChequePayment::where('payable_type',get_class($invoice))
                                    ->where('payable_id',$invoice->id)
                                    ->where('type',PaymentType::income)
                                    ->where('passed',true)
                                    ->sum('price');

        $sumPaid = $sumPos+$sumCard+$sumCash+$sumCheque;
        $remained =  $invoice->final_price - $sumPaid;


        return view('admin.reserves.payment.invoice',compact('invoice','reserve','sumPaid','remained'));
    }

    public function found()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoices.pay');


        $branches = Auth::guard('admin')->user()->branches->pluck('id')->toArray();

        $mobile = request('mobile');
        $code = request('code');
        $nationCode = request('nation_code');

        if((isset($mobile) && $mobile!='') || (isset($nationCode) && $nationCode!='') || (isset($code) && $code!='')){
            $receptions = Reception::where('found_status','<>',FoundStatus::pending)
                ->whereIn('branch_id',$branches)
                ->orderBy('created_at','asc')
                ->filter()
                ->get();
        } else{
            $receptions = Reception::where('found_status',FoundStatus::referred)
                ->whereIn('branch_id',$branches)
                ->orderBy('created_at','asc')
                ->filter()
                ->get();
        }

        return  view('admin.found.found',compact('receptions'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Reception;
use App\Models\ReceptionInvoice;
use App\Models\ReserveInvoice;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use App\Models\CardToCardPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class ReceptionCardToCardPaymentController extends Controller
{

    public function index(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.index');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $payments = CardToCardPayment::with('reciverAccount')
                    ->where('payable_type',get_class($receptionInvoice))
                    ->where('payable_id',$receptionInvoice->id)
                    ->where('type',PaymentType::income)
                    ->orderBy('paid_at','desc')
                    ->get();

        return  view('admin.accounting.payment.card.all',compact('reception','receptionInvoice','payments'));

    }

    public function create(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::orderBy('bank_name')->get();
        return  view('admin.accounting.payment.card.create',compact('reception','receptionInvoice','accounts'));
    }

    public function store(Reception $reception,ReceptionInvoice $receptionInvoice,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'sender_full_name'=>'nullable|max:255',
            'sender_cart_number'=>'nullable|max:255|max:16|min:16',
            'receiver_account_id'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'sender_full_name.required' => ' نام و نام خانوادگی کارت واریز کننده الزامی است.',
            'sender_full_name.max' => 'حداکثر طول  نام و نام خانوادگی کارت واریز کننده  255 کارکتر',
            'sender_cart_number.required' => 'شماره  شماره کارت واریز کننده  الزامی است.',
            'sender_cart_number.max' => 'شماره کارت واریز کننده  16 کارکتر',
            'sender_cart_number.min' => 'شماره کارت واریز کننده  16 کارکتر',
            'receiver_account_id.required' => 'انتخاب حساب دریافت کننده الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $card = new CardToCardPayment();
        $card->payable_type = get_class($receptionInvoice);
        $card->payable_id = $receptionInvoice->id;
        $card->receiver_account_id = $request->receiver_account_id;
        $card->sender_full_name = $request->sender_full_name;
        $card->sender_cart_number = $request->sender_full_name;
        $card->price = $request->price;
        $card->transaction_number = $request->transaction_number;
        $card->paid_at = $paidAt;
        $card->description = $request->description;
        $card->cashier_id =Auth::guard('admin')->id();


        DB::transaction(function() use ($card, $receptionInvoice) {
            $card->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.card.index',[$reception,$receptionInvoice]));
    }

    public function edit(Reception $reception,ReceptionInvoice $receptionInvoice,CardToCardPayment $card)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::orderBy('bank_name')->get();
        return  view('admin.accounting.payment.card.edit',compact('reception','receptionInvoice','card','accounts'));
    }

    public function update(Reception $reception,ReceptionInvoice $receptionInvoice,CardToCardPayment $card,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'sender_full_name'=>'nullable|max:255',
            'sender_cart_number'=>'nullable|max:255|max:16|min:16',
            'receiver_account_id'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'sender_full_name.required' => ' نام و نام خانوادگی کارت واریز کننده الزامی است.',
            'sender_full_name.max' => 'حداکثر طول  نام و نام خانوادگی کارت واریز کننده  255 کارکتر',
            'sender_cart_number.required' => 'شماره  شماره کارت واریز کننده  الزامی است.',
            'sender_cart_number.max' => 'شماره کارت واریز کننده  16 کارکتر',
            'sender_cart_number.min' => 'شماره کارت واریز کننده  16 کارکتر',
            'receiver_account_id.required' => 'انتخاب حساب دریافت کننده الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

            $paidAt =  faToEn($request->paid_at);
            $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

            $card->receiver_account_id = $request->receiver_account_id;
            $card->sender_full_name = $request->sender_full_name;
            $card->sender_cart_number = $request->sender_cart_number;
            $card->price = $request->price;
            $card->transaction_number = $request->transaction_number;
            $card->paid_at = $paidAt;
            $card->description = $request->description;
            $card->cashier_id =Auth::guard('admin')->id();


        DB::transaction(function() use ($card, $receptionInvoice) {
            $card->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.card.index',[$reception,$receptionInvoice]));
    }

    public function destroy(Reception $reception,ReceptionInvoice $receptionInvoice,CardToCardPayment $card)
    {
            //اجازه دسترسی
//            config(['auth.defaults.guard' => 'admin']);
//            $this->authorize('reserves.payment.invoice.card.delete');
            if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
            {
                abort(403);
            }

            DB::transaction(function() use ($card, $receptionInvoice) {
                $card->delete();
                $receptionInvoice->updateCalculation();
            });

            toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
            return back()->withInput();
        }
    }

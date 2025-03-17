<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\CashPayment;
use App\Models\Reception;
use App\Models\ReceptionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;

class ReceptionCashPaymentController extends Controller
{

    public function index(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.index');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $payments = CashPayment::where('payable_type',get_class($receptionInvoice))
            ->where('payable_id',$receptionInvoice->id)
            ->where('type',PaymentType::income)
            ->orderBy('paid_at','desc')
            ->get();


        return  view('admin.accounting.payment.cash.all',compact('reception','receptionInvoice','payments'));
    }


    public function create(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }
        return  view('admin.accounting.payment.cash.create',compact('reception','receptionInvoice'));
    }

    public function store(Reception $reception,ReceptionInvoice $receptionInvoice,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'price'=>'required|integer',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $cash = new CashPayment();
        $cash->payable_type = get_class($receptionInvoice);
        $cash->payable_id =  $receptionInvoice->id;
        $cash->price =  $request->price;
        $cash->paid_at = $paidAt;
        $cash->description =  $request->description;
        $cash->cashier_id =  Auth::guard('admin')->id();

        DB::transaction(function() use ($cash, $receptionInvoice) {
            $cash->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.cash.index',[$reception,$receptionInvoice]));
    }


    public function edit(Reception $reception,ReceptionInvoice $receptionInvoice,CashPayment $cash)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }
        return  view('admin.accounting.payment.cash.edit',compact('reception','receptionInvoice','cash'));
    }

    public function update(Reception $reception,ReceptionInvoice $receptionInvoice,CashPayment $cash,Request $request)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'price'=>'required|integer',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $cash->price = $request->price;
        $cash->paid_at = $paidAt;
        $cash->description = $request->description;
        $cash->cashier_id = Auth::guard('admin')->id();

        DB::transaction(function() use ($cash, $receptionInvoice) {
            $cash->save();
            $receptionInvoice->updateCalculation();
        });

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        return redirect(route('admin.accounting.reception.invoices.cash.index',[$reception,$receptionInvoice]));
    }

    public function destroy(Reception $reception,ReceptionInvoice $receptionInvoice,CashPayment $cash)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.delete');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        DB::transaction(function() use ($cash, $receptionInvoice) {
            $cash->delete();
            $receptionInvoice->updateCalculation();
       });

        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

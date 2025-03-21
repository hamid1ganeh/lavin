<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\PosPayment;
use App\Models\ReceptionInvoice;
use App\Models\Reception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class ReceptionPosPaymentController extends Controller
{

    public function index(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.index');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

         $payments = PosPayment::with('receiverAccount')
                                    ->where('payable_type',get_class($receptionInvoice))
                                    ->where('payable_id',$receptionInvoice->id)
                                    ->orderBy('paid_at','desc')
                                    ->get();

         return  view('admin.accounting.payment.pos.all',compact('reception','receptionInvoice','payments'));
    }

    public function create(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.create');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.accounting.payment.pos.create',compact('reception','receptionInvoice','accounts'));
    }

    public function store(Reception $reception,ReceptionInvoice $receptionInvoice,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.create');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'receiver_account_id'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'receiver_account_id.required' => 'انتخاب حساب بانکی الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $pos = new PosPayment();
        $pos->payable_type = get_class($receptionInvoice);
        $pos->payable_id = $receptionInvoice->id;
        $pos->receiver_account_id = $request->receiver_account_id;
        $pos->price = $request->price;
        $pos->transaction_number =  $request->transaction_number;
        $pos->paid_at = $paidAt;
        $pos->description = $request->description;
        $pos->cashier_id = Auth::guard('admin')->id();

        DB::transaction(function() use ($pos, $receptionInvoice) {
            $pos->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.pos.index',[$reception,$receptionInvoice]));
    }


    public function edit(Reception $reception,ReceptionInvoice $receptionInvoice,PosPayment $pos)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.edit');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.accounting.payment.pos.edit',compact('reception','receptionInvoice','accounts','pos'));
    }

    public function update(Reception $reception,ReceptionInvoice $receptionInvoice,PosPayment $pos,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.edit');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([
            'receiver_account_id'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'receiver_account_id.required' => 'انتخاب حساب بانکی الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $pos->receiver_account_id = $request->receiver_account_id;
        $pos->price = $request->price;
        $pos->transaction_number = $request->transaction_number;
        $pos->paid_at = $paidAt;
        $pos->description = $request->description;
        $pos->cashier_id = Auth::guard('admin')->id();

        DB::transaction(function() use ($pos, $receptionInvoice) {
            $pos->save();
            $receptionInvoice->updateCalculation();
        });

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');

        return redirect(route('admin.accounting.reception.invoices.pos.index',[$reception,$receptionInvoice]));
    }


    public function destroy(Reception $reception,ReceptionInvoice $receptionInvoice,PosPayment $pos)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.delete');

        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        DB::transaction(function() use ($pos, $receptionInvoice) {
            $pos->delete();
            $receptionInvoice->updateCalculation();
        });

        $receptionInvoice->updateCalculation();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\PosPayment;
use App\Models\ReserveInvoice;
use App\Models\ReservePayment;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReservePosPaymentContoller extends Controller
{

    public function index(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
         $payments = PosPayment::with('account')
                                    ->where('payable_type',get_class($invoice))
                                    ->where('payable_id',$invoice->id)
                                    ->orderBy('paid_at','desc')
                                    ->get();

         return  view('admin.reserves.payment.pos.all',compact('reserve','invoice','payments'));
    }

    public function create(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.reserves.payment.pos.create',compact('reserve','invoice','accounts'));
    }

    public function store(ServiceReserve $reserve,ReserveInvoice $invoice,Request $request)
    {
        $request->validate([
            'account'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'account.required' => 'انتخاب حساب بانکی الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        PosPayment::create(['payable_type'=>get_class($invoice),
                            'payable_id'=> $invoice->id,
                            'account_id'=>$request->account,
                             'price'=>$request->price,
                             'transaction_number'=> $request->transaction_number,
                             'paid_at'=> $paidAt,
                             'description'=> $request->description,
                             'cashier_id'=>Auth::guard('admin')->id()]);

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        return redirect(route('admin.reserves.payment.pos.index',[$reserve,$invoice]));
    }


    public function edit(ServiceReserve $reserve,ReserveInvoice $invoice,PosPayment $pos)
    {
        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.reserves.payment.pos.edit',compact('reserve','invoice','accounts','pos'));
    }

    public function update(ServiceReserve $reserve,ReserveInvoice $invoice,PosPayment $pos,Request $request)
    {
        $request->validate([
            'account'=>'required|exists:accounts,id',
            'price'=>'required|integer',
            'transaction_number'=>'nullable|max:255',
            'paid_at'=>'required',
            'description'=>'nullable|max:255',
        ],[ 'account.required' => 'انتخاب حساب بانکی الزامی است.',
            'price.required' => 'مبلغ تراکنش الزامی است.',
            'price.integer' => 'مبلغ تراکنش معتبر نیست.',
            'paid_at.required' => 'تاریخ تراکنش الزامی است.',
            'transaction_number.required' => 'شماره تراکنش الزامی است.',
            'transaction_number.max' => 'حداکثر طول شماره تراکنش 255 کارکتر',
            'description.max' => 'حداکثر طول شماره تراکنش 255 کارکتر']);

        $paidAt =  faToEn($request->paid_at);
        $paidAt = Jalalian::fromFormat('Y/m/d H:i', $paidAt)->toCarbon("Y-m-d H:i");

        $pos->update(['account_id'=>$request->account,
                      'price'=>$request->price,
                      'transaction_number'=> $request->transaction_number,
                      'paid_at'=> $paidAt,
                      'description'=> $request->description,
                      'cashier_id'=>Auth::guard('admin')->id()]);

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');

        return redirect(route('admin.reserves.payment.pos.index',[$reserve,$invoice]));
    }


    public function destroy(ServiceReserve $reserve,ReserveInvoice $invoice,PosPayment $pos)
    {
        $pos->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

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
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.index');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

         $payments = PosPayment::with('receiverAccount')
                                    ->where('payable_type',get_class($invoice))
                                    ->where('payable_id',$invoice->id)
                                    ->orderBy('paid_at','desc')
                                    ->get();

         return  view('admin.reserves.payment.pos.all',compact('reserve','invoice','payments'));
    }

    public function create(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.create');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.reserves.payment.pos.create',compact('reserve','invoice','accounts'));
    }

    public function store(ServiceReserve $reserve,ReserveInvoice $invoice,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.create');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
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

        PosPayment::create(['payable_type'=>get_class($invoice),
                            'payable_id'=> $invoice->id,
                            'receiver_account_id'=>$request->receiver_account_id,
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
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.edit');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.reserves.payment.pos.edit',compact('reserve','invoice','accounts','pos'));
    }

    public function update(ServiceReserve $reserve,ReserveInvoice $invoice,PosPayment $pos,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.edit');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
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

        $pos->update(['receiver_account_id'=>$request->receiver_account_id,
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
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('invoice.pos.delete');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $pos->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

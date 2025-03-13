<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\CashPayment;
use App\Models\ReserveInvoice;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReceptionCashPaymentController extends Controller
{

    public function index(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.index');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $payments = CashPayment::where('payable_type',get_class($invoice))
            ->where('payable_id',$invoice->id)
            ->where('type',PaymentType::income)
            ->orderBy('paid_at','desc')
            ->get();

        return  view('admin.reserves.payment.cash.all',compact('reserve','invoice','payments'));
    }


    public function create(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.create');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        return  view('admin.reserves.payment.cash.create',compact('reserve','invoice'));
    }

    public function store(ServiceReserve $reserve,ReserveInvoice $invoice,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.create');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
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

        CashPayment::create(['payable_type'=>get_class($invoice),
            'payable_id'=> $invoice->id,
            'price'=>$request->price,
            'paid_at'=> $paidAt,
            'description'=> $request->description,
            'cashier_id'=>Auth::guard('admin')->id()]);

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        return redirect(route('admin.reserves.payment.cash.index',[$reserve,$invoice]));
    }


    public function edit(ServiceReserve $reserve,ReserveInvoice $invoice,CashPayment $cash)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.edit');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }
        return  view('admin.reserves.payment.cash.edit',compact('reserve','invoice','cash'));
    }

    public function update(ServiceReserve $reserve,ReserveInvoice $invoice,CashPayment $cash,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.edit');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
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

        $cash->update([ 'price'=>$request->price,
                        'paid_at'=> $paidAt,
                        'description'=> $request->description,
                        'cashier_id'=>Auth::guard('admin')->id()]);

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');

        return redirect(route('admin.reserves.payment.cash.index',[$reserve,$invoice]));
    }


    public function destroy(ServiceReserve $reserve,ReserveInvoice $invoice,CashPayment $cash)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.payment.invoice.cash.delete');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $cash->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

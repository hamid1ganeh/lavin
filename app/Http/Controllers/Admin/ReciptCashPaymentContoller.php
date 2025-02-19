<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\CashPayment;
use App\Models\ReceiptInvoice;
use App\Models\WarehouseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReciptCashPaymentContoller extends Controller
{

    public function index(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.index');

        $payments = CashPayment::where('payable_type',get_class($invoice))
            ->where('payable_id',$invoice->id)
            ->where('type',PaymentType::income)
            ->orderBy('paid_at','desc')
            ->get();

        return  view('admin.warehousing.receipt.invoice.cash.all',compact('receipt','invoice','payments'));
    }


    public function create(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.create');

        return  view('admin.warehousing.receipt.invoice.cash.create',compact('receipt','invoice'));
    }

    public function store(WarehouseReceipt $receipt,ReceiptInvoice $invoice,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.create');

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

        return redirect(route('admin.warehousing.receipts.invoice.cash.index',[$receipt,$invoice]));
    }


    public function edit(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CashPayment $cash)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.edit');

        return  view('admin.warehousing.receipt.invoice.cash.edit',compact('receipt','invoice','cash'));
    }

    public function update(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CashPayment $cash,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.edit');

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

        return redirect(route('admin.warehousing.receipts.invoice.cash.index',[$receipt,$invoice]));
    }

    public function destroy(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CashPayment $cash)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cash.delete');

        $cash->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

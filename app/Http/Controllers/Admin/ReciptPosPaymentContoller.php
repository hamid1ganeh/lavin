<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\PosPayment;
use App\Models\ReceiptInvoice;
use App\Models\WarehouseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReciptPosPaymentContoller extends Controller
{

    public function index(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.index');

         $payments = PosPayment::with('receiverAccount')
                                    ->where('payable_type',get_class($invoice))
                                    ->where('payable_id',$invoice->id)
                                    ->orderBy('paid_at','desc')
                                    ->get();

         return  view('admin.warehousing.receipt.invoice.pos.all',compact('receipt','invoice','payments'));
    }

    public function create(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.create');


        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.warehousing.receipt.invoice.pos.create',compact('receipt','invoice','accounts'));
    }

    public function store(WarehouseReceipt $receipt,ReceiptInvoice $invoice,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.create');

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

        return redirect(route('admin.warehousing.receipt.invoice.pos.index',[$receipt,$invoice]));
    }


    public function edit(WarehouseReceipt $receipt,ReceiptInvoice $invoice,PosPayment $pos)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.edit');

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.warehousing.receipt.invoice.pos.edit',compact('receipt','invoice','accounts','pos'));
    }

    public function update(WarehouseReceipt $receipt,ReceiptInvoice $invoice,PosPayment $pos,Request $request)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.edit');

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

        return redirect(route('admin.warehousing.receipt.invoice.pos.index',[$receipt,$invoice]));
    }


    public function destroy(WarehouseReceipt $receipt,ReceiptInvoice $invoice,PosPayment $pos)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.pos.delete');

        $pos->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ReceiptInvoice;
use App\Models\WarehouseReceipt;
use Illuminate\Http\Request;
use App\Models\CardToCardPayment;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReciptCardToCardPaymentContoller extends Controller
{
    public function index(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.index');

        $payments = CardToCardPayment::with('reciverAccount')
            ->where('payable_type',get_class($invoice))
            ->where('payable_id',$invoice->id)
            ->where('type',PaymentType::income)
            ->orderBy('paid_at','desc')
            ->get();

        return  view('admin.warehousing.receipt.invoice.card.all',compact('receipt','invoice','payments'));
    }

    public function create(WarehouseReceipt $receipt,ReceiptInvoice $invoice)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.create');

        $accounts = Account::orderBy('bank_name')->get();
        return  view('admin.warehousing.receipt.invoice.card.create',compact('receipt','invoice','accounts'));
    }

    public function store(WarehouseReceipt $receipt,ReceiptInvoice $invoice,Request $request)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.create');

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

        CardToCardPayment::create(['payable_type'=>get_class($invoice),
            'payable_id'=> $invoice->id,
            'receiver_account_id'=>$request->receiver_account_id,
            'sender_full_name'=>$request->sender_full_name,
            'sender_cart_number'=>$request->sender_cart_number,
            'price'=>$request->price,
            'transaction_number'=> $request->transaction_number,
            'paid_at'=> $paidAt,
            'description'=> $request->description,
            'cashier_id'=>Auth::guard('admin')->id()]);

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        return redirect(route('admin.warehousing.receipt.invoice.card.index',[$receipt,$invoice]));
    }


    public function edit(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CardToCardPayment $card)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.edit');


        $accounts = Account::orderBy('bank_name')->get();
        return  view('admin.warehousing.receipt.invoice.card.edit',compact('receipt','invoice','accounts','card'));
    }

    public function update(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CardToCardPayment $card,Request $request)
    {
//        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.edit');

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

            $card->update([ 'receiver_account_id'=>$request->receiver_account_id,
            'sender_full_name'=>$request->sender_full_name,
            'sender_cart_number'=>$request->sender_cart_number,
            'price'=>$request->price,
            'transaction_number'=> $request->transaction_number,
            'paid_at'=> $paidAt,
            'description'=> $request->description,
            'cashier_id'=>Auth::guard('admin')->id()]);

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');

        return redirect(route('admin.warehousing.receipt.invoice.card.index',[$receipt,$invoice]));
    }

    public function destroy(WarehouseReceipt $receipt,ReceiptInvoice $invoice,CardToCardPayment $card)
    {
        //اجازه دسترسی
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.card.delete');
        $card->delete();
        toast('پرداختی مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

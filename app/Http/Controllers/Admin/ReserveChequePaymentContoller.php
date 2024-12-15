<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\ChequePayment;
use App\Models\ReserveInvoice;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class ReserveChequePaymentContoller extends Controller
{

    public function index(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
            $payments = ChequePayment::where('payable_type',get_class($invoice))
                        ->where('payable_id',$invoice->id)
                        ->where('type',PaymentType::income)
                        ->orderBy('date_of_issue','desc')
                        ->get();


            return  view('admin.reserves.payment.cheque.all',compact('reserve','invoice','payments'));
    }


    public function create(ServiceReserve $reserve,ReserveInvoice $invoice)
    {
        return  view('admin.reserves.payment.cheque.create',compact('reserve','invoice'));
    }


    public function store(ServiceReserve $reserve,ReserveInvoice $invoice,Request $request)
    {
        return "Dfd";
        $request->validate(
            [
                'sender_full_name' => ['required','max:255'],
                'sender_nation_code' => ['required','max:10','min:10'],
                'sender_account_number' => ['required','max:20'],
                'serial_number' => ['required','max:255'],
                'price' => ['required','integer'],
                'date_of_issue' => ['required'],
                'due_date' => ['required'],
                'description' => ['nullable','max:255'],
            ],
            [
                'sender_full_name.required' => ' نام و نام خانوادگی صادر کننده چک  الزامی است.',
                'sender_full_name.max' => ' حداکثر طول نام و نام خانوادگی صادر کننده چک است.',
                'sender_nation_code.required' => '  کدملی صادر کننده  چک الزامی است.',
                'sender_nation_code.max' => '  طول  کدملی صادر کننده چک  10 رقم است.',
                'sender_nation_code.min' => '  طول  کدملی صادر کننده چک  10 رقم است.',
                'sender_account_number.required' => ' شماره حساب صادر کننده چک الزامی است.',
                'sender_account_number.max' => ' حداکثر طول شماره حساب صادر کننده چک  20 رقم است.',
                'serial_number.required' => ' شماره سریال چک الزامی است.',
                'serial_number.max' => ' حداکثر طول شماره سریال چک 255 کارکتر است.',
                'price.required' => ' مبلغ چک الزامی است.',
                'date_of_issue.required' => ' تاریخ صدور چک الزامی است.',
                'due_date.required' => ' تاریخ سررسید چک الزامی است.',
                'description.max' => ' حداکثر طول توضیحات چک  255 کارکتر است.',
            ]);

        $dateOfIssue =  faToEn($request->date_of_issue);
        $dateOfIssue = Jalalian::fromFormat('Y/m/d', $dateOfIssue)->toCarbon("Y-m-d");

        $dueDate =  faToEn($request->due_date);
        $dueDate = Jalalian::fromFormat('Y/m/d', $dueDate)->toCarbon("Y-m-d");

        ChequePayment::create(['payable_type'=>get_class($invoice),
                                'payable_id'=> $invoice->id,
                                'sender_full_name'=>$request->sender_full_name,
                                'sender_nation_code'=>$request->sender_nation_code,
                                'sender_account_number'=>$request->sender_account_number,
                                'serial_number'=>$request->serial_number,
                                'price'=>$request->price,
                                'date_of_issue'=> $dateOfIssue,
                                'due_date'=> $dueDate,
                                'description'=> $request->description,
                                'cashier_id'=>Auth::guard('admin')->id(),
                                'type'=>PaymentType::income]);

        toast('چک جدید اضافه شد.','success')->position('bottom-end');
        return redirect(route('admin.reserves.payment.cheque.index',[$reserve,$invoice]));
    }

    public function edit(ServiceReserve $reserve,ReserveInvoice $invoice,ChequePayment $cheque)
    {
        return redirect(route('admin.reserves.payment.cheque.edit',[$reserve,$invoice,$cheque]));
    }


    public function update(ServiceReserve $reserve,ReserveInvoice $invoice,ChequePayment $cheque,Request $request)
    {
        $request->validate(
            [
                'sender_full_name' => ['required','max:255'],
                'sender_nation_code' => ['required','max:10','min:10'],
                'sender_account_number' => ['required','max:20'],
                'serial_number' => ['required','max:255'],
                'price' => ['required','integer'],
                'date_of_issue' => ['required'],
                'due_date' => ['required'],
                'description' => ['nullable','max:255'],
            ],
            [
                'sender_full_name.required' => ' نام و نام خانوادگی صادر کننده چک  الزامی است.',
                'sender_full_name.max' => ' حداکثر طول نام و نام خانوادگی صادر کننده چک است.',
                'sender_nation_code.required' => '  کدملی صادر کننده  چک الزامی است.',
                'sender_nation_code.max' => '  طول  کدملی صادر کننده چک  10 رقم است.',
                'sender_nation_code.min' => '  طول  کدملی صادر کننده چک  10 رقم است.',
                'sender_account_number.required' => ' شماره حساب صادر کننده چک الزامی است.',
                'sender_account_number.max' => ' حداکثر طول شماره حساب صادر کننده چک  20 رقم است.',
                'serial_number.required' => ' شماره سریال چک الزامی است.',
                'serial_number.max' => ' حداکثر طول شماره سریال چک 255 کارکتر است.',
                'price.required' => ' مبلغ چک الزامی است.',
                'date_of_issue.required' => ' تاریخ صدور چک الزامی است.',
                'due_date.required' => ' تاریخ سررسید چک الزامی است.',
                'description.max' => ' حداکثر طول توضیحات چک  255 کارکتر است.',
            ]);

        $dateOfIssue =  faToEn($request->date_of_issue);
        $dateOfIssue = Jalalian::fromFormat('Y/m/d', $dateOfIssue)->toCarbon("Y-m-d");

        $dueDate =  faToEn($request->due_date);
        $dueDate = Jalalian::fromFormat('Y/m/d', $dueDate)->toCarbon("Y-m-d");

        $cheque->update(['payable_type'=>get_class($invoice),
                        'payable_id'=> $invoice->id,
                        'sender_full_name'=>$request->sender_full_name,
                        'sender_nation_code'=>$request->sender_nation_code,
                        'sender_account_number'=>$request->sender_account_number,
                        'serial_number'=>$request->serial_number,
                        'price'=>$request->price,
                        'date_of_issue'=> $dateOfIssue,
                        'due_date'=> $dueDate,
                        'description'=> $request->description,
                        'cashier_id'=>Auth::guard('admin')->id(),
                        'type'=>PaymentType::income]);

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        return redirect(route('admin.reserves.payment.cheque.index',[$reserve,$invoice]));
    }

    public function destroy(ServiceReserve $reserve,ReserveInvoice $invoice,ChequePayment $cheque)
    {
        $cheque->delete();
        toast('چک مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ChequePayment;
use App\Models\Reception;
use App\Models\ReceptionInvoice;
use App\Models\ReserveInvoice;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class ReceptionChequePaymentController extends Controller
{
    public function index(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.index');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $payments = ChequePayment::where('payable_type',get_class($receptionInvoice))
                    ->where('payable_id',$receptionInvoice->id)
                    ->where('type',PaymentType::income)
                    ->orderBy('date_of_issue','desc')
                    ->get();

        $accounts = Account::where('pos',true)->orderBy('bank_name')->get();
        return  view('admin.accounting.payment.cheque.all',compact('reception','receptionInvoice','payments','accounts'));
    }

    public function create(Reception $reception,ReceptionInvoice $receptionInvoice)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }
        return  view('admin.accounting.payment.cheque.create',compact('reception','receptionInvoice'));
    }

    public function store(Reception $reception,ReceptionInvoice $receptionInvoice,Request $request)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.create');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

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

        $cheque = new ChequePayment();
        $cheque->payable_type = get_class($receptionInvoice);
        $cheque->payable_id = $receptionInvoice->id;
        $cheque->sender_full_name = $request->sender_full_name;
        $cheque->sender_nation_code = $request->sender_nation_code;
        $cheque->sender_account_number = $request->sender_account_number;
        $cheque->serial_number = $request->serial_number;
        $cheque->price = $request->price;
        $cheque->date_of_issue = $dateOfIssue;
        $cheque->due_date = $dueDate;
        $cheque->description = $request->description;
        $cheque->cashier_id =Auth::guard('admin')->id();
        $cheque->type = PaymentType::income;

        DB::transaction(function() use ($cheque, $receptionInvoice) {
            $cheque->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.cheque.index',[$reception,$receptionInvoice]));
    }


    public function edit(Reception $reception,ReceptionInvoice $receptionInvoice,ChequePayment $cheque)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }
        return  view('admin.accounting.payment.cheque.edit',compact('reception','receptionInvoice','cheque'));
    }


    public function update(Reception $reception,ReceptionInvoice $receptionInvoice,ChequePayment $cheque,Request $request)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.edit');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

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
                'description.max' => ' حداکثر طول توضیحات چک  255 کارکتر است.']);

        $dateOfIssue =  faToEn($request->date_of_issue);
        $dateOfIssue = Jalalian::fromFormat('Y/m/d', $dateOfIssue)->toCarbon("Y-m-d");

        $dueDate =  faToEn($request->due_date);
        $dueDate = Jalalian::fromFormat('Y/m/d', $dueDate)->toCarbon("Y-m-d");

        $cheque->update(['sender_full_name'=>$request->sender_full_name,
                        'sender_nation_code'=>$request->sender_nation_code,
                        'sender_account_number'=>$request->sender_account_number,
                        'serial_number'=>$request->serial_number,
                        'price'=>$request->price,
                        'date_of_issue'=> $dateOfIssue,
                        'due_date'=> $dueDate,
                        'description'=> $request->description,
                        'cashier_id'=>Auth::guard('admin')->id()]);

        $cheque->sender_full_name = $request->sender_full_name;
        $cheque->sender_nation_code = $request->sender_nation_code;
        $cheque->sender_account_number = $request->sender_account_number;
        $cheque->serial_number = $request->serial_number;
        $cheque->price = $request->price;
        $cheque->date_of_issue = $dateOfIssue;
        $cheque->due_date = $dueDate;
        $cheque->description = $request->description;
        $cheque->cashier_id = Auth::guard('admin')->id();

        DB::transaction(function() use ($cheque, $receptionInvoice) {
            $cheque->save();
            $receptionInvoice->updateCalculation();
        });

        toast('پرداختی شما ثبت شد.','success')->position('bottom-end');

        if (!is_null($request->get('invoice')))
        {
            return back();
        }
        return redirect(route('admin.accounting.reception.invoices.cheque.index',[$reception,$receptionInvoice]));
    }

    public function destroy(Reception $reception,ReceptionInvoice $receptionInvoice,ChequePayment $cheque)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.delete');
        if (!in_array($reception->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        DB::transaction(function() use ($cheque, $receptionInvoice) {
            $cheque->save();
            $receptionInvoice->updateCalculation();
        });
        toast('چک مورد نظر حذف شد.','error')->position('bottom-end');
        return back()->withInput();
    }

    public function pass(ServiceReserve $reserve,ReserveInvoice $invoice,ChequePayment $cheque,Request $request)
    {
//        config(['auth.defaults.guard' => 'admin']);
//        $this->authorize('reserves.payment.invoice.cheque.pass');
        if (!in_array($reserve->branch_id,Auth::guard('admin')->user()->branches->pluck('id')->toArray()))
        {
            abort(403);
        }

        $request->validate([ 'passed_date' => ['required'],
                            'passed_by_account_id'=>['required','exists:accounts,id']],
                            ['passed_date.required' => ' تاریخ پاس شدن چک الزامی است.',
                            'passed_by_account_id.required' => 'حساب پاس شدن چک الزامی است.']);

        $passedDate =  faToEn($request->passed_date);
        $passedDate = Jalalian::fromFormat('Y/m/d', $passedDate)->toCarbon("Y-m-d");
        $cheque->passed = true;
        $cheque->passed_date =  $passedDate;
        $cheque->passed_by_account_id = $request->passed_by_account_id;
        $cheque->save();

        toast('چک مورد نظر پاس شد.','success')->position('bottom-end');
        return back()->withInput();
    }
}

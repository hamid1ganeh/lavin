<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\ChequePayment;
use App\Models\ReserveInvoice;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;

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
        return  view('admin.reserves.payment.cash.create',compact('reserve','invoice'));
    }


    public function store(Request $request)
    {
        //
    }




    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

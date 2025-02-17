<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseReceipt;
use Illuminate\Http\Request;
use App\Models\ReceiptInvoice;

class ReceiptInvoiceController extends Controller
{
     public function show(WarehouseReceipt $receipt)
     {
             $invoice = $receipt->invoic;
             if (is_null($invoice)){
                 return view('admin.warehousing.receipt.invoice.show', compact('receipt'));
             }

         return view('admin.warehousing.receipt.invoice.invoice', compact('invoice'));
     }


     public function create(WarehouseReceipt $receipt,Request $request)
     {

         $invoice = new ReceiptInvoice();
         $invoice->receipt_id = $receipt->id;
         $invoice->number = $request->number;
         $invoice->price = $receipt->price;
         $invoice->discount_price = $receipt->discount;
         $invoice->final_price = $receipt->total_cost;
         $invoice->save();
         toast('فاکتور جدید ایجاد شد.','success')->position('bottom-end');
         return back();
     }
}

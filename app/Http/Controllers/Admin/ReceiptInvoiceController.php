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
     }


     public function create(WarehouseReceipt $receipt,Request $request)
     {
            return $receipt;
     }
}

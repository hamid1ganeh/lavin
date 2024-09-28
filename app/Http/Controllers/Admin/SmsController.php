<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SMS;
use App\Models\SmsHistory;
use App\Models\Admin;


class SmsController extends Controller
{
   public function history()
   {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('sms.history'); 

      $messages = SmsHistory::with('sender')
      ->orderBy('created_at','desc')
      ->filter()
      ->paginate(50)
      ->withQueryString();
      
      $admins = Admin::orderBy('fullname','asc')->get();
      return  view('admin.sms',compact('messages','admins'));
   }
}

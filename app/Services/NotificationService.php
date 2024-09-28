<?php
namespace App\Services;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\User;
use Auth;
use App\Services\SMS;
use Illuminate\Support\Facades\Log;


class NotificationService
{

  public function send($title,$message,$status,$audience,$type,$sms=false):void
  {
       $notification = Notification::create([
          'title'=>$title,
          'message'=>$message,
           'admin_id'=>Auth::guard('admin')->id(),
           'type'=>$type,
           'status'=>$status,
           'sms'=>$sms
        ]);

          if($type == "user"){
              try {
                  $notification->users()->sync($audience);
              }catch (\Exception $e){
                  Log::info($e);
              }
          }else if($type == "admin"){
              try {
                  $notification->admins()->sync($audience);
              }catch (\Exception $e){
                  Log::info($e);
              }
          }

          if($sms){
              if($type == "user"){
                  $mobiles = User::find($audience)->pluck('mobile')->toArray();
              }elseIf($type == "admin"){
                  $mobiles = Admin::find($audience)->pluck('mobile')->toArray();
              }
             $sendSms = new SMS();
             $sendSms->send($mobiles,$message);
          }
  }
}

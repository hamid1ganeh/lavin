<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Reception;
use App\Models\ServiceReserve;
use App\Enums\ReserveStatus;

class ReceptionController extends Controller
{
     public function index()
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reception.index');

         $mobile = request('mobile');
         $code = request('code');
         $nationCode = request('nation_code');

         if((isset($mobile) && $mobile!='') || (isset($nationCode) && $nationCode!='') || (isset($code) && $code!='')){
             $receptions = Reception::orderBy('created_at','asc')->filter()->get();
         } else{
             $receptions = Reception::where('end',false)->orderBy('created_at','asc')->filter()->get();
         }

         return  view('admin.reception',compact('receptions'));
     }

     public function end(Reception $reception)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('reception.end');

         $reserves = ServiceReserve::where('reception_id',$reception->id)->whereNotIn('status',[ReserveStatus::done,ReserveStatus::cancel])->exists();

         if($reserves){
             alert()->error('برای این کد مراجعه رزرو  انجام نشده وجود دارد', 'خطا');
             return back();
         }

         $upgrades = ServiceReserve::with('upgrades')->where('reception_id',$reception->id)
             ->WhereHas('upgrades',function ($q){
                 $q->where('status',ReserveStatus::waiting);
             })->exists();

         if($upgrades){
             alert()->error('برای این کد مراجعه  ارتقاء انجام نشده وجود دارد', 'خطا');
             return back();
         }

        $reception->end =true;
        $reception->endTime = Carbon::now('+3:30')->format('Y-m-d H:i:s');
        $reception->save();
        return back();
     }

    public function start(Reception $reception)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reception.start');

        $last = $reception->hasOpenReferCode();
        if($last){
            alert()->error(' برای این کاربر یک کد مراجعه باز با شماره '.$last->code.' وجود دارد ', 'خطا');
            return back();
        }
        $reception->end =false;
        $reception->endTime = null;
        $reception->save();
        return back();
    }
}

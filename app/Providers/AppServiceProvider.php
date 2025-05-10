<?php

namespace App\Providers;

use App\Enums\AnaliseStatus;
use App\Enums\seenStatus;
use App\Enums\NumberStatus;
use App\Models\AnalyseReserve;
use App\Models\Reception;
use Illuminate\Support\ServiceProvider;
use App\Models\Service;
use App\Models\ProductCategory;
use App\Models\Number;
use App\Models\Adviser;
use Illuminate\Support\Facades\View;
use App\Enums\Status;
use App\Models\Notification;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['*'], function($view)
        {
            // $services = Service::with('details')->where('status',Status::Active)
            // ->whereHas('details')->orderBy('name','asc')->get();

            $services = Service::with('details')->where('status',Status::Active)->orderBy('name','asc')->get();

            $productCategories = ProductCategory::where('status',Status::Active)->where('parent_id',0)->orderBy('name','asc')->get();
            $notificationCount=0;

            if(Auth::check())
            {
                $notificationCount = Notification::whereHas('users',function($q){
                    $q->where('user_id',Auth::id())->where('seen',seenStatus::unseen);
                })->count();
            }

            $adminNotificationCount=0;

            if(Auth::guard('admin')->check())
            {
                $adminNotificationCount = Notification::whereHas('admins',function($q){
                    $q->where('admin_id', Auth::guard('admin')->id())->where('seen',seenStatus::unseen);
                })->count();
            }


            $waitingAdviser = 0;
            $adviserCount = 0;
            $inPersonAdviserCount = 0;
            $acceptCount = 0;
            $doctorAnalise = 0;
            $analiseCount = 0;
            $operatorCount = 0;
            $openReceptionCount = 0;

            if(Auth::guard('admin')->check())
            {
                $roles = Auth::guard('admin')->user()->getRoles();
                if(count($roles)==1 && in_array('adviser-operator',$roles))
                {
                    $waitingAdviser = Number::where('operator_id',Auth::guard('admin')->id())->where('status',NumberStatus::Operator)->count();
                }
                else  if(count($roles)==1 && in_array('adviser',$roles))
                {
                    $waitingAdviser = Adviser::where('adviser_id',Auth::guard('admin')->id())->where('status',NumberStatus::Adviser)->count();
                }
                else  if(count($roles)==1 && in_array('adviser-manegment',$roles))
                {
                    $waitingAdviser = Number::where('status',NumberStatus::NoAction)->orWhere('status',NumberStatus::WaitingForAdviser)->count();
                }
                else  if(count($roles)==1 && in_array('adviser-arrangement',$roles))
                {
                    $waitingAdviser = Adviser::where('status',NumberStatus::Accept)->orWhere('status',NumberStatus::RecivedDocuments)->count();
                }

                $roles = Auth::guard('admin')->user()->getRoles();
                if(in_array('adviser',$roles)){
                    $adviserCount = Adviser::where('status',NumberStatus::Adviser)->where('adviser_id',Auth::guard('admin')->id())->count();
                    $inPersonAdviserCount = Adviser::where('status',NumberStatus::Adviser)->where('in_person',true)->where('adviser_id',Auth::guard('admin')->id())->count();
                }

                if(in_array('adviser-arrangement',$roles)){
                    $acceptCount = Adviser::where('status',NumberStatus::Accept)->count();
                }



                if(in_array('doctor',$roles)){
                    $doctorAnalise = AnalyseReserve::where('doctor_id',Auth::guard('admin')->id())->where('status',AnaliseStatus::doctor)->count();
                }else {
                    $analiseCount = AnalyseReserve::where('status',AnaliseStatus::pending)->count();
                }

                if(in_array('operator',$roles)){
                    $operatorCount = Number::where('operator_id',Auth::guard('admin')->id())->where('status', NumberStatus::Operator)->count();
                }

                if(in_array('reception',$roles)){
                    $openReceptionCount = Reception::where('branch_id',Auth::guard('admin')->user()->branches->pluck('id')->toArray())->where('end',false)->count();
                }

            }

            $view->with([
                'services'=>$services,
                'productCategories'=>$productCategories,
                'notificationCount'=>$notificationCount,
                'adminNotificationCount'=>$adminNotificationCount,
                'waitingAdviser'=>$waitingAdviser,
                'adviserCount'=>$adviserCount,
                'inPersonAdviserCount'=>$inPersonAdviserCount,
                'acceptCount'=>$acceptCount,
                'doctorAnalise'=>$doctorAnalise,
                'analiseCount'=>$analiseCount,
                'operatorCount'=>$operatorCount,
                'openReceptionCount'=>$openReceptionCount
            ]);
        });

    }
}

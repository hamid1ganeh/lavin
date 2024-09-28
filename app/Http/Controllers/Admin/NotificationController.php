<?php

namespace App\Http\Controllers\Admin;

use App\Enums\seenStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use App\Models\Job;
use App\Models\Province;
use App\Models\Role;
use App\Models\ServiceDetail;
use App\Models\ServiceReserve;
use App\Models\UserAddress;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Level;
use App\Enums\Status;
use Auth;
use  Validator;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function user_index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.users.index');

        $notifications = Notification::with('seen','users','admin')
        ->where('type','user')
        ->withTrashed()
        ->filter()
        ->orderBy('created_at','desc')
        ->paginate(10)
        ->withQueryString();

        $admins = Admin::orderBy('fullname','asc')->get();
        $users = User::orderBy('firstname','asc')->orderBy('lastname','asc')->get();
        return  view('admin.notification.users.all',compact('notifications','users','admins'));
    }

    public function admin_index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.index');

        $notifications = Notification::with('seen','admins','admin')
            ->where('type','admin')
            ->withTrashed()
            ->filter()
            ->orderBy('created_at','desc')
            ->paginate(10)
            ->withQueryString();

        $admins = Admin::orderBy('fullname','asc')->get();
        return  view('admin.notification.admins.all',compact('notifications','admins'));
    }


    public function user_create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.users.create');

        $users = User::orderBy('firstname','asc')->orderBy('lastname','asc')->get();
        $levels = Level::orderBy('point','asc')->get();
        $jobs = Job::where('status',Status::Active)->orderBy('title','asc')->get();
        $provinces = Province::where('status',Status::Active)->orderBy('name','asc')->get();
        $cities = City::where('status',Status::Active)->orderBy('name','asc')->get();
        $serviceDetails = ServiceDetail::where('status',Status::Active)->orderBy('name','asc')->get();

        return view('admin.notification.users.create',compact('users','levels','jobs','provinces','cities','serviceDetails'));
    }

    public function admin_create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.create');

        $admins = Admin::withTrashed()
            ->orderBy('fullname','asc')
            ->get();

        $roles = Role::with('permissions')->filter()->orderBy('name','desc')->get();
        return view('admin.notification.admins.create',compact('admins','roles'));
    }

    public function store(Request $request)
    {

        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.create','notifications.user  s.create');

        $audience = array();

        if($request->type == "user"){

            $levels=[];
            if(isset($request->levels)) {
                $levels = User::whereIn('level_id',$request->levels)->pluck('id')->toArray();
            }

            $jobs=[];
            if(isset($request->jobs)) {
                $jobs = UserInfo::whereIn('job_id',$request->jobs)->pluck('user_id')->toArray();
            }

            $provinces=[];
            if(isset($request->provinces)) {
                $provinces = UserAddress::whereIn('province_id',$request->provinces)->pluck('user_id')->toArray();
            }

            $cities=[];
            if(isset($request->cities)) {
                $cities = UserAddress::whereIn('city_id',$request->cities)->pluck('user_id')->toArray();
            }

            $services=[];
            if(isset($request->services)) {
                $services = ServiceReserve::whereIn('detail_id',$request->services)->pluck('user_id')->toArray();
            }

            $audience = array_unique(array_merge(array_map('intval', $request->users), $levels, $jobs, $provinces,$cities,$services));

            if(isset($request->excepts)){
                $audience = array_diff($audience,array_map('intval', $request->excepts));
            }

        }elseif($request->type == "admin"){

            $admins = [];
            if(isset($request->roles))
            {
                $roles = $request->roles;
                $admins = Admin::whereHas('roles',function ($q) use ($roles){
                    $q->whereIn('id',$roles);
                })->pluck('id')->toArray();
            }

            $audience = array_unique(array_merge(array_map('intval', $request->admins), $admins));

            if(isset($request->excepts)){
                $audience = array_diff($audience,array_map('intval', $request->excepts));
            }
        }

        if(count($audience)>0)
        {
            Validator::extend('audience_validator',function(){return true;});
            $audience = array_map('intval',$audience);
            $audience = array_unique($audience);
        }
        else
        {
            Validator::extend('audience_validator',function(){return false;});
        }


        $request->validate([
            'title'=>"required|max:255|audience_validator",
            'message'=>"required|max:63000",
        ],
        [
            'title.required'=>'* عنوان پیام الزامی است.',
            'title.max'=>'* حداکثر طول عنوان پیام 255 کارکتر.',
            'title.audience_validator'=>'* مخاطب را مشخص کنید.',
            'message.required'=>'* متن پیام است.',
            'message.max'=>'* حداکثر طول متن پیام 63000 کارکتر.',
        ]);

       if($request->status == Status::Active || $request->status ==Status::Deactive)
       {
           $notification = new NotificationService();
           $notification->send($request->title,$request->message,$request->status,$audience,$request->type,isset($request->sms));
           toast('اعلان جدید ثبت شد.','success')->position('bottom-end');

           if($request->type == "user"){
               return redirect(route('admin.notifications.users.index'));
           }else if($request->type == "admin"){
               return redirect(route('admin.notifications.admins.index'));
            }
       }
    }



    public function update(Request $request,Notification $notification)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.update','notifications.users.update');

        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            $notification->status = $request->status;
            $notification->save();
        }
        return back();
    }


    public function destroy(Notification $notification)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.destroy','notifications.users.destroy');

        $notification->delete();
        toast('اعلان مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('notifications.admins.destroy','notifications.users.destroy');


        $notification = Notification::withTrashed()->find($id);
        $notification->restore();
        toast('اعلان مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

    public function usersfetch()
    {
        $array =array();
        $levels = request('levels');
        $levels = explode(',',$levels);

         for($i=0;$i<count($levels);$i++)
         {
            array_push($array,$levels[$i]);
         }

        $users = User::whereIn('level_id',$array)->get();
        $selected = $users->pluck('id');

        return response()->json(['selected'=>$selected],200);
    }

    public function index()
    {
        $notifications = Notification::with('admins')->
        whereHas('admins',function($q){
            $q->where('admin_id',Auth::guard('admin')->id());
        })
        ->where('type','admin')
        -> filter()
        ->orderBy('created_at','desc')
        ->paginate(10)
        ->withQueryString();

        return  view('admin.notification.all',compact('notifications'));
    }


    public function show(Notification $notification)
    {
        DB::table('notification_admin')->where('notification_id',$notification->id)
            ->where('admin_id',Auth::guard('admin')->id())->update([
                'seen' => seenStatus::seen
            ]);

        return view('admin.notification.show',compact('notification'));
    }
}

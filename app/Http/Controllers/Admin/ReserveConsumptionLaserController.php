<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReserveStatus;
use App\Http\Controllers\Controller;
use App\Models\LaserDevice;
use App\Models\ServiceLaser;
use App\Models\ServiceReserve;
use Illuminate\Http\Request;
use App\Models\ReserveConsumptionLaser;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class ReserveConsumptionLaserController extends Controller
{

    public function index(ServiceReserve  $reserve)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.index');

        $consumptions = ReserveConsumptionLaser::where('reserve_id',$reserve->id)->orderBy('created_at','desc')->get();
        $lasers = LaserDevice::orderBy('name','asc')->get();
        $laserServices = ServiceLaser::orderBy('title','asc')->get();
        return  view('admin.reserves.consumptions.laser',compact('consumptions','reserve','lasers','laserServices'));

    }



    public function store(ServiceReserve  $reserve,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.create');

        $request->validate(
            [
                'laser' => ['required','exists:laser_devices,id'],
                'start' => ['required'],
                'end' => ['required'],
                'shot_number' => ['required','integer'],
            ],
            [
                "laser.required" => "انتخاب دستگاه الزامی است.",
                "start.required" => "زمان شروع الزامی است.",
                "end.required" => "زمان پایان الزامی است.",
            ]);

        $start =  faToEn($request->start);
        $start = Jalalian::fromFormat('Y/m/d H:i:s', $start)->toCarbon("Y-m-d H:i:s");

        $end =  faToEn($request->end);
        $end = Jalalian::fromFormat('Y/m/d H:i:s', $end)->toCarbon("Y-m-d H:i:s");

        $device = LaserDevice::find($request->laser);

        $shot = $device->shot-$request->shot_number;

        if ($shot<0)
        {
            alert()->error('شماره شات مصرف شده بیشتر از موجودی شات دستگاه می باشد.');
            return back();
        }

        $consumption = new ReserveConsumptionLaser();
        $consumption->reserve_id = $reserve->id;
        $consumption->laser_device_id = $device->id;
        $consumption->service_laser_id = $request->service;
        $consumption->recent_shot_number = $device->shot;
        $consumption->shot_number = $request->shot_number;
        $consumption->shot = $shot;
        $consumption->started_at = $start;
        $consumption->finished_at = $end;
        $device->shot -=$shot;

        DB::transaction(function() use ($consumption,$device) {
            $consumption->save();
            $device->save();
        });

        toast('مصرفی جدید ثبت شد.','success')->position('bottom-end');

        return back();
    }


    public function update(ServiceReserve $reserve,ReserveConsumptionLaser $consumption,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.edit');

        if ( $reserve->status ==  ReserveStatus::done){
            return abort(403);
        }

        $request->validate(
            [
                'laser' => ['required','exists:laser_devices,id'],
                'start' => ['required'],
                'end' => ['required'],
                'shot_number' => ['required','integer'],
            ],
            [
                "laser.required" => "انتخاب دستگاه الزامی است.",
                "start.required" => "زمان شروع الزامی است.",
                "end.required" => "زمان پایان الزامی است.",
            ]);

        $start =  faToEn($request->start);
        $start = Jalalian::fromFormat('Y/m/d H:i:s', $start)->toCarbon("Y-m-d H:i:s");

        $end =  faToEn($request->end);
        $end = Jalalian::fromFormat('Y/m/d H:i:s', $end)->toCarbon("Y-m-d H:i:s");

        $lastDevice = $consumption->device;
        if($lastDevice->id != $request->laser){
            $device = LaserDevice::find($request->laser);
        }else{
            $device = $lastDevice;
        }

        $lastDevice->shot += $consumption->shot;

        $shot = $device->shot-$request->shot_number;

        if ($shot<0)
        {
            alert()->error('شماره شات مصرف شده بیشتر از موجودی شات دستگاه می باشد.');
            return back();
        }

        $consumption->laser_device_id = $device->id;
        $consumption->service_laser_id = $request->service;
        $consumption->recent_shot_number = $device->shot;
        $consumption->shot_number = $request->shot_number;
        $consumption->shot = $shot;
        $consumption->started_at = $start;
        $consumption->finished_at = $end;
        $device->shot -=$shot;

        DB::transaction(function() use ($consumption,$device,$lastDevice) {
            $consumption->save();
            $lastDevice->save();
            $device->save();
        });

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        return back();
    }


    public function delete(ServiceReserve $reserve,ReserveConsumptionLaser $consumption)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reserves.consumptions.destroy');

        if ( $reserve->status ==  ReserveStatus::done){
            return abort(403);
        }

        $device = $consumption->device;
        $device->shot += $consumption->shot;

        DB::transaction(function() use ($device, $consumption) {
            $device->save();
            $consumption->delete();
        });
        toast('موارد مصرفی حذف شد.','error')->position('bottom-end');
        return back();
    }
}

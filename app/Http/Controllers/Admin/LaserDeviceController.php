<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaserDevice;

class LaserDeviceController extends Controller
{
    function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.index');

        $lasers = LaserDevice::orderBy('name','asc')->get();
        return view('admin.warehousing.lasers.all',compact('lasers'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.create');

        return view('admin.warehousing.lasers.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.create');

        $request->validate(
            [
                "code"=>['required','max:255','unique:laser_devices'],
                "name"=>['required','max:255'],
                "brand"=>['required','max:255'],
                "model"=>['required','max:255'],
                "year"=>['required','min:4','max:4'],

            ],
            [
                "code.required"=>"کد دستگاه الزامی است",
                "code.max"=>"حداکثر طول مجاز برای کد دستگاه 255 کارکتر",
                "code.unique"=>"کد دستگاه قبلا ثبت شده است",
                "name.required"=>"نام دستگاه دستگاه الزامی است",
                "name.max"=>"حداکثر طول مجاز برای نام دستگاه 255 کارکتر",
                "brand.required"=>"برند دستگاه الزامی است",
                "brand.max"=>"حداکثر طول مجاز برای برند دستگاه 255 کارکتر",
                "model.required"=>"مدل دستگاه الزامی است",
                "model.max"=>"حداکثر طول مجاز برای مدل دستگاه 255 کارکتر",
                "year.required"=>"سال ساخت دستگاه الزامی است",
                "year.max"=>"حداکثر طول مجاز برای سال ساخت دستگاه 255 کارکتر",

            ]);

            LaserDevice::create([
                'code'=> $request->code,
                'name'=> $request->name,
                'brand'=> $request->brand,
                'model'=> $request->model,
                'year'=> $request->year
            ]);

        toast('دستگاه لیزر جدید ثبت شد.', 'success')->position('bottom-end');

        return redirect(route('admin.warehousing.lasers.index'));
    }


    public function edit(LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.edit');

        return view('admin.warehousing.lasers.edit',compact('laser'));
    }


    public function update(Request $request,LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.edit');

        $request->validate(
            [
                "code"=>['required','max:255','unique:laser_devices,code,'.$laser->id],
                "name"=>['required','max:255'],
                "brand"=>['required','max:255'],
                "model"=>['required','max:255'],
                "year"=>['required','min:4','max:4'],

            ],
            [
                "code.required"=>"کد دستگاه الزامی است",
                "code.max"=>"حداکثر طول مجاز برای کد دستگاه 255 کارکتر",
                "code.unique"=>"کد دستگاه قبلا ثبت شده است",
                "name.required"=>"نام دستگاه دستگاه الزامی است",
                "name.max"=>"حداکثر طول مجاز برای نام دستگاه 255 کارکتر",
                "brand.required"=>"برند دستگاه الزامی است",
                "brand.max"=>"حداکثر طول مجاز برای برند دستگاه 255 کارکتر",
                "model.required"=>"مدل دستگاه الزامی است",
                "model.max"=>"حداکثر طول مجاز برای مدل دستگاه 255 کارکتر",
                "year.required"=>"سال ساخت دستگاه الزامی است",
                "year.max"=>"حداکثر طول مجاز برای سال ساخت دستگاه 255 کارکتر",

            ]);

        $laser->update([
                'code'=> $request->code,
                'name'=> $request->name,
                'brand'=> $request->brand,
                'model'=> $request->model,
                'year'=> $request->year
            ]);

        toast('بروزررسانی انجام  شد.', 'success')->position('bottom-end');

        return redirect(route('admin.warehousing.lasers.index'));
    }

    public function destroy(LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.destroy');

        $laser->delete();
        toast('دستگاه مورد نظر حذف  شد.', 'error')->position('bottom-end');
        return back();
    }
}

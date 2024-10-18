<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceLaser;

class ServiceLaserController extends Controller
{

    public function index()
    {
        $lasers = ServiceLaser::orderBy('created_at', 'desc')->withTrashed()->get();
        return  view('admin.services.lasers.all', compact('lasers'));

    }

    public function create()
    {
        return  view('admin.services.lasers.create');
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required','max:255'],
                'skin' => ['required','max:255'],
                'weight' => ['required','max:255'],
                'shot' => ['required','integer'],
            ],
            [
                "title.required" => "عنوان سرویس لیزر الزامی است",
                "title.max" => "حداکثر طول مجاز عنوان لیزر 255 کارکتر است.",
                "skin.required" => " رنگ پوست الزامی است",
                "skin.max" => "حداکثر طول مجاز  رنگ پوست 255 کارکتر است.",
                "weight.required" => " رنج وزنی الزامی است",
                "weight.max" => "حداکثر طول مجاز رنح وزنی 255 کارکتر است.",
                "shot.required" => " تعداد شات الزامی است",
            ]);

        ServiceLaser::create([
            'title'=> $request->title,
            'skin'=> $request->skin,
            'weight'=> $request->weight,
            'shot'=> $request->shot,
        ]);

        toast('سرویس جدید ثبت شد','success')->position('bottom-end');

        return redirect()->route('admin.services.lasers.index');
    }


    public function edit(ServiceLaser  $laser)
    {
        return  view('admin.services.lasers.edit',compact('laser'));
    }


    public function update(ServiceLaser  $laser,Request $request)
    {
        $request->validate(
            [
                'title' => ['required','max:255'],
                'skin' => ['required','max:255'],
                'weight' => ['required','max:255'],
                'shot' => ['required','integer'],
            ],
            [
                "title.required" => "عنوان سرویس لیزر الزامی است",
                "title.max" => "حداکثر طول مجاز عنوان لیزر 255 کارکتر است.",
                "skin.required" => " رنگ پوست الزامی است",
                "skin.max" => "حداکثر طول مجاز  رنگ پوست 255 کارکتر است.",
                "weight.required" => " رنج وزنی الزامی است",
                "weight.max" => "حداکثر طول مجاز رنح وزنی 255 کارکتر است.",
                "shot.required" => " تعداد شات الزامی است",
            ]);

        $laser->update([
            'title'=> $request->title,
            'skin'=> $request->skin,
            'weight'=> $request->weight,
            'shot'=> $request->shot,
        ]);

        toast('بروزرسانی انجام شد.','success')->position('bottom-end');

        return redirect()->route('admin.services.lasers.index');
    }


    public function destroy(ServiceLaser  $laser)
    {
        $laser->delete();
        toast('سرویس لیزر مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        $laser = ServiceLaser::onlyTrashed()->findOrFail($id);
        $laser->restore();
        toast('سرویس لیزر مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }


}

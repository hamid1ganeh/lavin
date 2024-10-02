<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\CityPart;
use App\Models\Province;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class AreaController extends Controller
{

    public function index(Province $province,City $city,CityPart $part)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.index');

        $areas = Area::where('part_id',$part->id)->withTrashed()->orderBy('name','asc')->get();
        return view('admin.provinces.city.part.area.all',compact('areas','province','city','part'));
    }

    public function create(Province $province,City $city,CityPart $part)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.create');

        return view('admin.provinces.city.part.area.create',compact('province','city','part'));
    }


    public function store(Province $province,City $city,CityPart $part,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.create');

        $pr = Area::where('part_id',$part->id)->where('name',$request->name)->first();
        if($pr==null)
        {
            Validator::extend('unique_validator',function(){return true;});
        }
        else
        {
            Validator::extend('unique_validator',function(){return false;});
        }

        $request->validate(
            [
                'name' => ['required','max:255','unique_validator'],
            ],
            [
                "name.required" => "نام محله الزامی است.",
                "name.max" => "حداکثر طول مجاز برای نام منطقه 255 کارکتر است.",
                "name.unique_validator"=> "این منطقه قبلا برای این محله ثبت شده است.",
            ]);

        if($request->status==Status::Active || $request->status==Status::Deactive)
        {
            Area::create(['name'=>$request->name,
                          'slug'=>SlugService::createSlug(Area::class, 'slug', $request->name),
                          'part_id'=>$part->id,
                          'status'=>$request->status]);

            toast('منطقه جدید ثبت شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.provinces.cities.parts.areas.index',[$province,$city,$part]));
    }



    public function edit(Province $province,City $city,CityPart $part,Area $area)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.edit');

        return view('admin.provinces.city.part.area.edit',compact('province','city','part','area'));
    }


    public function update(Province $province,City $city,CityPart $part,Area $area,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.edit');

        $pr = Area::where('part_id',$part->id)->where('name',$request->name)->where('id','<>',$part->$area)->first();
        if($pr==null)
        {
            Validator::extend('unique_validator',function(){return true;});
        }
        else
        {
            Validator::extend('unique_validator',function(){return false;});
        }

        $request->validate(
        [
            'name' => ['required','max:255','unique_validator'],
        ],
        [
            "name.required" => "نام محله الزامی است.",
            "name.max" => "حداکثر طول مجاز برای نام محله 255 کارکتر است.",
            "name.unique_validator"=> "این محله قبلا برای این منطقه ثبت شده است.",
        ]);

        if($request->status==Status::Active || $request->status==Status::Deactive)
        {
            $area->update([
                'name'=>$request->name,
                'slug'=>SlugService::createSlug(Area::class, 'slug', $request->name),
                'status'=>$request->status,
            ]);

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.provinces.cities.parts.areas.index',[$province,$city,$part]));
    }


    public function destroy(Province $province,City $city,CityPart $part,Area $area)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.delete');

        $area->delete();
        toast('محله مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle(Province $province,City $city,CityPart $part,$id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('provinces.cities.parts.areas.recycle');

        $area = Area::withTrashed()->find($id);
        $area->restore();
        toast('محله مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}

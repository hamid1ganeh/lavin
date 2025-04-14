<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.index');

        $brands = Brand::orderby('name','ASC')->withTrashed()->get();
        return view('admin.brand.all',compact('brands'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.create');

        return view('admin.brand.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.create');

        $request->validate(
            [
                'name' => ['required','max:255','unique:brands'],
            ],
            [
                "name.required" => "* عنوان برند را وارد نمایید.",
                "name.max" => "* حداکثر طول مجاز برای عنوان برند 255 کارکتر است.",
                "name.unique"=> "* این برند قبلا ثبت شده است.",
            ]);


        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            Brand::create(
                [
                    "name"=>$request->name,
                    'status'=>$request->status
                ]
            );
        }

        alert()->success('برند جدید ثبت شد.', 'تبریک');

        return  redirect(route('admin.brands.index'));
    }


    public function edit(Brand $brand)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.edit');

        return view('admin.brand.edit',compact('brand'));
    }


    public function update(Request $request, Brand $brand)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.edit');

        $request->validate([
            "name"=>['required','max:255',Rule::unique('brands')->ignore($brand->id)],
        ],
            [
                "name.required" => "* عنوان برند را وارد نمایید.",
                "name.max" => "* حداکثر طول مجاز برای عنوان برند 255 کارکتر است.",
                "name.unique"=> "* این برند قبلا ثبت شده است.",
            ]);

        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            $brand->update(
                [
                    "name" =>  $request->name,
                    'status'=>$request->status
                ]);
        }

        toastr()->info('عملایت بروزرسانی با موفقیت انجام شد');

        return redirect(route('admin.brands.index'));
    }


    public function destroy(Brand $brand)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.delete');

        $brand->delete();
        toastr()->error("برند ".$brand->name." حذف شد. ");
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('brands.delete');

        $branch = Brand::withTrashed()->find($id);
        $branch->restore();
        toast('برند مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}

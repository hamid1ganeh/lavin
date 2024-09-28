<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{

    public function index()
    {
       //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.index');

        $branchs = Branch::orderby('name','ASC')->withTrashed()->get();
        return view('admin.branch.all',compact('branchs'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.create');

        return view('admin.branch.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.create');

        $request->validate(
            [
                'name' => ['required','max:255','unique:branchs'],
            ],
            [
                "name.required" => "* عنوان شعبه را وارد نمایید.",
                "name.max" => "* حداکثر طول مجاز برای عنوان شعبه 255 کارکتر است.",
                "name.unique"=> "* این شعبه قبلا ثبت شده است.",
            ]);


        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            Branch::create(
                [
                    "name"=>$request->name,
                    'status'=>$request->status
                ]
            );
        }

        alert()->success('شعبه جدید ثبت شد.', 'تبریک');

        return  redirect(route('admin.branchs.index'));
    }


    public function show($id)
    {
        //
    }


    public function edit(Branch $branch)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.edit');

        return view('admin.branch.edit',compact('branch'));
    }


    public function update(Request $request,Branch $branch)
    {
         //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.edit');

        $request->validate([
            "name"=>['required','max:255',Rule::unique('branchs')->ignore($branch->id)],
        ],
            [
                "name.required" => "* عنوان شعبه را وارد نمایید.",
                "name.max" => "* حداکثر طول مجاز برای عنوان شعبه 255 کارکتر است.",
                "name.unique"=> "* این شعبه قبلا ثبت شده است.",
            ]);

        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            $branch->update(
                [
                    "name" =>  $request->name,
                    'status'=>$request->status
                ]);
        }

        toastr()->info('عملایت بروزرسانی با موفقیت انجام شد');

        return redirect(route('admin.branchs.index'));
    }

    public function destroy(Branch $branch)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.delete');

        $branch->delete();
        toastr()->error("شعبه ".$branch->name." حذف شد. ");
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('branchs.delete');


        $branch = Branch::withTrashed()->find($id);
        $branch->restore();
        toast('شعبه مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}

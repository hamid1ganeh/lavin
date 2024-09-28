<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobMainCategory;
use App\Models\EmploymentMainCategory;
class EmploymentMainCateoryController extends Controller
{
    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.index');

        $categories = EmploymentMainCategory::withTrashed()->orderBy('title','asc')->get();
        return view('admin.employments.categories.main.all',compact('categories'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.create');

        return view('admin.employments.categories.main.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.create');

        $request->validate(
        [
            'title' => ['required','max:255','unique:employment_main_categories']
        ],
        [
            'title.required' => 'عنوان دسته بندی الزامی است.',
            'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
            'title.unique' => 'عنوان دسته بندی تکرای است.'
        ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            EmploymentMainCategory::create([
                'title'=>$request->title,
                'status'=> Status::Active
            ]);

            toast('دسته بندی جدید افزوده شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.employments.categories.main.index'));
    }

    public function edit(EmploymentMainCategory $main)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.edit');

        return view('admin.employments.categories.main.edit',compact('main'));
    }

    public function update(EmploymentMainCategory $main,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.edit');

        $request->validate(
            [
                'title' => ['required','max:255','unique:employment_main_categories,title,'.$main->id]
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
                'title.unique' => 'عنوان دسته بندی تکرای است.'
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $main->title =$request->title;
            $main->Status =$request->status;
            $main->save();
           toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.employments.categories.main.index'));
    }

    public function destroy(EmploymentMainCategory $main)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.destroy');

        $main->delete();
        toast('دسته بندی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }


    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.main.recycle');

        $sub = EmploymentMainCategory::withTrashed()->find($id);
        $sub->restore();
        toast(' دسته مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }



}

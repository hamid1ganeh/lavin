<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\EmploymentMainCategory;
use App\Models\EmploymentSubCategory;
use Illuminate\Http\Request;

class EmploymentSubCateoryController extends Controller
{
   public function index(EmploymentMainCategory $main)
   {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('employments.categories.sub.index');

       $categories = EmploymentSubCategory::withTrashed()
           ->where('main_id',$main->id)
           ->orderBy('title','asc')->get();

       return view('admin.employments.categories.sub.all',compact('categories','main'));
   }

    public function create(EmploymentMainCategory $main)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.create');

        return view('admin.employments.categories.sub.create',compact('main'));
    }

    public function store(EmploymentMainCategory $main,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.create');

        $request->validate(
        [
            'title' => ['required','max:255']
        ],
        [
            'title.required' => 'عنوان دسته بندی الزامی است.',
            'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
        ]);

        if(EmploymentSubCategory::where('main_id',$main->id)->where('title',$request->title)->exists()){
            $msg = " زیردسته ".$request->title.' قبلا برای دسته اصلی '.$main->title.' ثبت شده است.';
            alert()->error($msg);
            return back()->withInput();
        }

        if(in_array($request->status ,status::getStatusList))
        {
            EmploymentSubCategory::create([
                'main_id'=> $main->id,
                'title'=> $request->title,
                'status'=> $request->status,
            ]);
            toast('زیردسته جدید افزوده شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.employments.categories.sub.index',$main));

    }

    public function edit(EmploymentMainCategory $main,EmploymentSubCategory $sub)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.edit');

        return view('admin.employments.categories.sub.edit',compact('main','sub'));
    }

    public function update(EmploymentMainCategory $main,EmploymentSubCategory $sub,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.edit');

        $request->validate(
            [
                'title' => ['required','max:255']
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
            ]);

        if(EmploymentSubCategory::where('main_id',$main->id)->where('title',$request->title)->where('id','<>',$sub->id)->exists()){
            $msg = " زیردسته ".$request->title.' قبلا برای دسته اصلی '.$main->title.' ثبت شده است.';
            alert()->error($msg);
            return back()->withInput();
        }

        if(in_array($request->status ,status::getStatusList))
        {
            $sub->title = $request->title;
            $sub->status = $request->status;
            $sub->save();
            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.employments.categories.sub.index',$main));
    }

    public function destroy(EmploymentMainCategory $main, EmploymentSubCategory $sub)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.destroy');

        $sub->delete();
        toast(' زیردسته مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle(EmploymentMainCategory $main,$id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.categories.sub.recycle');

        $sub = EmploymentSubCategory::withTrashed()->find($id);
        $sub->restore();
        toast(' زیردسته مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

    public function fetch_sub()
    {
        $main = request('main');
        $sub = EmploymentSubCategory::where('status',Status::Active)->where('main_id',$main)->get();
        return response()->json(['sub'=>$sub]);
    }

}

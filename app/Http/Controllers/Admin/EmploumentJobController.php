<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\EmploymentJob;
use App\Models\EmploymentMainCategory;
use App\Models\EmploymentSubCategory;
use Illuminate\Http\Request;

class EmploumentJobController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.index');

        $jobs = EmploymentJob::orderBy('title','asc')
                                ->withTrashed()
                                ->paginate(10)
                                ->withQueryString();

        return view('admin.employments.jobs.all',compact('jobs'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.create');

        $mains = EmploymentMainCategory::where('status',Status::Active)->orderBy('title','asc')->get();
        return view('admin.employments.jobs.create',compact('mains'));
    }

    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.create');

        $request->validate(
        [
            'title' => ['required','max:255'],
            'main_cat_id' => ['required','exists:employment_main_categories,id'],
            'sub_cat_id' => ['nullable','exists:employment_sub_categories,id'],
        ],
        [
            'title.required' => 'عنوان دسته بندی الزامی است.',
            'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
            'main_cat_id.required' => ' دسته بندی اصلی الزامی است.',
        ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
           EmploymentJob::create([
               'main_cat_id' => $request->main_cat_id,
               'sub_cat_id' => $request->sub_cat_id,
               'title' => $request->title,
               'status' => $request->status,
           ]);

            toast('شغل جدید اضافه شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.employments.jobs.index'));
    }


    public function edit(EmploymentJob $job)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.edit');

        $mains = EmploymentMainCategory::where('status',Status::Active)->orderBy('title','asc')->get();
        $subCats = EmploymentSubCategory::where('main_id',$job->main_cat_id)->where('status',Status::Active)->orderBy('title','asc')->get();
        return view('admin.employments.jobs.edit',compact('mains','job','subCats'));
    }


    public function update(Request $request, EmploymentJob $job)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.edit');

        $request->validate(
            [
                'title' => ['required','max:255'],
                'main_cat_id' => ['required','exists:employment_main_categories,id'],
                'sub_cat_id' => ['nullable','exists:employment_sub_categories,id'],
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
                'main_cat_id.required' => ' دسته بندی اصلی الزامی است.',
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $job->update([
                'main_cat_id' => $request->main_cat_id,
                'sub_cat_id' => $request->sub_cat_id,
                'title' => $request->title,
                'status' => $request->status,
            ]);

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.employments.jobs.index'));

    }

    public function destroy(EmploymentJob $job)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.destroy');

        $job->delete();
        toast('شغل مورد نظر حذف شد.', 'error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.jobs.recycle');

        $job = EmploymentJob::withTrashed()->find($id);
        $job->restore();
        toast(' شغل مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

}

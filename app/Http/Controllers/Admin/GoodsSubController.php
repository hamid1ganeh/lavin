<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploymentSubCategory;
use Illuminate\Http\Request;
use App\Models\GoodsSubCategory;
use App\Models\GoodsMainCategory;
use App\Enums\status;

class GoodsSubController extends Controller
{

    public function index(GoodsMainCategory $main)
    {
         //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.index');

        $categories = GoodsSubCategory::withTrashed()
            ->where('main_id',$main->id)
            ->orderBy('title','asc')->get();

        return view('admin.warehousing.categories.sub.all',compact('categories','main'));
    }


    public function create(GoodsMainCategory $main)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.create');

        return view('admin.warehousing.categories.sub.create',compact( 'main'));
    }


    public function store(GoodsMainCategory $main,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.create');

        $request->validate(
            [
                'title' => ['required','max:255']
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
            ]);

        if(GoodsSubCategory::where('main_id',$main->id)->where('title',$request->title)->exists()){
            $msg = " زیردسته ".$request->title.' قبلا برای دسته اصلی '.$main->title.' ثبت شده است.';
            alert()->error($msg);
            return back()->withInput();
        }

        if(in_array($request->status ,status::getStatusList))
        {
            GoodsSubCategory::create([
                'main_id'=> $main->id,
                'title'=> $request->title,
                'status'=> $request->status,
            ]);
            toast('زیردسته جدید افزوده شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.warehousing.categories.sub.index',$main));

    }


    public function edit(GoodsMainCategory $main,GoodsSubCategory $sub)
    {
        if ($sub->id == 1){
            abort(403);
        }

        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.edit');

        return view('admin.warehousing.categories.sub.edit',compact( 'main','sub'));
    }


    public function update(GoodsMainCategory $main,GoodsSubCategory $sub,Request $request)
    {
        if ($sub->id == 1){
            abort(403);
        }


        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.edit');

        $request->validate(
            [
                'title' => ['required','max:255']
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
            ]);

        if(GoodsSubCategory::where('main_id',$main->id)->where('title',$request->title)->where('id','<>',$sub->id)->exists()){
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

        return redirect(route('admin.warehousing.categories.sub.index',$main));
    }

    public function destroy(GoodsMainCategory $main, GoodsSubCategory $sub)
    {
        if ($sub->id == 1){
            abort(403);
        }


        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.destroy');

        $sub->delete();
        toast(' زیردسته مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }


    public function recycle(GoodsMainCategory $main,$id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.sub.recycle');

        $sub = GoodsSubCategory::withTrashed()->find($id);
        $sub->restore();
        toast(' زیردسته مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

}

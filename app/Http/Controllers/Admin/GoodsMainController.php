<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodsMainCategory;
use App\Models\GoodsSubCategory;

class GoodsMainController extends Controller
{

    public function index()
    {
       //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.index');

        $categories = GoodsMainCategory::withTrashed()->orderBy('title','asc')->get();
        return view('admin.warehousing.categories.main.all',compact('categories'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.create');

        return view('admin.warehousing.categories.main.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.create');

        $request->validate(
            [
                'title' => ['required','max:255','unique:goods_main_categories']
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
                'title.unique' => 'عنوان دسته بندی تکرای است.'
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            GoodsMainCategory::create([
                'title'=>$request->title,
                'status'=> Status::Active
            ]);

            toast('دسته بندی جدید افزوده شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.categories.main.index'));
    }


    public function edit(GoodsMainCategory $main)
    {
        if ($main->id == 1){
            abort(403);
        }
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.edit');

        return view('admin.warehousing.categories.main.edit',compact('main'));
    }


    public function update(Request $request, GoodsMainCategory $main)
    {
        if ($main->id == 1){
            abort(403);
        }

        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.edit');

        $request->validate(
            [
                'title' => ['required','max:255','unique:goods_main_categories,title,'.$main->id]
            ],
            [
                'title.required' => 'عنوان دسته بندی الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان دسته بندی 255 کارکتر می باشد.',
                'title.unique' => 'عنوان دسته بندی تکرای است.'
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $main->title = $request->title;
            $main->Status = $request->status;
            $main->save();
            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.warehousing.categories.main.index'));
    }


    public function destroy(GoodsMainCategory $main)
    {
        if ($main->id == 1){
            abort(403);
        }

        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.destroy');

        $main->delete();
        toast('دسته بندی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.categories.main.recycle');

        $main = GoodsMainCategory::withTrashed()->find($id);
        $main->restore();
        toast(' دسته بندی مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }

    public function fetch_sub()
    {
        $main = request('main');
        $sub = GoodsSubCategory::where('status',Status::Active)->where('main_id',$main)->get();
        return response()->json(['sub'=>$sub]);
    }
}

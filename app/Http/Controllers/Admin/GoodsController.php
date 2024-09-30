<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\GoodsSubCategory;
use App\Models\GoodsMainCategory;
use Illuminate\Http\Request;
use App\Models\Goods;
use Morilog\Jalali\Jalalian;
use App\Services\FunctionService;

class GoodsController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.index');

         $goods = Goods::orderBy('title','asc')
             ->withTrashed()
             ->paginate(10)
             ->withQueryString();

         return view('admin.warehousing.goods.all',compact('goods'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.create');

        $mains = GoodsMainCategory::where('status',Status::Active)->orderBy('title','asc')->get();
        $subs = [];
        if (!is_null(old('main_cat_id')))
        {
            $subs = GoodsSubCategory::where('main_id',old('main_cat_id'))->where('status',Status::Active)->orderBy('title')->get();
        }
        return view('admin.warehousing.goods.create',compact('mains','subs'));
    }

    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.create');

        $request->validate(
            [
                'title' => ['required','max:255','unique:goods'],
                'code' => ['nullable','max:255'],
                'unit' => ['required'],
                'stock' => ['required','integer'],
                'price' => ['required','integer'],
                'description' => ['nullable','max:255'],
                'main_cat_id' => ['required','exists:goods_main_categories,id'],
                'sub_cat_id' => ['nullable','exists:goods_sub_categories,id'],
            ],
            [
                'title.required' => 'عنوان  کالا الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان کالا 255 کارکتر می باشد.',
                'title.unique' => 'این کالا قبلا ثبت شده است.',
                'code.max' => 'حداکثر  طول مجاز کد کالا 255 کارکتر می باشد.',
                'unit.required' => 'واحد   کالا الزامی است.',
                'stock.required' => 'موجودی  کالا الزامی است.',
                'stock.numeric' => 'موجودی کالا میبایست یک عدد مثبت باشد.',
                'price.required' => 'قیمت  کالا الزامی است.',
                'description.max' => 'حداکثر  طول مجاز توضیحات کالا 255 کارکتر می باشد.',
                'price.numeric' => 'قیمت کالا میبایست یک عدد مثبت باشد.',
                'main_cat_id.required' => ' دسته بندی اصلی الزامی است.',
            ]);

        $expireDate=null;
        $fuctionService = new FunctionService();
        if(isset($request->expireDate)){
            $expireDate =  $fuctionService->faToEn($request->expireDate);
            $expireDate = Jalalian::fromFormat('Y/m/d', $expireDate)->toCarbon("Y-m-d");
        }

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            Goods::create([
                'title' => $request->title,
                'code' => $request->code,
                'unit' => $request->unit,
                'stock' => $request->stock,
                'price' => $request->price,
                'expire_date' =>$expireDate,
                'description' => $request->description,
                'main_cat_id' => $request->main_cat_id,
                'sub_cat_id' => $request->sub_cat_id,
                'status' => $request->status,
            ]);

            toast('کالا جدید اضافه شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.warehousing.goods.index'));
    }


    public function edit(Goods $good)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.edit');

        $mains = GoodsMainCategory::where('status',Status::Active)->orderBy('title','asc')->get();
        $subs = [];
        if (!is_null(old('main_cat_id')))
        {
            $subs = GoodsSubCategory::where('main_id',old('main_cat_id'))->where('status',Status::Active)->orderBy('title')->get();
        }else{
            $subs = GoodsSubCategory::where('main_id',$good->main_cat_id)->orderBy('title')->get();
        }
        return view('admin.warehousing.goods.edit',compact('good','mains','subs'));
    }

    public function update(Goods $good,Request $request)
    {
        $request->validate(
            [
                'title' => ['required','max:255','unique:goods,title,'.$good->id],
                'code' => ['nullable','max:255'],
                'unit' => ['required'],
                'stock' => ['required','integer'],
                'price' => ['required','integer'],
                'description' => ['nullable','max:255'],
                'main_cat_id' => ['required','exists:goods_main_categories,id'],
                'sub_cat_id' => ['nullable','exists:goods_sub_categories,id'],
            ],
            [
                'title.required' => 'عنوان  کالا الزامی است.',
                'title.unique' => 'این کالا قبلا ثبت شده است.',
                'title.max' => 'حداکثر  طول مجاز عنوان کالا 255 کارکتر می باشد.',
                'code.max' => 'حداکثر  طول مجاز کد کالا 255 کارکتر می باشد.',
                'unit.required' => 'واحد   کالا الزامی است.',
                'stock.required' => 'موجودی  کالا الزامی است.',
                'stock.numeric' => 'موجودی کالا میبایست یک عدد مثبت باشد.',
                'price.required' => 'قیمت  کالا الزامی است.',
                'description.max' => 'حداکثر  طول مجاز توضیحات کالا 255 کارکتر می باشد.',
                'price.numeric' => 'قیمت کالا میبایست یک عدد مثبت باشد.',
                'main_cat_id.required' => ' دسته بندی اصلی الزامی است.',
            ]);

        $expireDate=null;
        $fuctionService = new FunctionService();
        if(isset($request->expireDate)){
            $expireDate =  $fuctionService->faToEn($request->expireDate);
            $expireDate = Jalalian::fromFormat('Y/m/d', $expireDate)->toCarbon("Y-m-d");
        }

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $good->update([
                    'title' => $request->title,
                    'code' => $request->code,
                    'unit' => $request->unit,
                    'stock' => $request->stock,
                    'price' => $request->price,
                    'expire_date' =>$expireDate,
                    'description' => $request->description,
                    'main_cat_id' => $request->main_cat_id,
                    'sub_cat_id' => $request->sub_cat_id,
                    'status' => $request->status,
             ]);

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.warehousing.goods.index'));
    }

    public function destroy(Goods $good)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.destroy');

        $good->delete();
        toast('کالا مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.goods.recycle');

        $good = Goods::withTrashed()->find($id);
        $good->restore();
        toast('کالا مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}

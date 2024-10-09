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
                'brand' => ['required','max:255'],
                'code' => ['nullable','max:255','unique:goods'],
                'unit' => ['required'],
                'value_per_count' => ['required','integer'],
                'count_stock' => ['required','integer'],
                'price' => ['required','integer'],
                'description' => ['nullable','max:255'],
                'main_cat_id' => ['required','exists:goods_main_categories,id'],
                'sub_cat_id' => ['nullable','exists:goods_sub_categories,id'],
            ],
            [
                'title.required' => 'عنوان  کالا الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان کالا 255 کارکتر می باشد.',
                'title.unique' => 'این کالا قبلا ثبت شده است.',
                'brand.required' => 'برند  کالا الزامی است.',
                'brand.max' => 'حداکثر  طول مجاز برند کالا 255 کارکتر می باشد.',
                'code.max' => 'حداکثر  طول مجاز کد کالا 255 کارکتر می باشد.',
                'code.unique' => 'این کد قبلا ثبت شده است.',
                'unit.required' => 'واحد کالا الزامی است.',
                'value_per_count.required' => 'حجم واحد در هر عدد  الزامی است.',
                'value_per_count.numeric' => 'حجم واحد در هر عدد میبایست یک عدد مثبت باشد.',
                'count_per_box.required' => ' تعداد در هر جبعه الزامی است.',
                'count_per_box.numeric' => ' تعداد در هر جبعه  میبایست یک عدد مثبت باشد.',
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
            $unitStock = $request->value_per_count*$request->count_stock;
            
            Goods::create([
                'title' => $request->title,
                'brand' => $request->brand,
                'code' => $request->code,
                'main_cat_id' => $request->main_cat_id,
                'sub_cat_id' => $request->sub_cat_id,
                'unit' => $request->unit,
                'value_per_count' => $request->value_per_count,
                'count_stock' => $request->count_stock,
                'unit_stock' => $unitStock,
                'price' => $request->price,
                'description' => $request->description,
                'expire_date' =>$expireDate,
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
                'brand' => ['required','max:255'],
                'code' => ['nullable','max:255','unique:goods,code,'.$good->id],
                'unit' => ['required'],
                'value_per_count' => ['required','integer'],
                'count_stock' => ['required','integer'],
                'unit_stock' => ['required','integer'],
                'price' => ['required','integer'],
                'description' => ['nullable','max:255'],
                'main_cat_id' => ['required','exists:goods_main_categories,id'],
                'sub_cat_id' => ['nullable','exists:goods_sub_categories,id'],
            ], [
                'title.required' => 'عنوان  کالا الزامی است.',
                'title.max' => 'حداکثر  طول مجاز عنوان کالا 255 کارکتر می باشد.',
                'title.unique' => 'این کالا قبلا ثبت شده است.',
                'brand.required' => 'برند  کالا الزامی است.',
                'brand.max' => 'حداکثر  طول مجاز برند کالا 255 کارکتر می باشد.',
                'code.max' => 'حداکثر  طول مجاز کد کالا 255 کارکتر می باشد.',
                'code.unique' => 'این کد قبلا ثبت شده است.',
                'unit.required' => 'واحد کالا الزامی است.',
                'value_per_count.required' => 'حجم واحد در هر عدد  الزامی است.',
                'value_per_count.numeric' => 'حجم واحد در هر عدد میبایست یک عدد مثبت باشد.',
                'count_stock.required' => '  موجودی تعداد در انبار الزامی است.',
                'count_stock.numeric' => 'موجودی تعداد در انبار میبایست یک عدد مثبت باشد.',
                'unit_stock.required' => 'موجودی واحد در انبار الزامی است.',
                'unit_stock.numeric' => ' موجودی واحد در انبار میبایست یک عدد مثبت باشد.',
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
                'brand' => $request->brand,
                'code' => $request->code,
                'main_cat_id' => $request->main_cat_id,
                'sub_cat_id' => $request->sub_cat_id,
                'unit' => $request->unit,
                'value_per_count' => $request->value_per_count,
                'count_stock' => $request->count_stock,
                'unit_stock' => $request->unit_stock,
                'price' => $request->price,
                'description' => $request->description,
                'expire_date' =>$expireDate,
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

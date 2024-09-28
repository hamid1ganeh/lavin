<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use  Validator;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Enums\Status;

class ProductCategoryController extends Controller
{
    private $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService();
    }
    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.index');

       $categories = ProductCategory::where('parent_id',0)
       ->orderBy('name','asc')
       ->paginate(10)
       ->withQueryString();

       return view('admin.shop.products.categories.all',compact('categories'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.create');

        return view('admin.shop.products.categories.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.create');

        $category = ProductCategory::where('parent_id',0)->where('name',$request->name)->first();
        if($category==null)
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
            'status' => ['required','integer'],
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],
        [
            "name.required" => "* عنوان دسته را وارد نمایید.",
            "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
            "name.unique_validator"=> "* این دسته قبلا ثبت شده است.",
            'thumbnail.required' => '* تصویر شاخص الزامی است.',
            "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
            "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
        ]);

       $category= ProductCategory::create([
            "name"=>$request->name,
            'slug' => SlugService::createSlug(ProductCategory::class, 'slug', $request->name),
            "status"=>$request->status,
        ]);

        $path = $this->imageService->path();
        $thumbnail = $this->imageService->upload($request->thumbnail,[
            'original' => [
                'w'=>getimagesize($request->thumbnail)[0],
                'h'=>getimagesize($request->thumbnail)[1],
            ],
            'large' => [
                'w'=>1023,
                'h'=>507,
            ],
            'medium' => [
                'w'=>370,
                'h'=>370,
            ],
            'thumbnail' => [
                'w'=>150,
                'h'=>54,
            ],
        ],$path);

         $category->thumbnail()->create([
             'title' => $request->title,
             'alt' => $request->title,
             'name' => $thumbnail,
             'path'=>$path
         ]);

        toast('.دسته بندی جدید ثبت شد','success')->position('bottom-end');
        return redirect(route('admin.shop.products.categories.index'));
    }


    public function show($id)
    {
        //
    }


    public function edit(ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.edit');

        return view('admin.shop.products.categories.edit',compact('category'));
    }

    public function update(Request $request,ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.edit');

        $cat = ProductCategory::where('parent_id',0)->where('name',$request->name)->where('id','<>',$category->id)->first();
        if($cat==null)
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
            'status' => ['required','integer']
        ],
        [
            "name.required" => "* عنوان دسته را وارد نمایید.",
            "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
            "name.unique_validator"=> "* این دسته قبلا ثبت شده است.",
        ]);

        $category->update([
            "name"=>$request->name,
            'slug' => SlugService::createSlug(ProductCategory::class, 'slug', $request->name),
            "status"=>$request->status,
        ]);

        toast('.بروزرسانی انجام شد','success')->position('bottom-end');
        return redirect(route('admin.shop.products.categories.index'));
    }


    public function destroy(ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.delete');

        $category->delete();
        toast('دسته بندی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function subindex(ProductCategory $parent)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.index');

       $categories = ProductCategory::where('parent_id',$parent->id)->orderBy('name','asc')->paginate(10);
       return view('admin.shop.products.categories.sub.all',compact('categories','parent'));
    }


    public function subcreate(ProductCategory $parent)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.create');

        return view('admin.shop.products.categories.sub.create',compact('parent'));
    }


    public function substore(Request $request,ProductCategory $parent)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.create');

        $category = ProductCategory::where('parent_id',$parent->id)->where('name',$request->name)->first();
        if($category==null)
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
            'status' => ['required','integer']
        ],
        [
            "name.required" => "* عنوان دسته را وارد نمایید.",
            "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
            "name.unique_validator"=> "* این دسته قبلا ثبت شده است.",
        ]);

        ProductCategory::create([
            'parent_id'=>$parent->id,
            "name"=>$request->name,
            'slug' => SlugService::createSlug(ProductCategory::class, 'slug', $request->name),
            "status"=>$request->status,
        ]);

        toast('.زیردسته جدید ثبت شد','success')->position('bottom-end');
        return redirect(route('admin.shop.products.categories.sub.index',$parent));
    }

    public function subedit(ProductCategory $parent,ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.edit');

        return view('admin.shop.products.categories.sub.edit',compact('parent','category'));
    }

    public function subupdate(Request $request,ProductCategory $parent,ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.edit');

        $cat = ProductCategory::where('parent_id',$parent->id)->where('name',$request->name)->where('id','<>',$category->id)->first();
        if($cat==null)
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
            'status' => ['required','integer']
        ],
        [
            "name.required" => "* عنوان دسته را وارد نمایید.",
            "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
            "name.unique_validator"=> "* این دسته قبلا ثبت شده است.",
        ]);

        $category->update([
            "name"=>$request->name,
            'slug' => SlugService::createSlug(ProductCategory::class, 'slug', $request->name),
            "status"=>$request->status,
        ]);

        toast('.بروزرسانی انجام شد','success')->position('bottom-end');
        return redirect(route('admin.shop.products.categories.sub.index',$parent));
    }

    public function subdestroy(ProductCategory $parent,ProductCategory $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('shop.products.categories.sub.delete');

        $category->delete();
        toast('دسته بندی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function fetch_child()
    {
        $cat_id = request('cat_id');
        $childs = ProductCategory::where('parent_id',$cat_id)->where('status',Status::Active)->get();
        return response()->json(['childs'=>$childs],200);
    }

}

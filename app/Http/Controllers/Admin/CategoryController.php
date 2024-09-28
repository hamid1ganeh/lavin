<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\ArticleCategories;
use Illuminate\Validation\Rule;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Enums\Status;
class CategoryController extends Controller
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
        $this->authorize('article.categorys.index');

        $categorys = ArticleCategories::orderby('name','ASC')
        ->filter()
        ->paginate(10)
        ->withQueryString();

        return view('admin.category.all',compact('categorys'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('article.categorys.create');

        return view('admin.category.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('article.categorys.create');

        $request->validate(
        [
            'name' => ['required','max:255','unique:article_categories'],
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],
        [
            "name.required" => "* عنوان دسته را وارد نمایید.",
            "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
            "name.unique"=> "* این دسته قبلا ثبت شده است.",
            'thumbnail.required' => '* تصویر شاخص الزامی است.',
            "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
            "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
        ]);


        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            $category =ArticleCategories::create(
                        [
                            "name"=>$request->name,
                            'slug' => SlugService::createSlug(ArticleCategories::class, 'slug', $request->name),
                            'status'=>$request->status
                        ]);

            $path = $this->imageService->path();
            $image = $this->imageService->upload($request->thumbnail,[
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
                'name' => $image,
                'path'=>$path
            ]);

        }

        alert()->success('دسته بندی جدید ثبت شد.', 'تبریک');

        return redirect(route('admin.article.categorys.index'));
    }

    public function show($id)
    {
        //
    }

    public function edit(ArticleCategories $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('article.categorys.edit');

        return view('admin.category.edit',compact('category'));
    }


    public function update(Request $request,ArticleCategories $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('article.categorys.edit');

        $id = $category->id;
        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('article_categories')->where(function($query) use ($id){
                        $query->where('id','<>',$id);
                    }),
                ]
            ],
            [
                "name.required" => "* عنوان دسته را وارد نمایید.",
                "name.max" => "* حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
                "name.unique"=> "* این دسته قبلا ثبت شده است.",
            ]);

        if($request->status == Status::Active || $request->status == Status::Deactive)
        {
            $category->update(
            [
                "name" =>  $request->name,
                'slug' => SlugService::createSlug(ArticleCategories::class, 'slug', $request->name),
                'status'=>$request->status
            ]);

            if($request->hasFile('thumbnail'))
            {
                $thumbnail = Image::where('imageable_type',get_class($category))->where('imageable_id',$category->id)->first();
                $thumbnail->destroyImage();

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
                        'w'=>267,
                        'h'=>273,
                    ],
                    'thumbnail' => [
                        'w'=>150,
                        'h'=>54,
                    ],
                ],$path);

                $category->thumbnail()->create([
                    'title' => $request->name,
                    'alt' => $request->name,
                    'name' => $thumbnail,
                    'path'=>$path
                ]);

            }
        }

        toastr()->info('عملایت بروزرسانی با موفقیت انجام شد');

        return redirect(route('admin.article.categorys.index'));
    }


    public function destroy(ArticleCategories $category)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('article.categorys.delete');

        $category->delete();
        toastr()->error('دسته  مورد نظر حذف  شد.');
        return redirect(route('admin.article.categorys.index'));
    }
}

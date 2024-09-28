<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Analyse;
use App\Models\AnalyseImage;
use Illuminate\Validation\Rule;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AnalyseController extends Controller
{
    private $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService();
    }
    public function index()
    {
//  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.index');

        $analysis = Analyse::withTrashed()->orderBy('title','asc')->get();
        return view('admin.analyse.index',compact('analysis'));
    }

    public function create()
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.store');

        return view('admin.analyse.create');
    }

    public function store(Request $request)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.store');

        $request->validate([
            'title'=>'required|max:255|unique:analyses',
            'min_price'=>'required|integer',
            'max_price'=>'required|integer',
            'description'=>'required|max:63000',
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],[
            'title.required' => '* الزامی',
            'title.unique' => '* قبلا ثبت شده است',
            'title.max' => '* حداکثر 255 کارکتر',
            'description.required' => '* الزامی',
            'min_price.required' => '* الزامی',
            'min_price.integer' => '* مقدار عددی وارد شود',
            'max_price.required' => '* الزامی',
            'max_price.integer' => '* مقدار عددی وارد شود',
            'description.max' => '* حداکثر 63000 کارکتر',
            'thumbnail.required' => '* تصویر شاخص الزامی است.',
            "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
            "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp"]);

        if($request->status == Status::Active || $request->status == Status::Deactive){
            $analyse =Analyse::create(['title'=> $request->title,
                                        'slug' => SlugService::createSlug(Analyse::class, 'slug', $request->title),
                                        'description'=> $request->description,
                                        'min_price'=> $request->min_price,
                                        'max_price'=> $request->max_price,
                                        'status'=> $request->status]);


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

            $analyse->thumbnail()->create([
                'title' => $request->title,
                'alt' => $request->title,
                'name' => $image,
                'path'=>$path
            ]);

        }


        return  redirect(route('admin.analysis.index'));
    }

    public function edit(Analyse $analise)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.edit');

        return view('admin.analyse.edit',compact('analise'));
    }

    public function update(Analyse $analise,Request $request)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.edit');

        $request->validate([
            'title'=>['required','max:255',Rule::unique('analyses')->ignore($analise->id)],
            'min_price'=>'required|integer',
            'max_price'=>'required|integer',
            'description'=>'required|max:63000',
        ],[
            'title.required' => '* الزامی',
            'title.unique' => '* قبلا ثبت شده است',
            'title.max' => '* حداکثر 255 کارکتر',
            'description.required' => '* الزامی',
            'min_price.required' => '* الزامی',
            'min_price.integer' => '* مقدار عددی وارد شود',
            'max_price.required' => '* الزامی',
            'max_price.integer' => '* مقدار عددی وارد شود',
            'description.max' => '* حداکثر 63000 کارکتر']);

        if($request->status == Status::Active || $request->status == Status::Deactive) {
            $analise->title = $request->title;
            $analise->slug = SlugService::createSlug(Analyse::class, 'slug', $request->title);
            $analise->min_price = $request->min_price;
            $analise->max_price = $request->max_price;
            $analise->description = $request->description;
            $analise->save();

            if($request->hasFile('thumbnail'))
            {

                $thumbnail = Image::where('imageable_type',get_class($analise))->where('imageable_id',$analise->id)->first();

                if(!is_null($thumbnail)){
                    $thumbnail->destroyImage();
                }


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

                $analise->thumbnail()->create([
                    'title' => $request->name,
                    'alt' => $request->name,
                    'name' => $thumbnail,
                    'path'=>$path
                ]);

            }
        }

        return  redirect(route('admin.analysis.index'));
    }

     public function detail(Analyse $analise)
     {
         $images = AnalyseImage::where('analyse_id',$analise->id)->get();
         return view('admin.analyse.detail',compact('analise','images'));
     }

    public function destroy(Analyse $analise)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.delete');

        $analise->delete();
        toast(' سرویس آنالیز مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.delete');

        $analise = Analyse::withTrashed()->find($id);
        $analise->restore();
        toast('سرویس آنالیز مورد نظر بازیابی  شد.','error')->position('bottom-end');
        return back();
    }

    public function images(Analyse $analise,Request $request)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.image.store');

        $request->validate([
            'title'=>'required|max:255|unique:analyse_images',
            'description'=>'required|max:63000',
            'thumbnail' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],[
            'title.required' => 'عنوان تصویر نمونه الزامی است',
            'title.unique' => 'عنوان تصویر نمونه قبلا ثبت شده است',
            'title.max' => 'عنوان تصویر نمونه حداکثر 255 کارکتر',
            'description.required' => 'توضیحات تصویر نمونه الزامی است',
            'description.max' => 'حداکثر توضیحات تصویر نمونه قبلا  63000 کارکتر',
            'thumbnail.required' => 'تصویر شاخص الزامی است.',
            "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
            "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp"]);


            $analyseImage =AnalyseImage::create(['title'=> $request->title,
                'analyse_id' => $analise->id,
                'description'=> $request->description,
                'required'=> (boolean)$request->required]);


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

        $analyseImage->thumbnail()->create([
                'title' => $request->title,
                'alt' => $request->title,
                'name' => $image,
                'path'=>$path
            ]);


        return back();
    }



    public function image_update(AnalyseImage $image,Request $request)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.image.update');

        $request->validate([
            'title'=>['required','max:255',Rule::unique('analyse_images')->ignore($image->id)],
            'description'=>'required|max:63000',
            'thumbnail' =>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],[
            'title.required' => 'عنوان تصویر نمونه الزامی است',
            'title.unique' => 'عنوان تصویر نمونه قبلا ثبت شده است',
            'title.max' => 'عنوان تصویر نمونه حداکثر 255 کارکتر',
            'description.required' => 'توضیحات تصویر نمونه الزامی است',
            'description.max' => 'حداکثر توضیحات تصویر نمونه قبلا  63000 کارکتر',
            'thumbnail.required' => 'تصویر شاخص الزامی است.',
            "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
            "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp"]);

        $image->title = $request->title;
        $image->description = $request->description;
        $image->required = (boolean)$request->required;
        $image->save();


        if($request->hasFile('thumbnail'))
        {
            $thumbnail = Image::where('imageable_type',get_class($image))->where('imageable_id',$image->id)->first();

            if ($thumbnail != null){
                $thumbnail->destroyImage();
            }

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

            $image->thumbnail()->create([
                'title' => $request->name,
                'alt' => $request->name,
                'name' => $thumbnail,
                'path'=>$path
            ]);

        }


        return back();
    }

    public function image_delete(AnalyseImage $image)
    {
        //  اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.image.delete');

        $image->delete();
        toast(' تصویر سرویس آنالیز مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }






}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Highlight;
use Illuminate\Validation\Rule;

class HighlighController extends Controller
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
        $this->authorize('highlights.index');

         $hilights = Highlight::orderBy('created_at','desc')->get();
         return view('admin.highlight.all',compact('hilights'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.create');

        return view('admin.highlight.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.create');

        $request->validate(
            [
                'title' => ['required','max:255','unique:highlights'],
                'thumbnail' =>'required|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                "title.required" => "* عنوان هایلایت را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان هایلایت 255 کارکتر است.",
                "title.unique"=> "* این عنوان قبلا ثبت شده است.",
                "thumbnail.required" => "* تصویر شاخص را انتخاب نمایید.",
                "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
                "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){

           $highlight = Highlight::create(['title'=>$request->title,
                                'status'=>$request->status]);


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

            $highlight->thumbnail()->create([
                'title' => $request->title,
                'alt' => $request->title,
                'name' => $thumbnail,
                'path'=>$path
            ]);

            toast('هایلایت جدید ثبت شد.','success')->position('bottom-end');
        }



        return redirect(route('admin.highlights.index'));

    }


    public function edit(Highlight $highlight)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.edit');

        return view('admin.highlight.edit',compact('highlight'));
    }


    public function update(Highlight $highlight,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.edit');

        $request->validate(
            [
                "title"=>['required','max:255',Rule::unique('highlights')->ignore($highlight->id)],
                'thumbnail' =>'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                "title.required" => "* عنوان هایلایت را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان هایلایت 255 کارکتر است.",
                "title.unique"=> "* این عنوان قبلا ثبت شده است.",
                "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
                "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $highlight->title =$request->title;
            $highlight->status =$request->status;
            $highlight->save();

            if($request->hasFile('thumbnail'))
            {
                $thumbnail = Image::where('imageable_type',get_class($highlight))->where('imageable_id',$highlight->id)->first();
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

                $highlight->thumbnail()->create([
                    'title' => $request->name,
                    'alt' => $request->name,
                    'name' => $thumbnail,
                    'path'=>$path
                ]);

            }
            toast('بروزرسانی انجام  شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.highlights.index'));
    }

    public function delete(Highlight $highlight)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.delete');

        $highlight->delete();
        toastr()->error("هایلایت ".$highlight->title." حذف شد. ");
        return back();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Image;
use App\Models\Story;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StoryController extends Controller
{
    private $imageService;


    public function __construct()
    {
        $this->imageService = new ImageService();
    }

    public function index()
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.index');

        $stories = Story::with('image')
                   ->orderBy('created_at','asc')
                   ->filter()
                   ->paginate(10)
                   ->withQueryString();


        $highlights = Highlight::orderBy('title','asc')->get();

        return view('admin.highlight.story.all',compact('stories','highlights'));
    }


    public function create()
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.create');

        $highlights = Highlight::orderBy('title','asc')->get();
        return view('admin.highlight.story.create',compact('highlights'));
    }


    public function store(Highlight $highlight,Request $request)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.create');

        $request->validate(
            [
                'title' => ['required','max:255'],
                'link' => ['nullable','url'],
                'highlight' => ['nullable','exists:highlights,id'],
                'image' =>'required|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                "title.required" => "* عنوان استوری را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان استوری 255 کارکتر است.",
                "link.url" => "*  لینک وارد شده مجاز نیست.",
                'image.required' => '* تصویر  الزامی است.',
                "image.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "image.image"=>"تنها تصویر قابل آپلود است.",
                "image.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
           $story = Story::create(['title'=>$request->title,
                                    'highlight_id'=> $request->highlight,
                                    'status'=>$request->status,
                                    'link'=>$request->link]);

            $path = $this->imageService->path();
            $image = $this->imageService->upload($request->image,[
                'original' => [
                    'w'=>getimagesize($request->image)[0],
                    'h'=>getimagesize($request->image)[1],
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

            $story->image()->create([
                'title' => $request->title,
                'alt' => $request->title,
                'name' => $image,
                'path'=>$path
            ]);

            toast('استوری جدید ثبت شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.stories.index'));
    }



    public function edit(Story $story)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.edit');

        $highlights = Highlight::orderBy('title','asc')->get();

        return view('admin.highlight.story.edit',compact('highlights','story'));
    }

    public function update(Story $story,Request $request)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.edit');

        $request->validate(
            [
                'title' => ['required','max:255'],
                'link' => ['nullable','url'],
                'highlight' => ['nullable','exists:highlights,id'],
                'image' =>'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                "title.required" => "* عنوان استوری را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان استوری 255 کارکتر است.",
                "link.url" => "*  لینک وارد شده مجاز نیست.",
                "image.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "image.image"=>"تنها تصویر قابل آپلود است.",
                "image.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $story->update(['title'=>$request->title,
                            'status'=>$request->status,
                            'highlight_id'=> $request->highlight,
                            'link'=>$request->link]);

            if($request->hasFile('image'))
            {
                $image = Image::where('imageable_type',get_class($story))->where('imageable_id',$story->id)->first();
                $image->destroyImage();
                $path = $this->imageService->path();
                $image = $this->imageService->upload($request->image,[
                    'original' => [
                        'w'=>getimagesize($request->image)[0],
                        'h'=>getimagesize($request->image)[1],
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

                $story->image()->create([
                    'title' => $request->title,
                    'alt' => $request->title,
                    'name' => $image,
                    'path'=>$path
                ]);
            }

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }

        return redirect(route('admin.stories.index'));
    }

    public function delete(Highlight $highlight,Story $story)
    {
        //اجازه دسترس
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('highlights.story.delete');

        DB::transaction(function() use ($story) {
            $story->image->destroyImage();
            $story->delete();
        });
        toast('استوری مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Festival;
use App\Models\Image;
use App\Services\FunctionService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;


class FestivalController extends Controller
{
    private $imageService;
    private $fuctionService;

    public function __construct()
    {
        $this->imageService = new ImageService();
        $this->fuctionService = new FunctionService();
    }

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.index');

       $festivals = Festival::orderBy('created_at','asc')->paginate(10);
       return view('admin.festival.all',compact('festivals'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.create');

        return view('admin.festival.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.create');

        $request->validate(
            [
                'title' => ['required','max:255','unique:festivals'],
                'thumbnail' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'description' => ['required'],
            ],
            [
                "title.required" => "* عنوان جشنوراه را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان جشنوراه 255 کارکتر است.",
                "title.unique"=> "* این جشنوراه قبلا ثبت شده است.",
                'thumbnail.required' => '* تصویر شاخص الزامی است.',
                "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
                "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
                'description.required' => '* توضیحات الزامی است.',
            ]);

        if (in_array($request->status,[Status::Active,Status::Deactive])){

            if (isset($request->end)){
                $end =  $this->fuctionService->faToEn($request->end);
                $end = Jalalian::fromFormat('Y/m/d H:i:s', $end)->toCarbon("Y-m-d H:i:s");
            }
            else{
                $end =null;
            }

           $festival = Festival::create([
                'title'=> $request->title,
                'description'=> $request->description,
                'end'=>$end,
                'status'=>$request->status]);


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

            $festival->thumbnail()->create([
                'title' => $request->title,
                'alt' => $request->title,
                'name' => $image,
                'path'=>$path
            ]);

            toast('جشنواره جدید ثبت شد. ','success')->position('bottom-end');
        }
        return redirect(route('admin.festivals.index'));
    }


    public function show($id)
    {
        //
    }

    public function edit(Festival $festival)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.edit');

        return view('admin.festival.edit',compact('festival'));
    }


    public function update(Festival $festival,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.edit');

        $request->validate(
            [
                'title' => ['required','max:255','unique:festivals,title,'.$festival->id,],
                'thumbnail' =>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'description' => ['required'],
            ],
            [
                "title.required" => "* عنوان جشنوراه را وارد نمایید.",
                "title.max" => "* حداکثر طول مجاز برای عنوان جشنوراه 255 کارکتر است.",
                "title.unique"=> "* این جشنوراه قبلا ثبت شده است.",
                "thumbnail.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "thumbnail.image"=>"تنها تصویر قابل آپلود است.",
                "thumbnail.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
                'description.required' => '* توضیحات الزامی است.',
            ]);

        if (in_array($request->status,[Status::Active,Status::Deactive])){

            if (isset($request->end)){
                $end =  $this->fuctionService->faToEn($request->end);
                $end = Jalalian::fromFormat('Y/m/d H:i:s', $end)->toCarbon("Y-m-d H:i:s");
            }
            else{
                $end =null;
            }


            $festival->title = $request->title;
            $festival->description = $request->description;
            $festival->end =  $end;
            $festival->status = $request->status;
            $festival->save();

            if ($request->hasFile('thumbnail')){

                $thumbnail = Image::where('imageable_type',get_class($festival))->where('imageable_id',$festival->id)->first();
                $thumbnail->destroyImage();

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

                $festival->thumbnail()->create([
                    'title' => $request->title,
                    'alt' => $request->title,
                    'name' => $image,
                    'path'=>$path
                ]);
            }

            toast('جشنواره بروزرسانی شد. ','success')->position('bottom-end');
        }
        return redirect(route('admin.festivals.index'));
    }

    public function display(Festival $festival)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.display');

        Festival::query()->update([
            'display' => false,
        ]);

        $festival->display = true;
        $festival->save();

        return back();
    }

    public function destroy(Festival $festival)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('festivals.delete');

        $festival->delete();
        toastr()->error('جشنواره مورد نظر حذف  شد.');
        return redirect(route('admin.festivals.index'));

    }
}

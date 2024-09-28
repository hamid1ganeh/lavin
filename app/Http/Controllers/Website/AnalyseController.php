<?php

namespace App\Http\Controllers\Website;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Services\ImageService;
use App\Services\SMS;
use Illuminate\Http\Request;
use App\Models\Analyse;
use App\Models\AnalyseReserve;
use App\Models\AnalyseImage;
use App\Enums\AnaliseStatus;
use Auth;

class AnalyseController extends Controller
{
    private $imageService;
    public function __construct()
    {
        $this->imageService = new ImageService();
    }
    public function index()
    {
        $analises = Analyse::where('status',Status::Active)->orderBy('created_at','desc')->paginate(20);
        return  view('website.analyse.index',compact('analises'));
    }

    public function show($slug)
    {
        $analyse = Analyse::with('images')->where('status',Status::Active)->where('slug',$slug)->first();
        if(is_null($analyse)){
            return abort(404);
        }
        return  view('website.analyse.show',compact('analyse'));
    }

    public function store(Analyse $analyse,Request $request)
    {

          $images = $request->except('_token','price');


          if (is_null($images)){
              alert()->error('خطا','ارسال تصاویر الزامی است');
              return back();
          }

        if (!isset($request->price)){
            alert()->error('خطا','ارسال مبلغ الزامی است.');
            return back();
        }

          if (!is_null(AnalyseReserve::where('user_id',Auth::id())
              ->where('analyse_id',$analyse->id)
              ->where('status',AnaliseStatus::pending)
              ->first())){
              alert()->error('خطا','این سرویس آنالیز قبلا توسط شما ثبت شده و هم اکنون در حال بررسی می باشد.');
              return back();
          }

          $reserve = new AnalyseReserve();
          $reserve->user_id = Auth::id();
          $reserve->analyse_id = $analyse->id;
          $reserve->price = $request->price;
          $reserve->save();

        $path = $this->imageService->path();
        foreach($images as $index=>$img){
            $analyseImage = AnalyseImage::find($index);
            if (!is_null($analyseImage)){
                $image = $this->imageService->upload($img,[
                    'original' => [
                        'w'=>getimagesize($img)[0],
                        'h'=>getimagesize($img)[1],
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


                Image::create([
                    'imageable_id'=> $reserve->id,
                    'imageable_type'=> get_class($reserve),
                    'title'=>$analyseImage->title,
                    'alt'=>$analyseImage->title,
                    'name'=>$image,
                    'path'=>$path,
                ]);

            }
        }

        //ارسال sms
        $msg = Auth::user()->getFullName()."\n عزیز"
            ."درخواست آنالیز شما ثبت شد.\nپس از بررسی تصاویر نتیجه آنالیز برای شما ارسال خواهد شد.\nکلینیک لاوین رشت";
        $sms = new SMS;
        $sms->send(array(Auth::user()->mobile),$msg);

        alert()->success('تبریک','درخواست آنالیز شما ثبت شد.پس از بررسی تصاویر نتیجه آنالیز برای شما ارسال خواهد شد.');
        return back();

    }
}

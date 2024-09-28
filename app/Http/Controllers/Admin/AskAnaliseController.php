<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnaliseStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\AnalyseReserve;
use App\Models\Analyse;
use Auth;

class AskAnaliseController extends Controller
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
         $this->authorize('analysis.ask.index');

         $roles = Auth::guard('admin')->user()->getRoles();
         if(in_array('doctor',$roles)){
             $asks = AnalyseReserve::where('doctor_id',Auth::guard('admin')->id())
                 ->orderby('created_at','asc')
                 ->filter()
                 ->paginate(30)
                 ->withQueryString();
         }else{
             $asks = AnalyseReserve::orderby('created_at','asc')
                 ->filter()
                 ->paginate(30)
                 ->withQueryString();
         }

         $doctors = Admin::whereHas('roles', function($q){$q->where('name', 'doctor');})
             ->orderBy('fullname','asc')
             ->get();

         $analiseServices = Analyse::orderBy('title','desc')->get();

         return  view('admin.analyse.ask',compact('asks','doctors','analiseServices'));
     }

     public function show(AnalyseReserve $ask)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('analysis.ask.show');

         return  view('admin.analyse.show',compact('ask'));
     }

    public function response(AnalyseReserve $ask,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.ask.response');

        $request->validate([
            'response'=>'required|max:63000',
            'image' =>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ],[
            'response.required' => '* پاسخ پزشک وارد شود',
            'response.max' => '* حداکثر 63000 کارکتر',
            "image.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
            "image.image"=>"تنها تصویر قابل آپلود است.",
            "image.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp"]);

        $ask->response = $request->response;
        $ask->status = AnaliseStatus::response;

        if(isset($request->remove)){
            $ask->image_id = null;
        }

        if($request->hasFile('image'))
        {
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
                    'w'=>267,
                    'h'=>273,
                ],
                'thumbnail' => [
                    'w'=>150,
                    'h'=>54,
                ],
            ],$path);

           $image = Image::create(['title' => $ask->title,
                                    'alt' => $ask->title,
                                    'name' => $image,
                                    'path'=>$path]);

           $ask->image_id = $image->id;

        }

        $ask->save();
        toast('پاسخ پزشک ثبت شد.','success')->position('bottom-end');
       return back();
    }


    public function voice(AnalyseReserve $ask,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.ask.response');

        $file_name = $_FILES['new_voice_file']['name'];
        $tmp_path  = $_FILES['new_voice_file']['tmp_name'];
        $file_size   = $_FILES['new_voice_file']['size'];

        if( $file_size > (1024 * 1024 * 3)){
            return response()->json(['success' => false, 'error' => 'The file "'. $file_name .'" is too big. Its size cannot exceed 3 MB.'],422);
        }

        $upload_path = '/voices/voice_recording_'.date('Ymdhis').'.mp3';

        if (move_uploaded_file($tmp_path, public_path($upload_path))) {
            $url = env('APP_URL').$upload_path;
            $ask->voice = $url;
            $ask->save();
            return response()->json(['url'=>$url],200);
        }
        else {
            return response()->json(['url'=>$file_name],500);
        }
    }

    public function voice_remove(AnalyseReserve $ask)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.ask.response');

        $ask->voice = null;
        $ask->save();
        toast('صدای ضبط شده پزشک حذف شد.','success')->position('bottom-end');
        return back();
    }


    public  function doctor(AnalyseReserve $ask,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('analysis.ask.doctor');

        $request->validate(['doctor'=>'nullable|numeric|exists:admins,id']);

        $ask->doctor_id = $request->doctor;

        if(isset($request->doctor)){
            $ask->status = AnaliseStatus::doctor;
        }else
        {
            $ask->status = AnaliseStatus::pending;
        }

        $ask->save();

        toast('سرویس آنالیز به پزشک مورد نظر ارجاع داده شد.','success')->position('bottom-end');
        return back();

    }

}

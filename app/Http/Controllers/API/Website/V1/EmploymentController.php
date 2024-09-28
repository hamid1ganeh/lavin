<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmploymentJob;
use App\Http\Resources\Website\Collections\EmploymentJobCollection;
use Illuminate\Http\Response;
use  Validator;
use App\Services\FunctionService;
use App\Models\Employment;

class EmploymentController extends Controller
{
     public function form()
     {
        $jobs = EmploymentJob::with('main_category','sub_category')->where('status',Status::Active)->orderBy('title','asc')->get();
        return response()->json(['jobs'=> new EmploymentJobCollection($jobs)],200);
     }

     public  function register(Request $request)
     {
         $validator = Validator::make(request()->all(),
             [
                 'fullname' => ['required','max:255'],
                 'mobile'=>['required','max:11','min:11'],
                 "job"=>['required','exists:employment_jobs,id'],
                 "about"=>['nullable','max:255'],
                 'resume' =>'required|max:2048',

             ] ,[
                 'fullname.required' => ' نام و نام خانوادگی الزامی است.',
                 'fullname.max' => 'حداکثر  طول نام و نام خانوادگی  255 کارکتر می باشد.',
                 "mobile.required"=>"شماره موبایل الزامی است",
                 "mobile.max"=>"0911xxxxxxx فرمت صحیح موبایل",
                 "mobile.min"=>"0911xxxxxxx فرمت صحیح موبایل",
                 "job.required"=>"انتخاب شغل الزامی است.",
                 "about.max"=>"حداکثر طول درباره 255 کارکتر است.",
                 "resume.required"=>"ارسال رزومه الزامی است.",
                 "resume.max"=>"حداکثر حجم مجاز برای رزومه ارسالی 2 مگابایت است.",

             ]
         );

         if ($validator->fails()) {
             return response()->json([
                 'errors' => $validator->errors(),
                 'status' => Response::HTTP_BAD_REQUEST,
             ], Response::HTTP_BAD_REQUEST);
         }
         $resume = null;
         if($request->hasFile('resume')) {
             $function = new FunctionService();
             $resume = $function->uploadFile($request->resume);
         }

         Employment::create([
             'fullname'=> $request->fullname,
             'mobile'=> $request->mobile,
             'job_id'=> $request->job,
             'about'=> $request->about,
             'resume'=> $resume,
         ]);

         return response()->json(['message'=>'درخواست شما با موفقیت ثبت شد.'],201);
     }
}

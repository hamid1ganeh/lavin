<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAddress;
use App\Models\AdminBank;
use App\Models\AdminMajor;
use App\Models\AdminMedia;
use App\Models\AdminRetraining;
use App\Models\Image;
use App\Models\Province;
use App\Services\FunctionService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StaffDocument;
use App\Enums\StaffDocumentStatus;
use Morilog\Jalali\Jalalian;

class StaffController extends Controller
{

    private $imageService;
    private $fuctionService;

    public function __construct()
    {
        $this->fuctionService = new FunctionService();
        $this->imageService = new ImageService();
    }
     public function documents()
     {
         $admin = Auth::guard('admin')->user();
         $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
             'admin_id'=>$admin->id
         ]);

         return  view('admin.staff.index',compact('document'));
     }

     public function personal()
     {
         $admin = Auth::guard('admin')->user();
         $address = AdminAddress::where('admin_id',$admin->id)->firstOrCreate([
             'admin_id'=>$admin->id
         ]);

         $provinces = Province::orderBy('name','asc')->get();

         return  view('admin.staff.personal',compact('address','admin','provinces'));
     }

     public  function personal_update(Request $request)
     {
         $request->validate(
             [
                 "fullname"=>['required','max:255'],
                 "mobile"=>['required','max:11','min:11'],
                 "nationalcode"=>['required','max:10','min:10'],
                 "email"=>['required','email','max:255'],
                 'province' => ['required','integer','exists:provinces,id'],
                 'city' => ['required','integer','exists:cities,id'],
                 'latitude' => ['nullable','max:255'],
                 'longitude' => ['nullable','max:255'],
                 'postalCode' => ['nullable','min:10','max:10'],
                 'address' => ['nullable','max:255'],
             ],
             [
                 "fullname.required"=>"* الزامی است.",
                 "fullname.max"=>"* حداکثر 255 کارکتر",
                 "mobile.required"=>"* الزامی است.",
                 "mobile.max"=>"0911xxxxxxx",
                 "mobile.min"=>"0911xxxxxxx",
                 "email.required"=>"* الزامی است.",
                 "email.email"=>"ایمیل وارد شده معتبر نیست.",
                 "email.max"=>"* حداکثر 255 کارکتر ",
                 "nationalcode.required"=>"* الزامی است.",
                 "nationalcode.min"=>"* کدملی 10 رقمی است.",
                 "nationalcode.max"=>"* کدملی 10 رقمی است.",
                 "province.required" => "* الزامی است.",
                 "city.required" => "* الزامی است.",
                 "latitude.max" => "* حداکثر 255 کارکتر.",
                 "longitude.max" => "* حداکثر 255 کارکتر.",
                 "postalCode.min" => "* کدپستی 10 رقمی است.",
                 "postalCode.max" => "* کدپستی 10 رقمی است.",
                 "address.required" => "* الزامی است.",
                 "address.max" => "* حداکثر 255 کارکتر.",
             ]);

         $admin = Auth::guard('admin')->user();
         $admin->fullname = $request->fullname;
         $admin->mobile = $request->mobile;
         $admin->nationalcode = $request->nationalcode;
         $admin->email = $request->email;

         $address = AdminAddress::where('admin_id',$admin->id)->firstOrCreate([
             'admin_id'=>$admin->id
         ]);

         $address->provance_id = $request->province;
         $address->city_id = $request->city;
         $address->latitude = $request->latitude;
         $address->longitude = $request->longitude;
         $address->postalCode = $request->postalCode;
         $address->address = $request->address;

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

         $document->personal_status = StaffDocumentStatus::pending;

         DB::transaction(function() use ($admin, $address,$document) {
             $admin->save();
             $address->save();
             $document->save();
         });

         toast('بروزرسانی انجام شد.','success')->position('bottom-end');
         return redirect(route('admin.staff.documents.index'));

     }


    public function educations()
    {
        $admin = Auth::guard('admin')->user();
        $majors =  AdminMajor::where('admin_id',$admin->id)->get();
        $provinces = Province::orderBy('name','asc')->get();

        return  view('admin.staff.educations',compact('majors','admin','provinces'));
    }


    public  function education_store(Request $request)
    {
        $request->validate(
            [
                "major"=>['required','max:255'],
                "orientation"=>['required','max:255'],
                "level"=>['required','max:255'],
                "center_name"=>['required','max:255'],
                'start' => ['required'],
                'end' => ['required'],
                "grade"=>['required','between:0,19.99'],
                'province_id' => ['required','integer','exists:provinces,id'],
                'city_id' => ['required','integer','exists:cities,id'],

            ],
            [
                "major.required"=>"رشته تحصیلی الزمی است.",
                "major.max"=>"* حداکثر طول رشته تحصیلی  255 کارکتر",
                "orientation.required"=>"گرایش تحصیلی الزمی است.",
                "orientation.max"=>"* حداکثر طول گرایش تحصیلی  255 کارکتر",
                "level.required"=>"مدرک تحصیلی الزمی است.",
                "level.max"=>"* حداکثر طول مدرک تحصیلی  255 کارکتر",
                "center_name.required"=>"نام واحد آموزشی الزمی است.",
                "center_name.max"=>"* حداکثر طول نام واحد آموزشی  255 کارکتر",
                "province_id.required" => "انتخاب استان محل تحصیل الزامی است.",
                "city_id.required" => "انتخاب استان محل تحصیل الزامی است.",
                "start.required" => "تاریخ شروع تحصیل الزامی است.",
                "end.required" => "تاریخ پایان تحصیل الزامی است.",
                "grade.required"=>"معدل الزمی است.",

            ]);

        $start =  $this->fuctionService->faToEn($request->start);
        $start = Jalalian::fromFormat('Y/m/d', $start)->toCarbon("Y-m-d");

        $end =  $this->fuctionService->faToEn($request->end);
        $end = Jalalian::fromFormat('Y/m/d', $end)->toCarbon("Y-m-d");

        $admin = Auth::guard('admin')->user();
        $major = new AdminMajor();
        $major->admin_id =   $admin->id;
        $major->orientation =  $request->orientation;
        $major->level =  $request->level;
        $major->center_name =  $request->center_name;
        $major->major =  $request->major;
        $major->grade =  $request->grade;
        $major->city_id =  $request->city_id;
        $major->province_id =  $request->province_id;
        $major->start =  $start;
        $major->end =  $end;


        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->education_status = StaffDocumentStatus::pending;


        DB::transaction(function() use ($major,$document) {
            $major->save();
            $document->save();
        });


        toast('رشته تحصیلی جدید افزوده شد.','success')->position('bottom-end');
        return back();
    }


    public  function education_update(AdminMajor $major,Request $request)
    {
        $request->validate(
            [
                "major"=>['required','max:255'],
                "orientation"=>['required','max:255'],
                "level"=>['required','max:255'],
                "center_name"=>['required','max:255'],
                'start' => ['nullable'],
                'end' => ['nullable'],
                "grade"=>['required','between:0,19.99'],
                'province_id' => ['required','integer','exists:provinces,id'],
                'city_id' => ['required','integer','exists:cities,id'],

            ],
            [
                "major.required"=>"رشته تحصیلی الزمی است.",
                "major.max"=>"* حداکثر طول رشته تحصیلی  255 کارکتر",
                "orientation.required"=>"گرایش تحصیلی الزمی است.",
                "orientation.max"=>"* حداکثر طول گرایش تحصیلی  255 کارکتر",
                "level.required"=>"مدرک تحصیلی الزمی است.",
                "level.max"=>"* حداکثر طول مدرک تحصیلی  255 کارکتر",
                "center_name.required"=>"نام واحد آموزشی الزمی است.",
                "center_name.max"=>"* حداکثر طول نام واحد آموزشی  255 کارکتر",
                "province_id.required" => "انتخاب استان محل تحصیل الزامی است.",
                "city_id.required" => "انتخاب استان محل تحصیل الزامی است.",
                "start.required" => "تاریخ شروع تحصیل الزامی است.",
                "end.required" => "تاریخ پایان تحصیل الزامی است.",
                "grade.required"=>"معدل الزمی است.",

            ]);

        $start =  $this->fuctionService->faToEn($request->start);
        $start = Jalalian::fromFormat('Y/m/d', $start)->toCarbon("Y-m-d");
        $end =  $this->fuctionService->faToEn($request->end);
        $end = Jalalian::fromFormat('Y/m/d', $end)->toCarbon("Y-m-d");

        $major->orientation =  $request->orientation;
        $major->level =  $request->level;
        $major->center_name =  $request->center_name;
        $major->major =  $request->major;
        $major->grade =  $request->grade;
        $major->city_id =  $request->city_id;
        $major->province_id =  $request->province_id;
        $major->start =  $start;
        $major->end =  $end;

        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->education_status = StaffDocumentStatus::pending;


        DB::transaction(function() use ($major,$document) {
            $major->save();
            $document->save();
        });


        toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        return back();
    }

    public  function education_delete(AdminMajor $major)
    {
        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->education_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($major,$document) {
            $major->delete();
            $document->save();
        });


        toast('رشته تحصیلی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function socialmedias()
    {
        $admin = Auth::guard('admin')->user();
        $medias =  AdminMedia::where('admin_id',$admin->id)->get();
        return  view('admin.staff.socialmedias',compact('medias'));
    }

    public  function media_store(Request $request)
    {
        $request->validate(
            [
                "name"=>['required','max:255'],
                "link"=>['required','max:255'],
            ],
            [
                "name.required"=>" نام شبکه اجتماعی الزمی است.",
                "name.max"=>" حداکثر طول نام شبکه اجتماعی  255 کارکتر",
                "link.required"=>"لینک یا شناسه شبکه اجتماعی الزمی است.",
                "link.max"=>" حداکثر طول لینک یا شناسه شبکه اجتماعی  255 کارکتر",
            ]);

        $admin = Auth::guard('admin')->user();
        $media = new AdminMedia();
        $media->admin_id =   $admin->id;
        $media->name =  $request->name;
        $media->link =  $request->link;


        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->socialmedia_status = StaffDocumentStatus::pending;


        DB::transaction(function() use ($media,$document) {
            $media->save();
            $document->save();
        });

        toast('شبکه اجتماعی جدید افزوده شد.','success')->position('bottom-end');
        return back();
    }

    public  function media_update(AdminMedia $media,Request $request)
    {
        $request->validate(
            [
                "name"=>['required','max:255'],
                "link"=>['required','max:255'],
            ],
            [
                "name.required"=>" نام شبکه اجتماعی الزمی است.",
                "name.max"=>" حداکثر طول نام شبکه اجتماعی  255 کارکتر",
                "link.required"=>"لینک یا شناسه شبکه اجتماعی الزمی است.",
                "link.max"=>" حداکثر طول لینک یا شناسه شبکه اجتماعی  255 کارکتر",
            ]);

        $admin = Auth::guard('admin')->user();
        $media->name =  $request->name;
        $media->link =  $request->link;

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->socialmedia_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($media,$document) {
            $media->save();
            $document->save();
        });

        toast('شبکه اجتماعی مورد نظر ویرایش شد.','success')->position('bottom-end');
        return back();
    }


    public  function media_delete(AdminMedia $media)
    {
        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->socialmedia_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($media,$document) {
            $media->delete();
            $document->save();
        });

        toast('شبکه اجتماعی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function banks()
    {
        $admin = Auth::guard('admin')->user();
        $banks =  AdminBank::where('admin_id',$admin->id)->get();
        return  view('admin.staff.banks',compact('banks'));
    }

    public  function bank_store(Request $request)
    {
        $request->validate(
            [
                "name"=>['required','max:255'],
                "number"=>['required','max:16','min:16'],
                "shaba"=>['required'],
            ],
            [
                "name.required"=>" نام بانک الزمی است.",
                "name.max"=>" حداکثر طول نام بانک  255 کارکتر",
                "number.required"=>"شماره کارت الزمی است.",
                "number.max"=>"شماره کارت 16 رقمی را وار نمایید",
                "number.min"=>"شماره کارت 16 رقمی را وار نمایید.",
                "shaba.required"=>"شماره شبا الزمی است.",
            ]);

        $admin = Auth::guard('admin')->user();
        $bank = new AdminBank();
        $bank->admin_id =   $admin->id;
        $bank->name =  $request->name;
        $bank->number =  $request->number;
        $bank->shaba =  $request->shaba;

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->bank_status = StaffDocumentStatus::pending;


        DB::transaction(function() use ($bank,$document) {
            $bank->save();
            $document->save();
        });

        toast('مشخصات بانکی جدید افزوده شد.','success')->position('bottom-end');
        return back();
    }

    public  function bank_update(AdminBank $bank, Request $request)
    {
        $request->validate(
            [
                "name"=>['required','max:255'],
                "number"=>['required','max:16','min:16'],
                "shaba"=>['required'],
            ],
            [
                "name.required"=>" نام بانک الزمی است.",
                "name.max"=>" حداکثر طول نام بانک  255 کارکتر",
                "number.required"=>"شماره کارت الزمی است.",
                "number.max"=>"شماره کارت 16 رقمی را وار نمایید",
                "number.min"=>"شماره کارت 16 رقمی را وار نمایید.",
                "shaba.required"=>"شماره شبا الزمی است.",
            ]);

        $admin = Auth::guard('admin')->user();
        $bank->name =  $request->name;
        $bank->number =  $request->number;
        $bank->shaba =  $request->shaba;


        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->bank_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($bank,$document) {
            $bank->save();
            $document->save();
        });

        toast('مشخصات بانکی  ویرایش شد.','success')->position('bottom-end');
        return back();
    }


    public  function bank_delete(AdminBank $bank)
    {
        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->bank_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($bank,$document) {
            $bank->delete();
            $document->save();
        });

        toast('مشخصات بانکی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }


    public function retrainings()
    {
        $admin = Auth::guard('admin')->user();
        $retrainings =  AdminRetraining::where('admin_id',$admin->id)->get();
        return  view('admin.staff.retrainings',compact('retrainings'));
    }


    public  function retraining_store(Request $request)
    {
        $request->validate(
            [
                "name"=>['required','max:255'],
                "duration"=>['required','max:255'],
                "reference"=>['required','max:255'],
                "institution"=>['required','max:255'],
                "date"=>['required'],
                'image' =>'required|image|mimes:jpeg,png,jpg,webp|max:2048',

            ],
            [
                "name.required"=>" نام دوره آموزشی الزمی است.",
                "name.max"=>" حداکثر طول دوره آموزشی  255 کارکتر",
                "duration.required"=>" مدت زمان دوره آموزشی الزمی است.",
                "duration.max"=>" حداکثر طول مدت زمان دوره آموزشی  255 کارکتر",
                "institution.required"=>" نام واحد آموزشی الزمی است.",
                "institution.max"=>" حداکثر طول نام واحد آموزشی  255 کارکتر",
                "reference.required"=>" نام مرجع صدور مدرک الزمی است.",
                "reference.max"=>" حداکثر طول نام مرجع صدور مدرک  255 کارکتر",
                "image.required"=>" آپلود تصویر مدرک الزمی است.",
                "image.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "image.image"=>"تنها تصویر قابل آپلود است.",
                "image.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
                "date.required"=>" تاریخ صدور مدرک  الزمی است.",
            ]);

        $date =  $this->fuctionService->faToEn($request->date);
        $date = Jalalian::fromFormat('Y/m/d', $date)->toCarbon("Y-m-d");

        $admin = Auth::guard('admin')->user();
        $retrainin = new AdminRetraining();
        $retrainin->admin_id =   $admin->id;
        $retrainin->name =  $request->name;
        $retrainin->duration =  $request->duration;
        $retrainin->reference =  $request->reference;
        $retrainin->institution =  $request->institution;
        $retrainin->date =  $date;

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->retraining_status = StaffDocumentStatus::pending;


        $path = $this->imageService->path();
        $thumbnail = $this->imageService->upload($request->image,[
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


        DB::transaction(function() use ($retrainin,$document,$request,$thumbnail,$path) {
            $retrainin->save();
            $document->save();
            $retrainin->image()->create([
                'title' => $request->name,
                'alt' => $request->name,
                'name' => $thumbnail,
                'path'=>$path
            ]);
        });


        toast('بازآموزی جدید افزوده شد.','success')->position('bottom-end');
        return back();
    }


    public  function retraining_update($id,Request $request)
    {
        $retrainin = AdminRetraining::find($id);

        $request->validate(
            [
                "name"=>['required','max:255'],
                "duration"=>['required','max:255'],
                "reference"=>['required','max:255'],
                "institution"=>['required','max:255'],
                "date"=>['required'],
                'image' =>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            ],
            [
                "name.required"=>" نام دوره آموزشی الزمی است.",
                "name.max"=>" حداکثر طول دوره آموزشی  255 کارکتر",
                "duration.required"=>" مدت زمان دوره آموزشی الزمی است.",
                "duration.max"=>" حداکثر طول مدت زمان دوره آموزشی  255 کارکتر",
                "institution.required"=>" نام واحد آموزشی الزمی است.",
                "institution.max"=>" حداکثر طول نام واحد آموزشی  255 کارکتر",
                "reference.required"=>" نام مرجع صدور مدرک الزمی است.",
                "reference.max"=>" حداکثر طول نام مرجع صدور مدرک  255 کارکتر",
                "image.max"=>"حداکثر حجم مجاز برای تصویر شما 2 مگابایت است.",
                "image.image"=>"تنها تصویر قابل آپلود است.",
                "image.mimes"=>"فرمت های مجاز jpeg,png,jpg,webp",
                "date.required"=>" تاریخ صدور مدرک  الزمی است.",
            ]);

        $date =  $this->fuctionService->faToEn($request->date);
        $date = Jalalian::fromFormat('Y/m/d', $date)->toCarbon("Y-m-d");


        $retrainin->name =  $request->name;
        $retrainin->duration =  $request->duration;
        $retrainin->reference =  $request->reference;
        $retrainin->institution =  $request->institution;
        $retrainin->date =  $date;


        if($request->hasFile('image')){
            $image = Image::where('imageable_type',get_class($retrainin))->where('imageable_id',$retrainin->id)->first();
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
                    'w'=>267,
                    'h'=>273,
                ],
                'thumbnail' => [
                    'w'=>150,
                    'h'=>54,
                ],
            ],$path);

            $retrainin->image()->create([
                'title' => $request->name,
                'alt' => $request->name,
                'name' => $image,
                'path'=>$path
            ]);
        }

        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->retraining_status = StaffDocumentStatus::pending;


        DB::transaction(function() use ($retrainin,$document) {
            $retrainin->save();
            $document->save();
        });

        toast('بازآموزی مورد نظر ویرایش شد.','success')->position('bottom-end');
        return back();
    }


    public  function retraining_delete($id)
    {
        $retrainin = AdminRetraining::find($id);
        $admin = Auth::guard('admin')->user();
        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->retraining_status = StaffDocumentStatus::pending;

        DB::transaction(function() use ($retrainin,$document) {
            $retrainin->delete();
            $retrainin->image->destroyImage();
            $document->save();
        });

        toast('بازآموزی مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }


}

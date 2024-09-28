<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EmploymentStatus;
use App\Services\FunctionService;
use App\Http\Controllers\Controller;
use App\Models\Employment;
use App\Models\Role;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class EmploymentController extends Controller
{

     public function index()
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('employments.index');

         $employments = Employment::orderBy('created_at','asc')->get();
         $roles = Role::orderBy('name','asc')->get();
         return view('admin.employments.all',compact('employments','roles'));
     }

     public function response(Employment $employment,Request $request)
     {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('employments.response');

         $request->validate(
         [
             'result' => ['required','max:255'],
         ],
         [
             'result.required' => 'درج پاسخ الزامی است.',
             'result.max' => 'حداکثر  طول مجاز متن پاسخ 255 کارکتر می باشد.',
         ]);

         if(in_array($request->status,EmploymentStatus::getStatusList)){

             $functionService = new FunctionService();

             $startEducation = null;
             if(isset($request->startEducation)){
                 $startEducation =  $functionService->faToEn($request->startEducation);
                 $startEducation = Jalalian::fromFormat('Y/m/d', $startEducation)->toCarbon("Y-m-d");
             }

             $endEducation = null;
             if(isset($request->endEducation)){
                 $endEducation =  $functionService->faToEn($request->endEducation);
                 $endEducation = Jalalian::fromFormat('Y/m/d', $endEducation)->toCarbon("Y-m-d");
             }

             $employment->result = $request->result;
             $employment->status = $request->status;
             $employment->startEducation = $startEducation;
             $employment->endEducation = $endEducation;
             $employment->save();

             toast('پاسخ شما ثبت شد','success')->position('bottom-end');
         }

         return back();

     }

    public function refer(Employment $employment,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('employments.refer');

        $request->validate(
            [
                'role' => ['nullable','exists:roles,id'],
            ]);

        $employment->role_id = $request->role;
        $employment->status = EmploymentStatus::refer;
        $employment->save();
        toast('درخواست استخدام ارجاع داده شد.','success')->position('bottom-end');

        return back();

    }


}

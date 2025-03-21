<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Auth;
use Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('roles.index');

         $roles = Role::with('permissions')->filter()->orderBy('name','desc')->get();
         $permissions = Permission::orderBy('id','asc')->get();
         return view('admin.roles.all',compact('roles','permissions'));
    }

    public function create()
    {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('roles.create');

        $permissions = Permission::where('status',Status::Active)->orderBy('name','asc')->get();
        return view('admin.roles.create',compact('permissions'));
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('roles.create');

        if(!(isset($_POST['permissions']) && is_array($_POST['permissions'])))
        {
            alert()->warning('تعیین سطوح دسترسی الزامی است.');
           return back();
        }


        $request->validate(
        [
            "name"=>['required','max:255','unique:roles'],
            "description"=>['max:255']
        ],[
            "name.required"=>"عنوان نقش الزامی است.",
            "name.max"=>" حداکثر مجاز برای عنوان 255 کارکتراست.",
            "name.unique"=>"این نقش قبلا ثبت شده است.",
            "description.max"=>" حداکثر مجاز برای توضیحات 255 کارکتراست."
        ]);

        $role = Role::create([
            "name"=> $request->name,
            "description"=> $request->description,
        ]);

        $role->permissions()->sync($request->permissions);


        toastr()->success('نقش جدید افزوده شد.');

        return redirect(route('admin.roles.index'));

    }


    public function show($id)
    {
        //
    }


    public function edit(Role $role)
    {
         //اجازه دسترسی
         config(['auth.defaults.guard' => 'admin']);
         $this->authorize('roles.edit');

        $permissions = Permission::where('status',Status::Active)->orderBy('name','asc')->get();
        return view('admin.roles.edit',compact('role','permissions'));
    }


    public function update(Request $request,Role $role)
    {
      //اجازه دسترسی
      config(['auth.defaults.guard' => 'admin']);
      $this->authorize('roles.edit');

        if(!(isset($_POST['permissions']) && is_array($_POST['permissions'])))
        {
            alert()->warning('تعیین سطوح دسترسی الزامی است.');
           return back();
        }

        $request->validate(
            [
                "name"=>['required','max:255',Rule::unique('roles')->ignore($role->id)],
                "description"=>['max:255']
            ],
            [
                "name.required"=>"* عنوان نقش الزامی است.",
                "name.max"=>"* حداکثر مجاز برای عنوان 255 کارکتراست.",
                "name.unique"=>"* این نقش قبلا ثبت شده است.",
                "description.max"=>"* حداکثر مجاز برای توضیحات 255 کارکتراست.",
            ]);


            $staticRols= array('superadmin','doctor','secretary','asistant1','asistant2','reception','adviser-operator','adviser','adviser-arrangement','adviser-manegment','cashier');

            if(in_array($role->name,$staticRols) && ($request->name != $role->name))
            {
                alert()->warning('عنوان نقش های اصلی سیستم قابل تغییر نمی باشد.');
                return back();
            }
            else
            {
                $role->name = $request->name;
                $role->description = $request->description;
                $role->save();

                $role->permissions()->sync($request->permissions);

                toastr()->info('بروزرسانی انجام شد.');
                return redirect(route('admin.roles.index'));
            }
    }


    public function destroy(Role $role)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('roles.destroy');

        $staticRols= array('superadmin','doctor','secretary','asistant1','asistant2','reception','adviser-operator','adviser','adviser-arrangement','adviser-manegment','cashier');

        if(in_array($role->name,$staticRols))
        {
            alert()->warning('نقش های اصلی سیستم قابل حذف نمی باشد.');
            return back();
        }


        if(!$role->admins->isEmpty())
        {
            alert()->warning('هشدار!','لطفا ابتدا نقش کاربرانی که این نقش به آنها اختصاص داده شده است را تغییر دهید.');
            return back();
        }

        $role->delete();
        toastr()->error("نقش ".$role->name." حذف شد. ");
        return back();
    }
}

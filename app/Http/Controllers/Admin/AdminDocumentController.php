<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StaffDocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminBank;
use App\Models\AdminMajor;
use App\Models\AdminMedia;
use App\Models\AdminRetraining;
use App\Models\StaffDocument;
use App\Models\AdminAddress;
use Illuminate\Http\Request;


class AdminDocumentController extends Controller
{
    public function documents(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        return  view('admin.admins.documents',compact('document','admin'));
    }

    public function personal(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $address = AdminAddress::with('province','city')->where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        return  view('admin.admins.personal',compact('address','admin'));
    }

    public function confirm_personel(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.confirm');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->personal_status = StaffDocumentStatus::confirm;
        $document->personal_desc = null;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function reject_personel(Admin $admin,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.reject');

        $request->validate(['desc' => ['required','max:255']],
            [
                'desc.required' => "علت رد شدن را وارد کنید.",
                'desc.max' => "حداکثر طول علت رد شدن 255 کارکتر",
            ]);

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        $document->personal_status = StaffDocumentStatus::reject;
        $document->personal_desc =  $request->desc;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function educations(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $majors =  AdminMajor::where('admin_id',$admin->id)->orderBy('created_at','desc')->get();
        return  view('admin.admins.educations',compact('majors','admin'));
    }

    public function confirm_educations(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.confirm');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->education_status = StaffDocumentStatus::confirm;
        $document->education_desc = null;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function reject_educations(Admin $admin,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.reject');

        $request->validate(['desc' => ['required','max:255']],
            [
                'desc.required' => "علت رد شدن را وارد کنید.",
                'desc.max' => "حداکثر طول علت رد شدن 255 کارکتر",
            ]);

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        $document->education_status = StaffDocumentStatus::reject;
        $document->education_desc =  $request->desc;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function socialmedias(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $medias =  AdminMedia::where('admin_id',$admin->id)->orderBy('created_at','desc')->get();
        return  view('admin.admins.socialmedias',compact('medias','admin'));
    }

    public function confirm_socialmedias(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.confirm');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->socialmedia_status = StaffDocumentStatus::confirm;
        $document->socialmedia_desc = null;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function reject_socialmedias(Admin $admin,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.reject');

        $request->validate(['desc' => ['required','max:255']],
            [
                'desc.required' => "علت رد شدن را وارد کنید.",
                'desc.max' => "حداکثر طول علت رد شدن 255 کارکتر",
            ]);

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        $document->socialmedia_status = StaffDocumentStatus::reject;
        $document->socialmedia_desc =  $request->desc;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function banks(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $banks =  AdminBank::where('admin_id',$admin->id)->orderBy('created_at','desc')->get();
        return  view('admin.admins.banks',compact('banks','admin'));
    }

    public function confirm_banks(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.confirm');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->bank_status = StaffDocumentStatus::confirm;
        $document->bank_desc = null;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function reject_banks(Admin $admin,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.reject');

        $request->validate(['desc' => ['required','max:255']],
            [
                'desc.required' => "علت رد شدن را وارد کنید.",
                'desc.max' => "حداکثر طول علت رد شدن 255 کارکتر",
            ]);

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        $document->bank_status = StaffDocumentStatus::reject;
        $document->bank_desc =  $request->desc;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function retrainings(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.index');

        $retrainings =  AdminRetraining::where('admin_id',$admin->id)->orderBy('created_at','desc')->get();
        return  view('admin.admins.retrainings',compact('retrainings','admin'));
    }

    public function confirm_retrainings(Admin $admin)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.confirm');

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);
        $document->retraining_status = StaffDocumentStatus::confirm;
        $document->retraining_desc = null;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }

    public function reject_retrainings(Admin $admin,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('admins.documents.reject');

        $request->validate(['desc' => ['required','max:255']],
            [
                'desc.required' => "علت رد شدن را وارد کنید.",
                'desc.max' => "حداکثر طول علت رد شدن 255 کارکتر",
            ]);

        $document = StaffDocument::where('admin_id',$admin->id)->firstOrCreate([
            'admin_id'=>$admin->id
        ]);

        $document->retraining_status = StaffDocumentStatus::reject;
        $document->retraining_desc =  $request->desc;
        $document->save();
        return  redirect(route('admin.admins.staff.documents',$admin));
    }


}

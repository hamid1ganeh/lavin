<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceDocument;
use App\Models\ServiceDetail;

class ServiceDocumentController extends Controller
{
    public function index(ServiceDetail $detail)
    {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('services.details.documents.index');

        $documents = ServiceDocument::where('service_id',$detail->id)->orderBy('title','asc')->get();
        return view('admin.details.documents.all',compact('detail','documents'));
    }

    public function create(ServiceDetail $detail)
    {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('services.details.documents.create');

        return view('admin.details.documents.create',compact('detail'));

    }

    public function store(ServiceDetail $detail,Request $request)
    {
               //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('services.details.documents.create');

        $request->validate(
        [
            "title"=>['required','max:255'],
        ],[
            "title.required"=>"عنوان الزامی است.",
            "title.max"=>" حداکثر مجاز برای عنوان 255 کارکتراست.",
        ]);

        ServiceDocument::create([
            "title"=> $request->title,
            "service_id"=> $detail->id,
            "status"=> $request->status,
        ]);

        toastr()->success('zمدرک جدید افزوده شد.');

        return redirect(route('admin.details.documents.index',$detail));
    }


    public function edit(ServiceDetail $detail,ServiceDocument $document)
    {
        //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('services.details.documents.edit');

         return view('admin.details.documents.edit',compact('detail','document'));
    }


    public function update(ServiceDetail $detail,ServiceDocument $document,Request $request)
    {
       //اجازه دسترسی
       config(['auth.defaults.guard' => 'admin']);
       $this->authorize('services.details.documents.edit');

        $request->validate(
        [
            "title"=>['required','max:255','unique:service_documents,id,'.$document->id],
        ],[
            "title.required"=>"عنوان الزامی است.",
            "title.max"=>" حداکثر مجاز برای عنوان 255 کارکتراست.",
            "title.unique"=>"این عنوان قبلا ثبت شده است.",
        ]);

        $document->update([
            "title"=> $request->title,
            "status"=> $request->status,
        ]);

        toastr()->success('بروزرسانی انجام شد.');

        return redirect(route('admin.details.documents.index',$detail));

    }


    public function delete(ServiceDetail $detail,ServiceDocument $document)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('services.details.documents.delete');

        $document->delete();
        toastr()->error('مدرک مورد نظر حذف  شد.');
        return back();
    }
}

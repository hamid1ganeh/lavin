<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\ArticleCategories;
use App\Models\Complication;
use App\Models\ComplicationItem;
use App\Models\RegisterComplication;
use App\Models\ServiceDetail;
use App\Services\ExportService;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class ComplicationController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.index');

        $complications = Complication::orderBy('title', 'asc')->filter()->get();
        return view('admin.complications.all', compact('complications'));
    }

    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.create');

        return view('admin.complications.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.create');

        $request->validate(
            [
                'title' => ['required', 'max:255', 'unique:complications'],
            ],
            [
                "title.required" => "عنوان عارضه را وارد نمایید.",
                "title.max" => "حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
                "title.unique" => "این عارضه قبلا ثبت شده است.",
            ]);


        if ($request->status == Status::Active || $request->status == Status::Deactive) {
            Complication::create(["title" => $request->title, 'status' => $request->status]);
        }

        toast('عارضه جدید ثبت شد.', 'success')->position('bottom-end');

        return redirect(route('admin.complications.index'));
    }


    public function show(Complication $complication)
    {
        //
    }

    public function edit(Complication $complication)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.edit');

        return view('admin.complications.edit', compact('complication'));
    }


    public function update(Request $request, Complication $complication)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.edit');

        $request->validate(
            [
                'title' => ['required', 'max:255', 'unique:complications,id,' . $complication->id],
            ],
            [
                "title.required" => "عنوان عارضه را وارد نمایید.",
                "title.max" => "حداکثر طول مجاز برای عنوان دسته 255 کارکتر است.",
                "title.unique" => "این عارضه قبلا ثبت شده است.",
            ]);


        if ($request->status == Status::Active || $request->status == Status::Deactive) {
            $complication->update(["title" => $request->title, 'status' => $request->status]);
        }

        toast('بروزرسانی با موفقیت انجام شد.', 'success')->position('bottom-end');

        return redirect(route('admin.complications.index'));
    }


    public function destroy(Complication $complication)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('complications.delete');

        $complication->delete();
        toastr()->info('عارضه مورد نظر حذف شد.');
        return redirect(route('admin.complications.index'));
    }

    public function registered()
    {
            //اجازه دسترسی
            config(['auth.defaults.guard' => 'admin']);
            $this->authorize('complications.report');

        $exel = request('exel');
        if (isset($exel) && $exel = 'on') {

            $complications = RegisterComplication::with('complications','reserve.user')
                ->orderBy('created_at', 'desc')
                ->filter()
                ->get();

            $titles = "نام نام خانوادگی" . "\t" . "شماره موبایل" . "\t" . "سرویس" . "\t" . "عوارض" . "\t" . "نسخه" . "\t" . "تاریخ ثبت" . "\t" . "وضعیت";
            $setData = '';
            $rowData = '';


            foreach ($complications as $complication) {
                $fullname = $complication->reserve->user->getFullname() ?? '';
                $mobile = $complication->reserve->user->mobile ?? '';
                $service = $complication->reserve->detail_name ?? '';
                $cps = '';
                foreach ($complication->complications->pluck('title') as $index => $cp) {
                    if ($index > 0) {
                        $cps .= ' , ';
                    }
                    $cps .= $cp;
                }

                $prescription = $complication->prescription ?? '';
                $status = $complication->getStatus() ?? '';
                $date = $complication->register_at() ?? '';

                $rowData .= $fullname . "\t";
                $rowData .= $mobile . "\t";
                $rowData .= $service . "\t";
                $rowData .= $cps . "\t";
                $rowData .= $prescription . "\t";
                $rowData .= $date . "\t";
                $rowData .= $status . "\t" . "\n";

                $setData .= $rowData . "\n";

                $filename = 'export_complications_' . date('YmdHis') . ".xls";

                $export = new ExportService();
                $export->exel($titles, $setData, $filename);
            }
        }

        $complicationItems = ComplicationItem::with('complications','reserve.user')
            ->orderBy('created_at','desc')
            ->filter()
            ->paginate(10)
            ->withQueryString();


        $complicationsList = Complication::orderBy('title','asc')->get();

        $serviceDetails = ServiceDetail::orderBy('name','asc')->get();

        return view('admin.complications.registered',compact('complicationItems','complicationsList','serviceDetails'));
    }
}

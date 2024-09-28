<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Poll;
use App\Models\ServiceDetail;
use App\Models\ServiceReserve;
use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
     public function index()
     {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reviews.index');

        $reviews = Review::with('reviewable')
        ->orderBy('created_at','desc')
        ->filter()
        ->paginate(10)
        ->withQueryString();

        $admins = Admin::orderBy('fullname','asc')->get();

        return view('admin.reviews.all',compact('reviews','admins'));
     }

    public function polls()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('reviews.polls');

        $exel = request('exel');
        if(isset($exel) && $exel='on') {
            $polls = Poll::with('admin','user','reserve')
                ->filter()
                ->orderBy('created_at','desc')
                ->get();

            $titles = "کاربر" . "\t" ."ثبت کننده نظر" . "\t" . "سررویس ارائه شده" . "\t"."زمان انتظار". "\t"."کیفیت سرویس"."\t"."رفتار پرسنل".
                "\t"."رضایت از محصول"."\t"."زمان درج"."\t"."توضیحات";

            $setData = '';
            $rowData = '';
            foreach ($polls as $poll) {
                $fullname = $poll->user->getFullName()??'';
                $mobile = '('.$poll->user->mobile.')'??'';
                $admin = $poll->admin->fullname??' کاربر';
                $serviceName = $poll->reserve->service->name??'';
                $duration = $poll->duration??'';
                $serviceQuality = $poll->serviceQuality??'';
                $staffBehavior = $poll->staffBehavior??'';
                $satisfactionWithProduct = $poll->satisfactionWithProduct??'';
                $createdAt = $poll->created_at()??'';
                $text = $poll->text??'';

                $rowData .=  $fullname.$mobile."\t";
                $rowData .=  $admin."\t";
                $rowData .=  $serviceName."\t";
                $rowData .=  $duration."\t";
                $rowData .=  $serviceQuality."\t";
                $rowData .=  $staffBehavior."\t";
                $rowData .=  $satisfactionWithProduct."\t";
                $rowData .=  $createdAt."\t";
                $rowData .=  $text."\t";
            }
            $setData .= $rowData. "\n";

            $filename = 'export_polls_'.date('YmdHis') . ".xls";

            $export = new ExportService();
            $export->exel($titles,$setData,$filename);
        }


        $polls = Poll::with('admin','user','reserve')
            ->orderBy('created_at','desc')
            ->filter()
            ->paginate(10)
            ->withQueryString();

        $admins = Admin::orderBy('fullname','asc')->get();
        $servicesDetails = ServiceDetail::orderBy('name','asc')->get();

        return view('admin.reviews.polls',compact('polls','admins','servicesDetails'));
    }

}

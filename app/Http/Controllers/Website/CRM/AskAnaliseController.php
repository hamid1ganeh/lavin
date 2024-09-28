<?php

namespace App\Http\Controllers\Website\CRM;

use App\Http\Controllers\Controller;
use App\Models\AnalyseReserve;
use Illuminate\Http\Request;
use Auth;
use App\Enums\AnaliseStatus;
class AskAnaliseController extends Controller
{
    public function index()
    {
        $asks = AnalyseReserve::where('user_id',Auth::id())
            ->orderby('created_at','asc')
            ->paginate(30)
            ->withQueryString();

        return  view('crm.analyse.ask',compact('asks'));
    }

    public function show(AnalyseReserve $ask)
    {
        return  view('crm.analyse.show',compact('ask'));
    }

    public function accept(AnalyseReserve $ask)
    {
        $ask->status =  AnaliseStatus::accept;
        $ask->save();
        toast('تصویر آنالیز شده توسط شما تایید شد.','success')->position('bottom-end');
        return back();
    }

    public function reject(AnalyseReserve $ask)
    {
        $ask->status =  AnaliseStatus::reject;
        $ask->save();
        toast('تصویر آنالیز شده توسط شما تایید شد.','error')->position('bottom-end');
        return back();
    }
}

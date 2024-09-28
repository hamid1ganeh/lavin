<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\AnaliseStatus;
use App\Http\Controllers\Controller;
use App\Models\AnalyseReserve;
use App\Http\Resources\Website\Collections\AnalysisCollection;
use App\Http\Resources\Website\Resources\AnalyzesResource;
use Illuminate\Http\Request;
use Auth;

class AnalyseController extends Controller
{
    public function index()
    {
        $analyzes = AnalyseReserve::where('user_id',Auth('sanctum')->id())
            ->orderby('created_at','asc')
            ->get();

        return response()->json(['analyzes'=> new AnalysisCollection($analyzes) ],200);

    }

    public function show(AnalyseReserve $analysis)
    {
        return response()->json(['analyzes'=> new AnalyzesResource($analysis) ],200);
    }

    public function accept(AnalyseReserve $analysis)
    {
        $analysis->status = AnaliseStatus::accept;
        $analysis->save();
        return response()->json(['message'=> "آنالیز مورد نظر تایید شد." ],201);
    }

    public function reject(AnalyseReserve $analysis)
    {
        $analysis->status = AnaliseStatus::reject;
        $analysis->save();
        return response()->json(['message'=> "آنالیز مورد نظر رد شد." ],201);
    }


}

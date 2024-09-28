<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\ArticleStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\HilightCollection;
use App\Http\Resources\Website\Collections\ServiceCollection;
use App\Http\Resources\Website\Collections\GalleryCollection;
use App\Http\Resources\Website\Collections\ArticleCategoryCollection;
use App\Http\Resources\Website\Collections\DoctorCollection;
use App\Http\Resources\Website\Collections\ProductCategoryCollection;
use App\Models\ArticleCategories;
use App\Models\Doctor;
use App\Models\Gallery;
use App\Models\Highlight;
use App\Models\ProductCategory;
use App\models\Service;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function home()
    {
        $highlights = Highlight::where('status',Status::Active)
            ->whereHas('activeStories')
            ->orderBy('created_at','desc')
            ->get();

        $serviceGroups = Service::where('status',Status::Active)
                                ->whereHas('activeServices')
                                ->where('displayed',true)
                                ->get();

        $galleries = Gallery::where('status',Status::Active)->get();

        $articleCategories = ArticleCategories::with('articles')
                            ->where('status',Status::Active)
                            ->whereHas('articles',function ($q){
                                $q->where('status',ArticleStatus::publish)->where('publishDateTime','>=',Carbon::now('+3:30'));
                            })->get();

        $doctors = Doctor::with('admin')->whereHas('admin',function ($q){
            $q->where('status',Status::Active);
        })->get();

        $productCategory = ProductCategory::where('status',Status::Active)->orderBy('name','asc')->get();



        return response()->json(['highlights'=> new HilightCollection($highlights),
                                 'serviceGroups'=> new ServiceCollection($serviceGroups),
                                 'galleries'=> new GalleryCollection($galleries),
                                 'articleCategories'=> new ArticleCategoryCollection($articleCategories),
                                 'doctors'=> new DoctorCollection($doctors),
                                 'productCategory' => new ProductCategoryCollection($productCategory),
        ],200);

    }

}

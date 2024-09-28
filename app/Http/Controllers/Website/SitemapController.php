<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArticleCategories;
use App\Models\Article;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Product;
use App\Enums\Status;

class SitemapController extends Controller
{
    public function sitemap()
    {
        return response()->view('sitemap.sitemap')->header('Content-Type','text/xml');
    }

    public function statics()
    {
        return response()->view('sitemap.statics')->header('Content-Type','text/xml');
    }

    public function articlecategory()
    {
        $categories=ArticleCategories::all();
        return response()->view('sitemap.articlecategory',compact('categories'))->header('Content-Type','text/xml');
    }

 
    public function service()
    {
        $serviceDetails = ServiceDetail::with('images','comments')->where('status',Status::Active)->orderBy('id','desc')->get();
        return response()->view('sitemap.service',compact('serviceDetails'))->header('Content-Type','text/xml');
    }

    public function article()
    {
        $articles=Article::with('comments')->orderBy('id','desc')->get();
        return response()->view('sitemap.article',compact('articles'))->header('Content-Type','text/xml');
    }

 
    public function product()
    {
        $products = Product::with('comments')->orderBy('id','desc')->get();
        return response()->view('sitemap.product',compact('products'))->header('Content-Type','text/xml');
    }

}

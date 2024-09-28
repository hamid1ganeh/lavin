<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\ArticleStatus;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\ArticleCollection;
use App\Http\Resources\Website\Resources\ArticleResource;
use App\Models\Article;
use App\models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class ArticleController extends Controller
{
     public function articles()
     {
         $articles = Article::with('categories','comments')->where('status',ArticleStatus::publish)
                                  ->where('publishDateTime','<=',Carbon::now('+3:30'))
                                  ->filter()
                                  ->get();

         return response()->json(['articles'=> new ArticleCollection($articles)],200);
     }

     public  function article($slug)
     {
          $service = Article::with('categories','comments')->where('status',ArticleStatus::publish)
                                ->where('publishDateTime','<=',Carbon::now('+3:30'))
                                ->where('slug',$slug)
                                ->first();

          if(is_null($service)){
              return response()->json(['service'=> 'Not found'],404);
          }

         return response()->json(['service'=> new ArticleResource($service)],200);
     }

     public function comment(Article $article,Request $request)
     {
         $validator = Validator::make(request()->all(),
             [
                 'fullname' => ['required','max:255'],
                 'email' => ['required','max:255'],
                 'comment' => ['required'],
             ] ,[
                 'fullname.required' => ' نام و نام خانوادگی الزامی است.',
                 'fullname.max' => 'حداکثر  طول نام و نام خانوادگی  255 کارکتر می باشد.',
                 'email.required' => ' آدرس ایمیل الزامی است.',
                 'email.max' => 'حداکثر  طول آدرس ایمیل  255 کارکتر می باشد.',
                 'comment.required' => ' متن خود را بنویسید.'
             ]
         );

         if ($validator->fails()) {
             return response()->json([
                 'errors' => $validator->errors(),
                 'status' => Response::HTTP_BAD_REQUEST,
             ], Response::HTTP_BAD_REQUEST);
         }

         Comment::create([
             'fullname'=>$request->fullname,
             'email'=>$request->email,
             'comment'=>$request->comment,
             'commentable_id'=> $article->id,
             'commentable_type'=> get_class($article)
         ]);

         return response()->json(['message'=> "نظر شما بعد از تایید در نمایش داده می شود."],200);
     }
}

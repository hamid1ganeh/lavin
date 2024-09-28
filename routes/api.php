<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Website\V1\StoryController;
use App\Http\Controllers\API\Website\V1\ServiceController;
use App\Http\Controllers\API\Website\V1\ArticleController;
use App\Http\Controllers\API\Website\V1\PageController;
use App\Http\Controllers\API\Website\V1\HomeController;
use App\Http\Controllers\API\Website\V1\AuthController;
use App\Http\Controllers\API\Website\V1\NotificationController;
use App\Http\Controllers\API\Website\V1\DiscountController;
use App\Http\Controllers\API\Website\V1\TicketController;
use App\Http\Controllers\API\Website\V1\ReserveController;
use App\Http\Controllers\API\Website\V1\AnalyseController;
use App\Http\Controllers\API\Website\V1\BuyController;
use App\Http\Controllers\API\Website\V1\ProfileController;
use App\Http\Controllers\API\Website\V1\EmploymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->name('v1.')->middleware('RestrictByDomain')->group(function () {

    Route::get('/home', [HomeController::class,'home'])->name('home');

    Route::prefix('/highlights')->name('highlights.')->group(function () {
        Route::get('/', [StoryController::class,'highlights'])->name('list');
        Route::get('{highlight}/stories', [StoryController::class,'stories'])->name('stories');
    });

    Route::patch('/stories/{story}/view', [StoryController::class,'view'])->name('view');
    Route::get('/daily_stories', [StoryController::class,'daily_stories'])->name('daily_stories');


    Route::prefix('/services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class,'services'])->name('list');
        Route::get('/details/{slug}', [ServiceController::class,'service'])->name('.show');
    });

    Route::prefix('/articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class,'articles'])->name('list');
        Route::get('/{slug}', [ArticleController::class,'article'])->name('show');
        Route::post('{article}/comment', [ArticleController::class,'comment'])->name('comment');
    });

    Route::prefix('/employments')->name('employments.')->group(function () {
        Route::get('/form', [EmploymentController::class,'form'])->name('form');
        Route::post('/register', [EmploymentController::class,'register'])->name('register');
    });


    Route::get('/faq',[PageController::class,'faq'])->name('faq');
    Route::get('/contact',[PageController::class,'contact'])->name('contact');

    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::post('/login',[AuthController::class,'login'])->name('login');




    Route::middleware('auth:sanctum')->prefix('/account')->name('account.')->group(function () {
        Route::get('/logout',[AuthController::class,'logout'])->name('logout');

        Route::prefix('/notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class,'index'])->name('index');
            Route::get('{notification}/show', [NotificationController::class,'show'])->name('show');
        });

        Route::prefix('/tickets')->name('tickets.')->group(function () {
            Route::get('/', [TicketController::class,'index'])->name('index');
            Route::get('/info', [TicketController::class,'info'])->name('info');
            Route::post('/store', [TicketController::class,'store'])->name('store');
            Route::get('{ticket}/show', [TicketController::class,'show'])->name('show');
        });

        Route::prefix('/reserves')->name('reserves.')->group(function () {
            Route::get('/', [ReserveController::class,'index'])->name('index');
            Route::get('{reserve}/complications', [ReserveController::class,'complications'])->name('complications');
            Route::post('{reserve}/complications', [ReserveController::class,'register_complications'])->name('complications.register');
            Route::get('{reserve}/review', [ReserveController::class,'review'])->name('review');
            Route::post('{reserve}/review', [ReserveController::class,'register_review'])->name('review.register');
            Route::get('{reserve}/poll', [ReserveController::class,'poll'])->name('poll');
            Route::post('{reserve}/poll', [ReserveController::class,'register_poll'])->name('poll.register');
        });

        Route::prefix('/analyzes')->name('analyzes.')->group(function () {
            Route::get('/', [AnalyseController::class,'index'])->name('index');
            Route::get('{analysis}/show', [AnalyseController::class,'show'])->name('show');
            Route::patch('{analysis}/accept', [AnalyseController::class,'accept'])->name('accept');
            Route::patch('{analysis}/reject', [AnalyseController::class,'reject'])->name('reject');
        });

        Route::get('/discounts', [DiscountController::class,'index'])->name('index');
        Route::get('/buy', [BuyController::class,'index'])->name('buy');

        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/info', [ProfileController::class,'info'])->name('info');
            Route::patch('/info', [ProfileController::class,'update_info'])->name('info.update');
            Route::patch('/change_password', [ProfileController::class,'change_password'])->name('change_password');
            Route::get('/address', [ProfileController::class,'address'])->name('address');
            Route::patch('/address', [ProfileController::class,'address_update'])->name('address.update');
            Route::get('/bank', [ProfileController::class,'bank'])->name('bank');
            Route::patch('/bank', [ProfileController::class,'bank_update'])->name('bank.update');
            Route::get('/other_info', [ProfileController::class,'other_info'])->name('other_info');
            Route::patch('/other_info', [ProfileController::class,'other_info_update'])->name('other_info.update');
        });

    });


});


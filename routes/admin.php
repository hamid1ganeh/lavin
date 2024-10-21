<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmploymentMainCateoryController;
use App\Http\Controllers\Admin\EmploymentSubCateoryController;
use App\Http\Controllers\Admin\EmploumentJobController;
use App\Http\Controllers\Admin\EmploymentController;
use App\Http\Controllers\Admin\GoodsMainController;
use App\Http\Controllers\Admin\GoodsSubController;
use App\Http\Controllers\Admin\GoodsController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\WareHouseOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LaserDeviceController;
use App\Http\Controllers\Admin\ServiceLaserController;

Route::get('/login', 'AuthController@loginPage')->name('loginPage');
Route::post('/login', 'AuthController@login')->name('login');
Route::get('/logout','AuthController@logout')->name('logout');
Route::get('/forgotPass', 'AuthController@forgotPass')->name('forgotPass');
Route::post('/recoveyPass', 'AuthController@recoveyPass')->name('recoveyPass');
Route::get('/changePass/{token}', 'AuthController@changePass')->name('changePass');
Route::PATCH('/changePassword/{token}', 'AuthController@changePassword')->name('changePassword');
Route::get('/fetch_cities', 'HomeController@fetch_cities')->name('fetch_cities');
Route::get('/servicefetch', 'HomeController@servicefetch')->name('servicefetch');
Route::get('/doctorsfetch', 'HomeController@doctorsfetch')->name('doctorsfetch');
Route::get('/detailsfetch', 'HomeController@detailsfetch')->name('detailsfetch');
Route::get('/goodsfetch', 'HomeController@goodsfetch')->name('goodsfetch');



Route::group(['middleware' => 'auth.admin'], function () {

    Route::prefix('/reports')->name('reports.')->group(function () {
        Route::get('/consumptions', [ReportController::class,'consumptions'])->name('consumptions');
        Route::get('/lasers', [ReportController::class,'lasers'])->name('lasers');
    });

  Route::POST('/upload', 'HomeController@videoupload')->name('upload.video');

  Route::get('/', 'HomeController@home')->name('home');

  Route::get('/static', 'HomeController@static')->name('static');

  Route::prefix('admins')->name('admins.')->group(function () {
    Route::get('/', 'AdminController@index')->name('index');
    Route::get('/create', 'AdminController@create')->name('create');
    Route::post('/sotre', 'AdminController@store')->name('store');
    Route::get('{admin}/edit', 'AdminController@edit')->name('edit');
    Route::patch('{admin}/update', 'AdminController@update')->name('update');
    Route::delete('/destroy/{admin}', 'AdminController@destroy')->name('destroy');
    Route::post('/recycle/{id}', 'AdminController@recycle')->name('recycle');

      Route::prefix('{admin}/staff')->name('staff.')->group(function () {

          Route::get('/documents', 'AdminDocumentController@documents')->name('documents');
          Route::prefix('/personal')->name('personal.')->group(function () {
              Route::get('/', 'AdminDocumentController@personal')->name('index');
              Route::patch('/confirm_personel', 'AdminDocumentController@confirm_personel')->name('confirm');
              Route::patch('/reject_personel', 'AdminDocumentController@reject_personel')->name('reject');
          });

          Route::prefix('/educations')->name('educations.')->group(function () {
              Route::get('/', 'AdminDocumentController@educations')->name('index');
              Route::patch('/confirm_educations', 'AdminDocumentController@confirm_educations')->name('confirm');
              Route::patch('/reject_educations', 'AdminDocumentController@reject_educations')->name('reject');
          });

          Route::prefix('/socialmedias')->name('socialmedias.')->group(function () {
              Route::get('/', 'AdminDocumentController@socialmedias')->name('index');
              Route::patch('/confirm_socialmedias', 'AdminDocumentController@confirm_socialmedias')->name('confirm');
              Route::patch('/reject_socialmedias', 'AdminDocumentController@reject_socialmedias')->name('reject');
          });

          Route::prefix('/banks')->name('banks.')->group(function () {
              Route::get('/', 'AdminDocumentController@banks')->name('index');
              Route::patch('/confirm_banks', 'AdminDocumentController@confirm_banks')->name('confirm');
              Route::patch('/reject_banks', 'AdminDocumentController@reject_banks')->name('reject');
          });

          Route::prefix('/retrainings')->name('retrainings.')->group(function () {
              Route::get('/', 'AdminDocumentController@retrainings')->name('index');
              Route::patch('/confirm_retrainings', 'AdminDocumentController@confirm_retrainings')->name('confirm');
              Route::patch('/reject_retrainings', 'AdminDocumentController@reject_retrainings')->name('reject');
          });

      });


      Route::prefix('{admin}/address')->name('address.')->group(function () {
      Route::get('/', 'AdminAddressAdminController@show')->name('show');
      Route::patch('{address}/update', 'AdminAddressAdminController@update')->name('update');
    });

    Route::prefix('{admin}/feilds')->name('feilds.')->group(function () {
      Route::get('/', 'AdminFeildController@index')->name('index');
      Route::get('/create', 'AdminFeildController@create')->name('create');
      Route::post('/sotre', 'AdminFeildController@store')->name('store');
      Route::get('{feild}/edit', 'AdminFeildController@edit')->name('edit');
      Route::patch('{feild}/update', 'AdminFeildController@update')->name('update');
      Route::delete('/destroy/{feild}', 'AdminFeildController@destroy')->name('destroy');
    });

    Route::prefix('{admin}/medias')->name('medias.')->group(function () {
      Route::get('/', 'AdminMediaController@index')->name('index');
      Route::get('/create', 'AdminMediaController@create')->name('create');
      Route::post('/sotre', 'AdminMediaController@store')->name('store');
      Route::get('{media}/edit', 'AdminMediaController@edit')->name('edit');
      Route::patch('{media}/update', 'AdminMediaController@update')->name('update');
      Route::delete('/destroy/{media}', 'AdminMediaController@destroy')->name('destroy');
    });

  });

  Route::prefix('users')->name('users.')->group(function () {

    Route::get('/', 'UserController@index')->name('index');
    Route::get('/create', 'UserController@create')->name('create');
    Route::post('/store', 'UserController@store')->name('store');
    Route::get('{user}/edit', 'UserController@edit')->name('edit');
    Route::patch('{user}/update', 'UserController@update')->name('update');
    Route::delete('{user}/destroy', 'UserController@destroy')->name('destroy');
    Route::post('/recycle/{id}', 'UserController@recycle')->name('recycle');
    Route::get('/fetch', 'UserController@fetch')->name('fetch');
  });

  Route::prefix('numbers')->name('numbers.')->group(function () {

    Route::get('/', 'NumberController@index')->name('index');
    Route::get('/create','NumberController@create')->name('create');
    Route::post('/store','NumberController@store')->name('store');
    Route::get('{number}/edit','NumberController@edit')->name('edit');
    Route::patch('{number}/update','NumberController@update')->name('update');
    Route::delete('{number}/destroy','NumberController@destroy')->name('destroy');
    Route::get('/csv','NumberController@csv')->name('csv');
    Route::post('/import','NumberController@import')->name('import');
    Route::patch('{number}/operator','NumberController@operator')->name('operator');
    Route::patch('{number}/update_oprator','NumberController@update_oprator')->name('update_oprator');
    Route::post('{number}/sms','NumberController@sms')->name('sms');
    Route::post('{number}/register','NumberController@register')->name('register');
    Route::patch('/referall','NumberController@referall')->name('referall');
    Route::get('{number}/show-info','NumberController@showInfo')->name('info.show');
    Route::patch('{number}/update-info','NumberController@updateInfo')->name('info.update');
    Route::get('history/operators','NumberController@operators_history')->name('history.operators');
    Route::get('history/advisers','NumberController@advisers_history')->name('history.advisers');

      Route::prefix('{number}/advisers')->name('advisers.')->group(function () {
          Route::get('/', 'NumberController@advisers')->name('index');
          Route::patch('{adviser}/set-adviser', 'NumberController@set_adviser')->name('set-adviser');
          Route::patch('{adviser}/update_adviser', 'AdviserController@update_adviser')->name('update_adviser');
          Route::post('{adviser}/send-documents', 'AdviserController@send_documents')->name('send_documents');
          Route::patch('{adviser}/recive-documents', 'AdviserController@recive_documents')->name('recive_documents');
          Route::post('{adviser}/reserve', 'AdviserController@reserve')->name('reserve');
          Route::delete('{adviser}/destroy', 'AdviserController@destroy')->name('destroy');
          Route::patch('{adviser}/cancel', 'AdviserController@cancel')->name('cancel');
          Route::POST('{adviser}/review', 'AdviserController@review')->name('review');
    });
  });

  Route::prefix('/advisers')->name('advisers.')->group(function () {
    Route::get('/', 'AdviserController@index')->name('index');
    Route::patch('{adviser}/update_adviser', 'AdviserController@update_adviser')->name('update_adviser');
    Route::post('{adviser}/send-documents', 'AdviserController@send_documents')->name('send_documents');
    Route::patch('{adviser}/recive-documents', 'AdviserController@recive_documents')->name('recive_documents');
    Route::post('{adviser}/reserve', 'AdviserController@reserve')->name('reserve');
  });

  Route::prefix('/sms')->name('sms.')->group(function () {
    Route::get('/history', 'SmsController@history')->name('history');
  });

  Route::prefix('doctors')->name('doctors.')->group(function () {
    Route::get('/', 'DoctorController@index')->name('index');
    Route::get('{doctor}/info', 'DoctorController@info')->name('info');
    Route::patch('{doctor}/update', 'DoctorController@update')->name('update');
    Route::get('{doctor}/video', 'DoctorController@video')->name('video');
    Route::POST('/upload', 'DoctorController@videoupload')->name('upload');
    Route::patch('{doctor}/store_video', 'DoctorController@store_video')->name('video.store');
    Route::delete('delete/{video}', 'DoctorController@videodelete')->name('delete');
  });

  Route::prefix('roles')->name('roles')->group(function () {
    Route::get('/', 'RoleController@index')->name('.index');
    Route::get('/create', 'RoleController@create')->name('.create');
    Route::post('/sotre', 'RoleController@store')->name('.store');
    Route::get('{role}/edit', 'RoleController@edit')->name('.edit');
    Route::patch('{role}/update', 'RoleController@update')->name('.update');
    Route::delete('/destroy/{role}', 'RoleController@destroy')->name('.destroy');
  });

  Route::prefix('levels')->name('levels.')->group(function () {
    Route::get('/', 'LevelController@index')->name('index');
    Route::get('/create', 'LevelController@create')->name('create');
    Route::post('/sotre', 'LevelController@store')->name('store');
    Route::get('{level}/edit', 'LevelController@edit')->name('edit');
    Route::patch('{level}/update', 'LevelController@update')->name('update');
    Route::delete('/destroy/{level}', 'LevelController@destroy')->name('destroy');
    Route::patch('/recycle/{level}', 'LevelController@recycle')->name('recycle');
    Route::get('usersfetch', 'LevelController@usersfetch')->name('usersfetch');
  });


  Route::prefix('galleries')->name('gallery.')->group(function () {
    Route::get('/', 'GalleryController@index')->name('index');
    Route::post('/sotre', 'GalleryController@store')->name('store');
    Route::patch('{gallery}/update', 'GalleryController@update')->name('update');
    Route::delete('/destroy/{gallery}', 'GalleryController@destroy')->name('destroy');

    Route::prefix('{gallery}/images')->name('images.')->group(function () {
      Route::get('/', 'ImageGalleryController@index')->name('index');
      Route::post('/sotre', 'ImageGalleryController@store')->name('store');
      Route::patch('{image}/ImageGalleryController', 'GalleryController@update')->name('update');
      Route::delete('/destroy/{image}', 'ImageGalleryController@destroy')->name('destroy');
    });
  });

  Route::prefix('portfolios')->name('portfolios.')->group(function () {
    Route::get('/', 'PortfolioController@index')->name('index');
    Route::get('/create', 'PortfolioController@create')->name('create');
    Route::post('/sotre', 'PortfolioController@store')->name('store');
    Route::get('{portfolio}/edit', 'PortfolioController@edit')->name('edit');
    Route::patch('{portfolio}/update', 'PortfolioController@update')->name('update');
    Route::delete('/destroy/{portfolio}', 'PortfolioController@destroy')->name('delete');
    Route::patch('/recycle/{portfolio}', 'PortfolioController@recycle')->name('recycle');
    Route::POST('/upload', 'PortfolioController@videoupload')->name('upload');
    Route::delete('{image}/remove_image', 'PortfolioController@remove_image')->name('remove_image');
  });

  Route::prefix('article')->name('article')->group(function () {

    Route::get('/', 'ArticleController@index')->name('.index');
    Route::get('/create', 'ArticleController@create')->name('.create');
    Route::post('/sotre', 'ArticleController@store')->name('.store');
    Route::get('{article}/edit', 'ArticleController@edit')->name('.edit');
    Route::patch('{article}/update', 'ArticleController@update')->name('.update');
    Route::delete('/destroy/{article}', 'ArticleController@destroy')->name('.destroy');
    Route::post('/ckeditor', 'ArticleController@ckeditor')->name('.ckeditor');


    Route::prefix('categories')->name('.categorys.')->group(function () {

        Route::get('/', 'CategoryController@index')->name('index');
        Route::get('/create', 'CategoryController@create')->name('create');
        Route::POST('/store', 'CategoryController@store')->name('store');
        Route::get('{category}/edit', 'CategoryController@edit')->name('edit');
        Route::patch('/update/{category}', 'CategoryController@update')->name('update');
        Route::delete('delete/{category}', 'CategoryController@destroy')->name('delete');
        Route::delete('recycle/{category}', 'CategoryController@recycle')->name('recycle');
        Route::delete('forceDelete/{category}', 'CategoryController@forceDelete')->name('forceDelete');

    });

  });


  Route::prefix('services')->name('services.')->group(function () {

      Route::get('/', 'ServiceController@index')->name('index');
      Route::get('/create', 'ServiceController@create')->name('create');
      Route::post('/sotre', 'ServiceController@store')->name('store');
      Route::get('{service}/edit', 'ServiceController@edit')->name('edit');
      Route::patch('{service}/update', 'ServiceController@update')->name('update');
      Route::delete('/destroy/{service}', 'ServiceController@destroy')->name('delete');
      Route::patch('/recycle/{id}', 'ServiceController@recycle')->name('recycle');
      Route::get('/fetch_details', 'ServiceController@fetch_details')->name('fetch_details');

      Route::prefix('categories')->name('categories.')->group(function () {

        Route::get('/', 'ServiceCategoryController@index')->name('index');
        Route::get('/create', 'ServiceCategoryController@create')->name('create');
        Route::POST('/store', 'ServiceCategoryController@store')->name('store');
        Route::get('{category}/edit', 'ServiceCategoryController@edit')->name('edit');
        Route::patch('/update/{category}', 'ServiceCategoryController@update')->name('update');
        Route::delete('delete/{category}', 'ServiceCategoryController@destroy')->name('delete');
        Route::get('/fetch_child', 'ServiceCategoryController@fetch_child')->name('fetch_child');

        Route::prefix('{parent}/sub')->name('sub.')->group(function () {

          Route::get('/', 'ServiceCategoryController@subindex')->name('index');
          Route::get('/create', 'ServiceCategoryController@subcreate')->name('create');
          Route::POST('/store', 'ServiceCategoryController@substore')->name('store');
          Route::get('{category}/edit', 'ServiceCategoryController@subedit')->name('edit');
          Route::patch('/update/{category}', 'ServiceCategoryController@subupdate')->name('update');
          Route::delete('delete/{category}', 'ServiceCategoryController@subdestroy')->name('delete');

      });

    });

      Route::prefix('lasers')->name('lasers.')->group(function () {
          Route::get('/', [ServiceLaserController::class,'index'])->name('index');
          Route::get('/create', [ServiceLaserController::class,'create'])->name('create');
          Route::post('/store', [ServiceLaserController::class,'store'])->name('store');
          Route::get('{laser}/edit', [ServiceLaserController::class,'edit'])->name('edit');
          Route::patch('{laser}/update', [ServiceLaserController::class,'update'])->name('update');
          Route::delete('/destroy/{laser}', [ServiceLaserController::class,'destroy'])->name('destroy');
          Route::delete('/recycle/{id}', [ServiceLaserController::class,'recycle'])->name('recycle');
      });

  });

  Route::prefix('service_details')->name('details.')->group(function () {

    Route::get('/', 'ServiceDetailController@index')->name('index');
    Route::get('/create', 'ServiceDetailController@create')->name('create');
    Route::POST('/store', 'ServiceDetailController@store')->name('store');
    Route::get('{detail}/edit', 'ServiceDetailController@edit')->name('edit');
    Route::patch('{detail}/update', 'ServiceDetailController@update')->name('update');
    Route::delete('{detail}/delete', 'ServiceDetailController@destroy')->name('delete');
    Route::patch('/recycle/{id}', 'ServiceDetailController@recycle')->name('recycle');

    Route::prefix('{detail}/images')->name('images.')->group(function () {
      Route::get('/', 'ServiceDetailController@showimages')->name('show');
      Route::POST('/store', 'ServiceDetailController@imagestore')->name('store');
      Route::delete('delete/{image}', 'ServiceDetailController@imagedelete')->name('delete');
    });

    Route::prefix('{detail}/videos')->name('videos.')->group(function () {
      Route::get('/', 'ServiceDetailController@showvideos')->name('show');
      Route::get('/create', 'ServiceDetailController@imagecreate')->name('create');
      Route::POST('/store', 'ServiceDetailController@videostore')->name('store');
      Route::POST('/upload', 'ServiceDetailController@videoupload')->name('upload');
      Route::delete('delete/{video}', 'ServiceDetailController@videodelete')->name('delete');
    });

    Route::prefix('{detail}/luck')->name('luck.')->group(function () {
      Route::get('/', 'ServiceDetailController@luckcreate')->name('create');
      Route::POST('/store', 'ServiceDetailController@luckstore')->name('store');
    });

    Route::prefix('{detail}/documents')->name('documents.')->group(function () {
      Route::get('/', 'ServiceDocumentController@index')->name('index');
      Route::get('/create', 'ServiceDocumentController@create')->name('create');
      Route::POST('/store', 'ServiceDocumentController@store')->name('store');
      Route::get('{document}/edit', 'ServiceDocumentController@edit')->name('edit');
      Route::patch('{document}/update', 'ServiceDocumentController@update')->name('update');
      Route::delete('{document}/delete', 'ServiceDocumentController@delete')->name('delete');

     });

});




    Route::prefix('comments')->name('comments.')->group(function () {
      Route::get('/', 'CommentsController@index')->name('index');
      Route::PATCH('update/{comment}', 'CommentsController@update')->name('update');
      Route::delete('/destroy/{comment}', 'CommentsController@destroy')->name('destroy');
  });


  Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', 'ReviewController@index')->name('index');
    Route::PATCH('update/{comment}', 'ReviewController@update')->name('update');
    Route::delete('/destroy/{comment}', 'ReviewController@destroy')->name('destroy');
    Route::get('/polls', 'ReviewController@polls')->name('polls');
 });

  Route::prefix('luck')->name('luck.')->group(function () {
      Route::get('/', 'LuckController@index')->name('index');
      Route::post('/sotre', 'LuckController@store')->name('store');
      Route::PATCH('/{luck}', 'LuckController@update')->name('update');
      Route::delete('{luck}/delete', 'LuckController@destroy')->name('destroy');
  });


  Route::prefix('discounts')->name('discounts.')->group(function () {
    Route::get('/', 'DiscountController@index')->name('index');
    Route::get('/create', 'DiscountController@create')->name('create');
    Route::get('/code', 'DiscountController@code')->name('code');
    Route::post('/sotre', 'DiscountController@store')->name('store');
    Route::get('{discount}/edit', 'DiscountController@edit')->name('edit');
    Route::patch('{discount}/update', 'DiscountController@update')->name('update');
    Route::delete('/destroy/{discount}', 'DiscountController@destroy')->name('destroy');
    Route::patch('/recycle/{discount}', 'DiscountController@recycle')->name('recycle');
    Route::patch('{discount}/festival_update', 'DiscountController@festival_update')->name('festival.update');

    Route::prefix('{discount}/users')->name('users.')->group(function () {
      Route::get('/', 'DiscountController@users_show')->name('show');
      Route::patch('/update', 'DiscountController@users_update')->name('update');
      Route::get('/update', 'LevelController@users')->name('fetch');
    });

    Route::prefix('{discount}/services')->name('services')->group(function () {
      Route::get('/', 'DiscountController@services_show')->name('.show');
      Route::patch('/update', 'DiscountController@services_update')->name('.update');
    });

  });


  Route::prefix('shop')->name('shop.')->group(function () {

    Route::prefix('/products')->name('products.')->group(function () {

      Route::get('/', 'ProductController@index')->name('index');
      Route::get('/create', 'ProductController@create')->name('create');
      Route::POST('/store', 'ProductController@store')->name('store');
      Route::get('{product}/edit', 'ProductController@edit')->name('edit');
      Route::patch('{product}/update', 'ProductController@update')->name('update');
      Route::delete('{product}/delete', 'ProductController@destroy')->name('delete');
      Route::patch('/recycle/{id}', 'ProductController@recycle')->name('recycle');

      Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'ProductCategoryController@index')->name('index');
        Route::get('/create', 'ProductCategoryController@create')->name('create');
        Route::POST('/store', 'ProductCategoryController@store')->name('store');
        Route::get('{category}/edit', 'ProductCategoryController@edit')->name('edit');
        Route::patch('/update/{category}', 'ProductCategoryController@update')->name('update');
        Route::delete('delete/{category}', 'ProductCategoryController@destroy')->name('delete');
        Route::get('/fetch_child', 'ProductCategoryController@fetch_child')->name('fetch_child');

          Route::prefix('{parent}/sub')->name('sub.')->group(function () {
            Route::get('/', 'ProductCategoryController@subindex')->name('index');
            Route::get('/create', 'ProductCategoryController@subcreate')->name('create');
            Route::POST('/store', 'ProductCategoryController@substore')->name('store');
            Route::get('{category}/edit', 'ProductCategoryController@subedit')->name('edit');
            Route::patch('/update/{category}', 'ProductCategoryController@subupdate')->name('update');
            Route::delete('delete/{category}', 'ProductCategoryController@subdestroy')->name('delete');
        });
      });

      Route::prefix('{product}/images')->name('images.')->group(function () {

        Route::get('/', 'ProductController@show')->name('show');
        Route::POST('/store', 'ProductController@imagestore')->name('store');
        Route::delete('delete/{image}', 'ProductController@imagedelete')->name('delete');

      });

      Route::prefix('{product}/attributes')->name('attributes.')->group(function () {
        Route::get('/', 'ProductController@show_attributes')->name('show');
        Route::patch('/update', 'ProductController@update_attributes')->name('update');
      });

      Route::prefix('{product}/luck')->name('luck.')->group(function () {
        Route::get('/', 'ProductController@luckcreate')->name('create');
        Route::POST('/store', 'ProductController@luckstore')->name('store');
      });

    });

    Route::prefix('sells')->name('sells.')->group(function () {

      Route::get('/', 'SellController@index')->name('index');
      Route::patch('{order}/update', 'SellController@update')->name('update');

    });
  });

  Route::prefix('notifications')->name('notifications.')->group(function () {
      Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', 'NotificationController@user_index')->name('index');
            Route::get('/create', 'NotificationController@user_create')->name('create');
            Route::post('/sotre', 'NotificationController@store')->name('store');
            Route::patch('{notification}/update', 'NotificationController@update')->name('update');
            Route::delete('/destroy/{notification}', 'NotificationController@destroy')->name('destroy');
            Route::post('/recycle/{id}', 'NotificationController@recycle')->name('recycle');
      });

      Route::prefix('admins')->name('admins.')->group(function () {
          Route::get('/', 'NotificationController@admin_index')->name('index');
          Route::get('/create', 'NotificationController@admin_create')->name('create');
          Route::post('/sotre', 'NotificationController@store')->name('store');
          Route::patch('{notification}/update', 'NotificationController@update')->name('update');
          Route::delete('/destroy/{notification}', 'NotificationController@destroy')->name('destroy');
          Route::post('/recycle/{id}', 'NotificationController@recycle')->name('recycle');
      });

      Route::get('/', 'NotificationController@index')->name('index');
      Route::get('{notification}/show', 'NotificationController@show')->name('show');
  });


  Route::prefix('rewiewGroups')->name('rewiewGroups.')->group(function () {

      Route::get('/', 'ReviewGroupController@index')->name('index');
      Route::get('/create', 'ReviewGroupController@create')->name('create');
      Route::POST('/store', 'ReviewGroupController@store')->name('store');
      Route::get('{group}/edit', 'ReviewGroupController@edit')->name('edit');
      Route::patch('/update/{group}', 'ReviewGroupController@update')->name('update');
      Route::delete('delete/{group}', 'ReviewGroupController@delete')->name('delete');

  });

    Route::prefix('/receptions')->name('receptions.')->group(function () {
        Route::get('/', 'ReceptionController@index')->name('index');
        Route::patch('{reception}/end', 'ReceptionController@end')->name('end');
        Route::patch('{reception}/start', 'ReceptionController@start')->name('start');
    });


  Route::prefix('reserves')->name('reserves.')->group(function () {
    Route::get('/upgrades', 'ReserveServiceController@upgrades')->name('upgrades');
    Route::get('/', 'ReserveServiceController@index')->name('index');
    Route::get('/create', 'ReserveServiceController@create')->name('create');
    Route::post('/store', 'ReserveServiceController@store')->name('store');
    Route::get('{reserve}/show', 'ReserveServiceController@show')->name('show');
    Route::patch('{reserve}/reserve', 'ReserveServiceController@reserve')->name('reserve');
    Route::patch('{reserve}/determining', 'ReserveServiceController@determining')->name('determining');
    Route::delete('/destroy/{reserve}', 'ReserveServiceController@destroy')->name('destroy');
    Route::patch('{reserve}/secratry', 'ReserveServiceController@secratry')->name('secratry');
    Route::patch('{reserve}/done', 'ReserveServiceController@done')->name('done');
    Route::get('{reserve}/payment', 'ReserveServiceController@payment')->name('payment');
    Route::post('{payment}/pay', 'ReserveServiceController@pay')->name('pay');
    Route::post('{reserve}/poll', 'ReserveServiceController@poll')->name('poll');
    Route::patch('{reserve}/price', 'ReserveServiceController@price')->name('price');

    Route::prefix('{reserve}/upgrade')->name('upgrade.')->group(function () {
      Route::get('/', 'ReserveUpgradeController@index')->name('index');
      Route::get('/create', 'ReserveUpgradeController@create')->name('create');
      Route::post('/store', 'ReserveUpgradeController@store')->name('store');
      Route::get('{upgrade}/edit', 'ReserveUpgradeController@edit')->name('edit');
      Route::patch('{upgrade}/update', 'ReserveUpgradeController@update')->name('update');
      Route::patch('{upgrade}/confirm', 'ReserveUpgradeController@confirm')->name('confirm');
      Route::delete('{upgrade}/delete', 'ReserveUpgradeController@delete')->name('delete');
    });

      Route::prefix('{reserve}/consumptions')->name('consumptions.')->group(function () {
          Route::get('/', 'ReserveConsumptionController@index')->name('index');
          Route::post('/create', 'ReserveConsumptionController@store')->name('store');
          Route::patch('{consumption}/update', 'ReserveConsumptionController@update')->name('update');
          Route::delete('/{consumption}/delete', 'ReserveConsumptionController@delete')->name('delete');+

          Route::prefix('/lasers')->name('lasers.')->group(function () {
              Route::get('/', 'ReserveConsumptionLaserController@index')->name('index');
              Route::post('/create', 'ReserveConsumptionLaserController@store')->name('store');
              Route::patch('/{consumption}/update', 'ReserveConsumptionLaserController@update')->name('update');
              Route::delete('/{consumption}/delete', 'ReserveConsumptionLaserController@delete')->name('delete');
          });
      });



    Route::prefix('{reserve}/complications')->name('complications.')->group(function () {
      Route::get('/show', 'RegisterComplicationController@show')->name('show');
      Route::post('/create', 'RegisterComplicationController@create')->name('create');
      Route::patch('/{complication}/update', 'RegisterComplicationController@update')->name('update');
      Route::delete('/{complication}/delete', 'RegisterComplicationController@delete')->name('delete');
      Route::post('/{complication}/reserve', 'RegisterComplicationController@reserve')->name('reserve');
    });

  });


    Route::prefix('ask/analysis')->name('ask.analysis.')->group(function () {
        Route::get('/', 'AskAnaliseController@index')->name('index');
        Route::get('{ask}/show', 'AskAnaliseController@show')->name('show');
        Route::patch('{ask}/doctor', 'AskAnaliseController@doctor')->name('doctor');
        Route::patch('{ask}/response', 'AskAnaliseController@response')->name('response');
        Route::post('{ask}/voice', 'AskAnaliseController@voice')->name('voice');
        Route::patch ('{ask}/voice/remove', 'AskAnaliseController@voice_remove')->name('voice.remove');

    });

  Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', 'JobController@index')->name('index');
    Route::get('/create', 'JobController@create')->name('create');
    Route::post('/sotre', 'JobController@store')->name('store');
    Route::get('{job}/edit', 'JobController@edit')->name('edit');
    Route::patch('{job}/update', 'JobController@update')->name('update');
    Route::delete('/destroy/{job}', 'JobController@destroy')->name('destroy');
    Route::patch('{job}/recycle', 'JobController@recycle')->name('recycle');
  });

  Route::prefix('departments')->name('departments.')->group(function () {

      Route::get('/', 'DepartmentController@index')->name('index');
      Route::get('/create', 'DepartmentController@create')->name('create');
      Route::POST('/store', 'DepartmentController@store')->name('store');
      Route::get('{department}/edit', 'DepartmentController@edit')->name('edit');
      Route::patch('/{department}', 'DepartmentController@update')->name('update');
      Route::delete('delete/{department}', 'DepartmentController@destroy')->name('delete');
      Route::patch('{department}/recycle', 'DepartmentController@recycle')->name('recycle');
  });

  Route::prefix('tickets')->name('tickets.')->group(function () {

    Route::get('/', 'TicketController@index')->name('index');
    Route::get('/create', 'TicketController@create')->name('create');
    Route::POST('/store', 'TicketController@store')->name('store');
    Route::POST('/ticketmessage/{ticket}', 'TicketController@ticketmessage')->name('ticketmessage');
    Route::patch('/{ticket}', 'TicketController@update')->name('update');
    Route::get('{ticket}/show', 'TicketController@show')->name('show');
    Route::delete('delete/{ticket}', 'TicketController@destroy')->name('delete');
    Route::get('/getaudience/{id}', 'TicketController@getaudience')->name('getaudience');
  });

  Route::prefix('provinces')->name('provinces.')->group(function () {
    Route::get('/', 'ProvinceController@index')->name('index');
//    Route::get('{provance}/edit', 'ProvanceController@edit')->name('edit');
//    Route::patch('{provance}/update', 'ProvanceController@update')->name('update');

    Route::prefix('{province}/cities')->name('cities.')->group(function () {
      Route::get('/', 'CityController@index')->name('index');
      Route::get('/create', 'CityController@create')->name('create');
      Route::post('/store', 'CityController@store')->name('store');
      Route::get('{city}/edit', 'CityController@edit')->name('edit');
      Route::patch('{city}/update', 'CityController@update')->name('update');
      Route::delete('{city}/delete', 'CityController@delete')->name('delete');
      Route::post('/recycle/{city}', 'CityController@recycle')->name('recycle');

      Route::prefix('{city}/parts')->name('parts.')->group(function () {
        Route::get('/', 'PartController@index')->name('index');
        Route::get('/create', 'PartController@create')->name('create');
        Route::post('/store', 'PartController@store')->name('store');
        Route::get('{part}/edit', 'PartController@edit')->name('edit');
        Route::patch('{part}/update', 'PartController@update')->name('update');
          Route::delete('{part}/delete', 'PartController@delete')->name('delete');
          Route::post('/recycle/{part}', 'PartController@recycle')->name('recycle');

          Route::prefix('{part}/areas')->name('areas.')->group(function () {
              Route::get('/', 'AreaController@index')->name('index');
              Route::get('/create', 'AreaController@create')->name('create');
              Route::post('/store', 'AreaController@store')->name('store');
              Route::get('{area}/edit', 'AreaController@edit')->name('edit');
              Route::patch('{area}/update', 'AreaController@update')->name('update');
              Route::delete('delete/{area}', 'AreaController@destroy')->name('delete');
              Route::patch('{area}/recycle', 'AreaController@recycle')->name('recycle');

          });

    });

  });
  });

  Route::prefix('socialmedia')->name('socialmedia.')->group(function () {
    Route::get('/', 'SocialmediaController@index')->name('index');
    Route::get('/create', 'SocialmediaController@create')->name('create');
    Route::POST('/store', 'SocialmediaController@store')->name('store');
    Route::get('{socialmedia}/edit', 'SocialmediaController@edit')->name('edit');
    Route::patch('/{socialmedia}', 'SocialmediaController@update')->name('update');
    Route::delete('delete/{socialmedia}', 'SocialmediaController@destroy')->name('delete');
    Route::post('{socialmedia}/recycle', 'SocialmediaController@recycle')->name('recycle');
  });

  Route::prefix('phones')->name('phones.')->group(function () {
    Route::get('/', 'PhoneController@index')->name('index');
    Route::get('/create', 'PhoneController@create')->name('create');
    Route::POST('/store', 'PhoneController@store')->name('store');
    Route::get('{phone}/edit', 'PhoneController@edit')->name('edit');
    Route::patch('{phone}/update', 'PhoneController@update')->name('update');
    Route::delete('{phone}/delete', 'PhoneController@destroy')->name('delete');

  });


  Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', 'MessageController@index')->name('index');
    Route::get('{message}/show', 'MessageController@show')->name('show');
    Route::delete('{message}/delete', 'MessageController@destroy')->name('delete');
  });


  Route::prefix('faq')->name('faq.')->group(function () {

    Route::get('/', 'FaqController@index')->name('index');
    Route::get('/create', 'FaqController@create')->name('create');
    Route::post('/store', 'FaqController@store')->name('store');
    Route::get('{faq}/edit', 'FaqController@edit')->name('edit');
    Route::patch('{faq}/update', 'FaqController@update')->name('update');
    Route::delete('{faq}/destroy', 'FaqController@destroy')->name('destroy');

  });

    Route::prefix('branchs')->name('branchs.')->group(function () {
        Route::get('/', 'BranchController@index')->name('index');
        Route::get('/create', 'BranchController@create')->name('create');
        Route::post('/store', 'BranchController@store')->name('store');
        Route::get('{branch}/edit', 'BranchController@edit')->name('edit');
        Route::patch('{branch}/update', 'BranchController@update')->name('update');
        Route::delete('{branch}/destroy', 'BranchController@destroy')->name('destroy');
        Route::patch('{branch}/recycle', 'BranchController@recycle')->name('recycle');
    });

    Route::prefix('festivals')->name('festivals.')->group(function () {
        Route::get('/', 'FestivalController@index')->name('index');
        Route::get('/create', 'FestivalController@create')->name('create');
        Route::post('/store', 'FestivalController@store')->name('store');
        Route::get('{festival}/edit', 'FestivalController@edit')->name('edit');
        Route::patch('{festival}/update', 'FestivalController@update')->name('update');
        Route::delete('{festival}/destroy', 'FestivalController@destroy')->name('destroy');
        Route::patch('{festival}/display', 'FestivalController@display')->name('display');
    });

    Route::prefix('analysis')->name('analysis.')->group(function () {
        Route::get('/', 'AnalyseController@index')->name('index');
        Route::get('/create', 'AnalyseController@create')->name('create');
        Route::get('{analise}/detail', 'AnalyseController@detail')->name('detail');
        Route::post('/store', 'AnalyseController@store')->name('store');
        Route::get('{analise}/edit', 'AnalyseController@edit')->name('edit');
        Route::patch('{analise}/update', 'AnalyseController@update')->name('update');
        Route::delete('{analise}/destroy', 'AnalyseController@destroy')->name('destroy');
        Route::patch('{analise}/recycle', 'AnalyseController@recycle')->name('recycle');
        Route::post('{analise}/images', 'AnalyseController@images')->name('images');
        Route::patch('{image}/image_update', 'AnalyseController@image_update')->name('image.update');
        Route::delete('{image}/image_delete', 'AnalyseController@image_delete')->name('image.delete');
    });

    Route::prefix('stories')->name('stories.')->group(function () {
        Route::get('/', 'StoryController@index')->name('index');
        Route::get('/create', 'StoryController@create')->name('create');
        Route::POST('/store', 'StoryController@store')->name('store');
        Route::get('{story}/edit', 'StoryController@edit')->name('edit');
        Route::Patch('{story}/update', 'StoryController@update')->name('update');
        Route::delete('{story}/delete', 'StoryController@delete')->name('delete');
    });


    Route::prefix('highlights')->name('highlights.')->group(function () {
        Route::get('/', 'HighlighController@index')->name('index');
        Route::get('/create', 'HighlighController@create')->name('create');
        Route::POST('/store', 'HighlighController@store')->name('store');
        Route::get('{highlight}/edit', 'HighlighController@edit')->name('edit');
        Route::patch('/update/{highlight}', 'HighlighController@update')->name('update');
        Route::delete('delete/{highlight}', 'HighlighController@delete')->name('delete');
    });


    Route::prefix('staff/')->name('staff.')->group(function () {
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', 'StaffController@documents')->name('index');
            Route::get('/personal', 'StaffController@personal')->name('personal');
            Route::patch('/personal', 'StaffController@personal_update')->name('personal.update');

            Route::prefix('educations')->name('educations.')->group(function () {
                Route::get('/', 'StaffController@educations')->name('index');
                Route::post('/store', 'StaffController@education_store')->name('store');
                Route::patch('{major}/update', 'StaffController@education_update')->name('update');
                Route::delete('{major}/delete', 'StaffController@education_delete')->name('delete');
            });

            Route::prefix('socialmedias')->name('socialmedias.')->group(function () {
                Route::get('/', 'StaffController@socialmedias')->name('index');
                Route::post('/store', 'StaffController@media_store')->name('store');
                Route::patch('{media}/update', 'StaffController@media_update')->name('update');
                Route::delete('{media}/delete', 'StaffController@media_delete')->name('delete');
            });

            Route::prefix('banks')->name('banks.')->group(function () {
                Route::get('/', 'StaffController@banks')->name('index');
                Route::post('/store', 'StaffController@bank_store')->name('store');
                Route::patch('{bank}/update', 'StaffController@bank_update')->name('update');
                Route::delete('{bank}/delete', 'StaffController@bank_delete')->name('delete');
            });

            Route::prefix('retrainings')->name('retrainings.')->group(function () {
                Route::get('/', 'StaffController@retrainings')->name('index');
                Route::post('/store', 'StaffController@retraining_store')->name('store');
                Route::patch('{retraining}/update', 'StaffController@retraining_update')->name('update');
                Route::delete('{retraining}/delete', 'StaffController@retraining_delete')->name('delete');
            });


        });
    });




    Route::prefix('employments')->name('employments.')->group(function () {

        Route::get('/', [EmploymentController::class,'index'])->name('index');
        Route::patch('{employment}/response', [EmploymentController::class,'response'])->name('response');
        Route::patch('{employment}/refer', [EmploymentController::class,'refer'])->name('refer');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [EmploymentMainCateoryController::class,'index'])->name('main.index');
            Route::get('/create', [EmploymentMainCateoryController::class,'create'])->name('main.create');
            Route::post('/store', [EmploymentMainCateoryController::class,'store'])->name('main.store');
            Route::get('{main}/edit', [EmploymentMainCateoryController::class,'edit'])->name('main.edit');
            Route::patch('{main}/update', [EmploymentMainCateoryController::class,'update'])->name('main.update');
            Route::delete('/destroy/{main}', [EmploymentMainCateoryController::class,'destroy'])->name('main.destroy');
            Route::patch('/recycle/{main}', [EmploymentMainCateoryController::class,'recycle'])->name('main.recycle');
            Route::get('/fetch_sub', [EmploymentSubCateoryController::class,'fetch_sub'])->name('fetch_sub');

            Route::prefix('{main}/sub')->name('sub.')->group(function () {
                Route::get('/', [EmploymentSubCateoryController::class,'index'])->name('index');
                Route::get('/create', [EmploymentSubCateoryController::class,'create'])->name('create');
                Route::post('/store', [EmploymentSubCateoryController::class,'store'])->name('store');
                Route::get('{sub}/edit', [EmploymentSubCateoryController::class,'edit'])->name('edit');
                Route::patch('{sub}/update', [EmploymentSubCateoryController::class,'update'])->name('update');
                Route::delete('/destroy/{sub}', [EmploymentSubCateoryController::class,'destroy'])->name('destroy');
                Route::patch('/recycle/{sub}', [EmploymentSubCateoryController::class,'recycle'])->name('recycle');
            });
        });

        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [EmploumentJobController::class,'index'])->name('index');
            Route::get('/create', [EmploumentJobController::class,'create'])->name('create');
            Route::post('/store', [EmploumentJobController::class,'store'])->name('store');
            Route::get('{job}/edit', [EmploumentJobController::class,'edit'])->name('edit');
            Route::patch('{job}/update', [EmploumentJobController::class,'update'])->name('update');
            Route::delete('/destroy/{job}', [EmploumentJobController::class,'destroy'])->name('destroy');
            Route::patch('/recycle/{job}', [EmploumentJobController::class,'recycle'])->name('recycle');
        });

    });


    Route::prefix('warehousing')->name('warehousing.')->group(function () {

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [GoodsMainController::class,'index'])->name('main.index');
            Route::get('/create', [GoodsMainController::class,'create'])->name('main.create');
            Route::post('/store', [GoodsMainController::class,'store'])->name('main.store');
            Route::get('{main}/edit', [GoodsMainController::class,'edit'])->name('main.edit');
            Route::patch('{main}/update', [GoodsMainController::class,'update'])->name('main.update');
            Route::delete('/destroy/{main}', [GoodsMainController::class,'destroy'])->name('main.destroy');
            Route::patch('/recycle/{main}', [GoodsMainController::class,'recycle'])->name('main.recycle');
            Route::get('/fetch_sub', [GoodsMainController::class,'fetch_sub'])->name('fetch_sub');

            Route::prefix('{main}/sub')->name('sub.')->group(function () {
                Route::get('/', [GoodsSubController::class,'index'])->name('index');
                Route::get('/create', [GoodsSubController::class,'create'])->name('create');
                Route::post('/store', [GoodsSubController::class,'store'])->name('store');
                Route::get('{sub}/edit', [GoodsSubController::class,'edit'])->name('edit');
                Route::patch('{sub}/update', [GoodsSubController::class,'update'])->name('update');
                Route::delete('/destroy/{sub}', [GoodsSubController::class,'destroy'])->name('destroy');
                Route::patch('/recycle/{sub}', [GoodsSubController::class,'recycle'])->name('recycle');
            });
        });

        Route::prefix('goods')->name('goods.')->group(function () {
            Route::get('/', [GoodsController::class,'index'])->name('index');
            Route::get('/create', [GoodsController::class,'create'])->name('create');
            Route::post('/store', [GoodsController::class,'store'])->name('store');
            Route::get('{good}/edit', [GoodsController::class,'edit'])->name('edit');
            Route::patch('{good}/update', [GoodsController::class,'update'])->name('update');
            Route::delete('/destroy/{good}', [GoodsController::class,'destroy'])->name('destroy');
            Route::patch('/recycle/{good}', [GoodsController::class,'recycle'])->name('recycle');
        });

        Route::prefix('warehouses')->name('warehouses.')->group(function () {
            Route::get('/', [WarehouseController::class,'index'])->name('index');
            Route::get('/create', [WarehouseController::class,'create'])->name('create');
            Route::post('/store', [WarehouseController::class,'store'])->name('store');
            Route::get('{warehouse}/edit', [WarehouseController::class,'edit'])->name('edit');
            Route::patch('{warehouse}/update', [WarehouseController::class,'update'])->name('update');
            Route::delete('/destroy/{warehouse}', [WarehouseController::class,'destroy'])->name('destroy');
            Route::patch('/recycle/{warehouse}', [WarehouseController::class,'recycle'])->name('recycle');
            Route::get('{warehouse}/stocks', [WarehouseController::class,'stocks'])->name('stocks');

            Route::prefix('{warehouse}/orders')->name('orders.')->group(function () {
                Route::get('/', [WareHouseOrderController::class,'index'])->name('index');
                Route::post('/change', [WareHouseOrderController::class,'store'])->name('store');
                Route::patch('{order}/update', [WareHouseOrderController::class,'update'])->name('update');
                Route::patch('{order}/deliver', [WareHouseOrderController::class,'deliver'])->name('deliver');
                Route::delete('{order}/destroy', [WareHouseOrderController::class,'destroy'])->name('destroy');
            });
        });

        Route::prefix('lasers')->name('lasers.')->group(function () {
            Route::get('/', [LaserDeviceController::class,'index'])->name('index');
            Route::get('/create', [LaserDeviceController::class,'create'])->name('create');
            Route::post('/store', [LaserDeviceController::class,'store'])->name('store');
            Route::get('{laser}/edit', [LaserDeviceController::class,'edit'])->name('edit');
            Route::patch('{laser}/update', [LaserDeviceController::class,'update'])->name('update');
            Route::delete('/destroy/{laser}', [LaserDeviceController::class,'destroy'])->name('destroy');
            Route::delete('/recycle/{laser}', [LaserDeviceController::class,'recycle'])->name('recycle');
            Route::post('{laser}/tube', [LaserDeviceController::class,'tube'])->name('tube');
            Route::get('{laser}/tube/history', [LaserDeviceController::class,'history'])->name('tube.history');
        });


    });


    Route::prefix('complications')->name('complications.')->group(function () {
        Route::get('/', 'ComplicationController@index')->name('index');
        Route::get('/create', 'ComplicationController@create')->name('create');
        Route::POST('/store', 'ComplicationController@store')->name('store');
        Route::get('{complication}/edit', 'ComplicationController@edit')->name('edit');
        Route::patch('{complication}/update', 'ComplicationController@update')->name('update');
        Route::delete('{complication}/delete', 'ComplicationController@destroy')->name('delete');
        Route::delete('{complication}/delete', 'ComplicationController@destroy')->name('delete');
        Route::get('/registered', 'ComplicationController@registered')->name('registered');
    });


});

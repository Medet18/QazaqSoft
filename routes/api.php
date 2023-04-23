<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\manager\ManagerController;
use App\Http\Controllers\user\Controller;
use App\Http\Controllers\user\OrderController;
use Illuminate\Support\Facades\Route;


//Route only for managers

Route::get('show',[ProductController::class, 'show']);
Route::group(['prefix' => 'manager'], function ($router) {
    Route::post('/login', [ManagerController::class, 'login']);

});

Route::group(['middleware' => ['auth:manager-api', 'jwt.auth'], 'prefix' => 'manager'], function ($router) {
    Route::post('/', [ManagerController::class, 'logout']);

    //edit admins
    Route::group(['prefix' => 'edit/admins'], function ($router) {
        Route::get('/', [ManagerController::class, 'index_subadmins']);
        Route::get('/{id}', [ManagerController::class, 'show_subadmin']);
        Route::delete('/{id}', [ManagerController::class, 'destroy_subadmin']);
    });

    //edit users
    Route::group(['prefix' => 'edit/users'], function ($router) {
        Route::get('/', [ManagerController::class, 'index_users']);
        Route::get('/{id}', [ManagerController::class, 'show_user']);
        Route::delete('/{id}', [ManagerController::class, 'destroy_user']);
    });
});


//Routes for admins
Route::group(['prefix' => 'admin'], function ($router) {
    Route::post('/login', [AdminController::class, 'login']);

});
Route::group(['middleware' => ['auth:admin-api', 'jwt.auth'], 'prefix' => 'admin'], function ($router) {
    Route::post('/', [AdminController::class, 'logout']);

    Route::group(['prefix' => 'edit/category'], function ($router) {
        Route::get('/', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'edit/product'], function ($router) {
        Route::get('/show',[ProductController::class, 'show']);
        Route::get('/', [ProductController::class, 'showForAdmins']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'edit/orders'], function ($router) {
        Route::get('/showOrders',[OrderController::class, 'showOrderAdmin']);

        Route::put('/{id}', [OrderController::class, 'confirm']);
      //  Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});


//Routes for users
Route::group(['prefix' => 'user'], function ($router) {
    Route::post('/register', [Controller::class, 'register']);
    Route::post('/login', [Controller::class, 'login']);
});
Route::group(['middleware' => ['auth:user-api', 'jwt.auth'], 'prefix' => 'user'], function ($router) {
    Route::post('/', [Controller::class, 'logout']);

    Route::group(['prefix' => 'order/products'], function () {
        Route::get('/showCategories', [CategoryController::class, 'show']);
        Route::get('/showProducts', [ProductController::class, 'show']);
        Route::get('/showProducts/{category}',[ProductController::class, 'showByCategory']);

        Route::get('showOrders',[OrderController::class,'showOrderUser']);
        Route::get('showConfirm/{status}',[OrderController::class,'showConfirmedOrder']);
        Route::post('/',[OrderController::class, 'order']);
        Route::delete('/{id}',[OrderController::class,'destroy']);
    });
});










//Route::group(['prefix'=>'user'], function ($router){
//   Route::post('store', [CommentController::class,'store']);
//   Route::put('update/{id}', [CommentController::class,'update']);
//   Route::delete('delete/{id}', [CommentController::class,'destroy']);
//});
//
//Route::group(['prefix'=>'post'], function ($router) {
//    Route::get('show', [PostController::class, 'show']);
//    Route::get('showall', [PostController::class, 'getPost']);
//
//});
//
//Route::group(['prefix'=>'edit'], function ($router) {
//    Route::post('/store', [PostController::class, 'store']);
//    Route::put('/update/{id}', [PostController::class, 'update']);
//    Route::delete('/delete/{id}',[PostController::class, 'destroy']);
//});


<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix'=>'user'], function ($router){
   Route::post('store', [CommentController::class,'store']);
   Route::put('update/{id}', [CommentController::class,'update']);
   Route::delete('delete/{id}', [CommentController::class,'destroy']);
});

Route::group(['prefix'=>'post'], function ($router) {
    Route::get('show', [PostController::class, 'show']);
    Route::get('showall', [PostController::class, 'getPost']);

});

Route::group(['prefix'=>'edit'], function ($router) {
    Route::post('/store', [PostController::class, 'store']);
    Route::put('/update/{id}', [PostController::class, 'update']);
    Route::delete('/delete/{id}',[PostController::class, 'destroy']);
});


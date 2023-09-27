<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
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

// Route::post('/getUsers',[UsersController::class,'getUsers']);

Route::controller(UsersController::class)->group(function(){
    Route::get('/getUsers','getUsers');
    Route::post('/register','register');
    Route::post('/login','login');
});

Route::controller(ProductController::class)->group(function(){
    Route::post('/addProduct','addProduct');
    Route::post('/updateProduct','updateProduct');
    Route::get('/viewProducts','viewProducts');
    Route::delete('/deleteProduct/{id}','deleteProduct');
    Route::get('/getProduct/{id}','getProduct');
    Route::get('/searchProduct/{key}','searchProduct');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

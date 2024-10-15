<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SerialController;
use App\Http\Controllers\CustomerController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('getAllUser', [AuthController::class, 'getAllUser']);
    Route::get('getUser/{id}', [AuthController::class, 'getUser']);
    Route::put('updateUser/{id}', [AuthController::class, 'updateUser']);
    Route::put('suspendUser/{id}', [AuthController::class, 'suspendUser']);

    Route::get('cat',[CatController::class,'index']);
    Route::get('cat/{id}',[CatController::class,'show']);
    Route::post('cat', [CatController::class, 'store']);
    Route::put('cat/{id}', [CatController::class, 'update']);
    Route::put('catUpdateStatus/{id}', [CatController::class, 'updateStatus']);

    Route::get('Product',[ProductController::class,'index']);
    Route::get('Product/{id}',[ProductController::class,'show']);
    Route::post('/addProduct', [ProductController::class, 'store']);
    Route::put('Product/{id}',[ProductController::class,'update']);

    Route::get('products/{productId}/serials', [SerialController::class, 'index']); 
    Route::post('serials', [SerialController::class, 'store']);
    Route::get('serials/{id}', [SerialController::class, 'show']);
    Route::put('serials/{id}', [SerialController::class, 'update']);


    Route::get('customer',[CustomerController::class,'index']);
    Route::get('customer/{id}',[CustomerController::class,'show']);
    Route::put('customer/{id}',[CustomerController::class,'update']);
    Route::post('customer', [CustomerController::class, 'store']);
    Route::put('suspendCus/{id}', [CustomerController::class, 'suspendCus']);

});
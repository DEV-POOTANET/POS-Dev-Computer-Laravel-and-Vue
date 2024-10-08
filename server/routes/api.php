<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatController;
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

});
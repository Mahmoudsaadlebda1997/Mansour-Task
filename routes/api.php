<?php

use App\Http\Controllers\Api\MealsApiController;
use App\Http\Controllers\Api\OrderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationApiController;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => '','namespace' => 'Api'],function (){
    Route::group(['prefix' => 'reservations'],function (){
        Route::post('/check' , [ReservationApiController::class,'checkDate']);
        Route::post('/reserve' , [ReservationApiController::class,'reserveTable'])->middleware('check.reservation');
    });
    Route::group(['prefix' => 'meals'],function (){
        Route::get('/allMeals' , [MealsApiController::class,'allMeals']);
    });
    Route::group(['prefix' => 'orders'],function (){
        Route::post('/createOrder' , [OrderApiController::class,'createOrder']);
    });
});


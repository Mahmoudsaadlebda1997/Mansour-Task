<?php

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
//Route::group(['prefix' => '','namespace' => 'Api'],function (){
//    Route::group(['prefix' => 'reservations'],function (){
//        Route::post('/check','ReservationApiController@checkDate');
//    });
//});
//Route::post('/reservations/check', 'Api\ReservationApiController@checkDate');
Route::post('/reservations/check' , [ReservationApiController::class,'checkDate']);


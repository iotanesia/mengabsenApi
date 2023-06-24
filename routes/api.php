<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\Bri\BriController;
use App\Http\Controllers\Api\LocationControler;
use App\Http\Controllers\Api\Mandiri\MandiriController;
use App\Http\Controllers\Api\RSAController;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

Route::get('health', HealthCheckJsonResultsController::class);
//with middleware
Route::prefix('v1')
->namespace('Api')
->middleware('write.log')
->group(function () {
    Route::get('/test',function (Request $request){
       return "service up";
    });
    Route::post('login',[AuthControler::class,'login']);
    Route::post('regis',[AuthControler::class,'regis']);
    Route::get('version',[AuthControler::class,'version']);
    Route::middleware('apiAuth')->group(function () {
        Route::get('get-attend-now',[AbsensiController::class,'attendNow']);
        Route::get('master/location',[LocationControler::class,'getAll']);
        Route::get('banner/dashboard',[LocationControler::class,'getBannerDashboard']);
        Route::post('master/location',[LocationControler::class,'save']);
        Route::post('master/location/{id}',[LocationControler::class,'update']);
        Route::delete('master/location/{id}',[LocationControler::class,'delete']);
        Route::get('absen',[AbsensiController::class,'listAbsen']);
        Route::post('absen',[AbsensiController::class,'absen']); 
        Route::get('user/profile',[AuthControler::class,'profile']);
    });
});


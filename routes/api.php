<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\Bri\BriController;
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
    Route::middleware('apiAuth')->group(function () {
        Route::post('absen',[AbsensiController::class,'absen']); 
    });
});


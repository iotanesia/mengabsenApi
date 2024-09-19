<?php

use App\Models\Reimburs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\RSAController;
use App\Http\Controllers\Api\UserControler;
use App\Http\Controllers\Api\CutiController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\Bri\BriController;
use App\Http\Controllers\Api\LocationControler;
use App\Http\Controllers\Api\ReimbursController;
use App\Http\Controllers\Api\Mandiri\MandiriController;
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
        Route::get('dropdown-leave',[AbsensiController::class,'ddLeave']);
        Route::get('master/location',[LocationControler::class,'getAll']);
        Route::get('banner/dashboard',[LocationControler::class,'getBannerDashboard']);
        Route::post('master/location',[LocationControler::class,'save']);
        Route::post('master/location/{id}',[LocationControler::class,'update']);
        Route::delete('master/location/{id}',[LocationControler::class,'delete']);
        Route::get('absen',[AbsensiController::class,'listAbsen']);
        Route::post('absen',[AbsensiController::class,'absen']);
        Route::get('user/profile',[AuthControler::class,'profile']);
        Route::post('user/profile',[AuthControler::class,'updateProfile']);
        
        Route::get('cuti/all', [CutiController::class, 'getAll']);
        Route::get('cuti', [CutiController::class, 'get']);
        Route::get('cuti/{id}', [CutiController::class, 'detail']);
        Route::post('cuti', [CutiController::class, 'create']);
        Route::post('cuti/{id}', [CutiController::class, 'update']);
        Route::delete('cuti/{id}', [CutiController::class, 'delete']);
        Route::get('cuti/lampiran/{id}', [CutiController::class, 'lampiran']);
        Route::get('cuti/form/{id}', [CutiController::class, 'formCuti']);
        Route::get('jabatan', [CutiController::class, 'jabatan']);
        
        Route::get('user/sisa', [CutiController::class, 'sisa']); //Data Sisa Cuti
        
        Route::get('reimburs/tujuan', [ReimbursController::class, 'tujuan']);
        Route::get('reimburs', [ReimbursController::class, 'get']);
        Route::post('reimburs', [ReimbursController::class, 'create']);
        Route::get('reimburs/all', [ReimbursController::class, 'getAll']);
        Route::post('reimburs/{id}', [ReimbursController::class, 'update']);
        Route::get('reimburs/bukti', [ReimbursController::class, 'bukti']); 

        Route::get('tugas/tujuan', [TaskController::class, 'tujuan']);
        Route::post('tugas', [TaskController::class, 'create']);
        Route::get('tugas', [TaskController::class, 'get']);
        Route::get('tugas/all', [TaskController::class, 'getAll']);
        Route::post('tugas/{id}', [TaskController::class, 'update']);
        Route::get('tugas/form/{id}', [TaskController::class, 'form']);
        Route::get('tugas/bukti/{id}', [TaskController::class, 'bukti']);

        Route::get('salary/users', [SalaryController::class, 'users']);
        Route::post('salary', [SalaryController::class, 'create']);
        Route::get('salary/all', [SalaryController::class, 'getAll']);
    });
    Route::get('history-absen',[AbsensiController::class,'historyAbsen']);
    Route::get('absen/rekap',[AbsensiController::class,'generateRekapAbsen']);
});


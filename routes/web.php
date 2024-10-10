<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\UserRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::user()) {
        return redirect('home');
    }else {
        return redirect('login');
    }
});

Route::get('health', HealthCheckResultsController::class);
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/supaniga', function(){
    // User::insert([
    //     'app_name' => 'Superadmin',
    //     'username' => 'admin',
    //     'email' => 'admin@admin.com',
    //     'password' => Hash::make('admin'),
    //     'created_at' => Carbon::now(),
    //     'updated_at' => Carbon::now(),
    //     'nama_lengkap' => 'Superadmin',
    //     'NIK' => '123123124',
    //     'sisa_cuti' => 12
    // ]);     

    // UserRole::insert(['id_user' => 36, 'code_role' => '00']);

    // // echo UserRole::all();
    // $niga = User::find(36);
    // $niga->username = "super admin";
    // $niga->save();
    
    // echo User::find(36);
});

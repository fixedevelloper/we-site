<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[HomeController::class,'home'])->name('home');
Route::get('/documentation',[HomeController::class,'documentation'])->name('documentation');
Route::get('/contact',[HomeController::class,'contact'])->name('contact');
Route::get('/about',[HomeController::class,'about'])->name('about');
Route::get('/service_collete',[HomeController::class,'service_collete'])->name('service_collete');
Route::get('/service_sms',[HomeController::class,'service_sms'])->name('service_sms');
Route::get('/service_transfert',[HomeController::class,'service_transfert'])->name('service_transfert');

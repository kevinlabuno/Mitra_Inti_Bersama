<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will
// | be assigned to the "web" middleware group. Make something great!
// |
// */

Route::get('/', function () {
    return view('welcome');
});


Route::get('index',[IndexController::class,'index'])->name('index');

Route::get('send',[EmailController::class,'send'])->name('sent');
Route::get('reset',[EmailController::class,'reset'])->name('reset');
Route::get('token',[EmailController::class,'token'])->name('token');
Route::get('verif',[EmailController::class,'verif'])->name('verif');
Route::get('sendreset',[EmailController::class,'sendreset'])->name('send.reset');
Route::get('sendemail', [EmailController::class,'testEmail'])->name('sendemail');


Route::get('/forgot-password', [EmailController::class, 'forgotpassword'])->name('password.request');
Route::post('/send-otp', [EmailController::class, 'sendOtp'])->name('sendOtp');
Route::get('/verify-otp', [EmailController::class, 'showVerifyOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [EmailController::class, 'verifyOtp'])->name('otp.check');
Route::get('/resetpassword', [EmailController::class, 'resetPasswordview'])->name('password.reset');
Route::post('/resetpassword', [EmailController::class, 'resetPassword'])->name('password.update');
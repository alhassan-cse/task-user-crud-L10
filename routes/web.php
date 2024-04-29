<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('home');
Route::resource('users', App\Http\Controllers\Backend\UsersController::class);
Route::post('user/mail/send', [App\Http\Controllers\Backend\UsersController::class,'user_mail_send'])->name('user.mail.send');

// Route::resource('users', ItemController::class);
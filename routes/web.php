<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DokumenController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::post('user/delete', 'App\Http\Controllers\UserController@delete')->name('user.delete');

    Route::resource('dokumen', App\Http\Controllers\DokumenController::class);
    Route::post('dokumen/delete', 'App\Http\Controllers\DokumenController@delete')->name('dokumen.delete');
    Route::get('/signOut/{id}', [DokumenController::class, 'showSign'])->name('signOut');
});
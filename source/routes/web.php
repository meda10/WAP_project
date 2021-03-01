<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{any}', [App\Http\Controllers\AppController::class, 'index'])->where('any', '.*');

/*Route::get('/', function () {
    return view('welcome', ['name' => 'Kokos']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/titles', [App\Http\Controllers\TitleContoller::class, 'index']);*/

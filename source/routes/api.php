<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

Route::post('/login', [App\Http\Controllers\Api\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\LoginController::class, 'logout']);
Route::post('/register', [App\Http\Controllers\Api\RegisterController::class, 'register']);
Route::post('/password', [App\Http\Controllers\Api\ForgotPasswordController::class, 'reset']);
Route::get('/genres_menu', [App\Http\Controllers\Api\TitlesController::class, 'genresMenu']);
Route::post('/filter_movies_by_genre', [App\Http\Controllers\Api\TitlesController::class, 'filterMoviesByGenre']);
Route::post('/filter_series_by_genre', [App\Http\Controllers\Api\TitlesController::class, 'filterSeriesByGenre']);
Route::post('/genre_info_from_url', [App\Http\Controllers\Api\TitlesController::class, 'getGenreInfoFromUrl']);

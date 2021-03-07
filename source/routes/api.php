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

Route::post('/genre_info_from_url', [App\Http\Controllers\Api\TitlesController::class, 'getGenreInfoFromUrl']);
Route::post('/get_titles', [App\Http\Controllers\Api\TitlesController::class, 'getTitles']);

Route::get('/genres_menu', [App\Http\Controllers\Api\TitlesController::class, 'genresMenu']);
Route::get('/get_genres_movies', [App\Http\Controllers\Api\TitlesController::class, 'getGenresMovies']);
Route::get('/get_genres_series', [App\Http\Controllers\Api\TitlesController::class, 'getGenresSeries']);

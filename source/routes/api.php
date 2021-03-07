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

Route::post('/genre_info_from_url', 'Api\TitlesController@getGenreInfoFromUrl');
Route::post('/get_titles', 'Api\TitlesController@getTitles');

Route::get('/genres_menu', 'Api\TitlesController@genresMenu');
Route::get('/get_genres_movies', 'Api\TitlesController@getGenresMovies');
Route::get('/get_genres_series', 'Api\TitlesController@getGenresSeries');

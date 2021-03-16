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
Route::post('/get_title', 'Api\TitlesController@getTitle');

Route::post('/update_user_name', 'Api\UsersController@updateName');
Route::post('/update_user_surname', 'Api\UsersController@updateSurname');
Route::post('/update_user_password', 'Api\UsersController@updatePassword');
Route::post('/update_user_address', 'Api\UsersController@updateAddress');
Route::post('/confirm_user/{id}', 'Api\UsersController@confirm_user');

Route::get('/get_all_titles_search', 'Api\TitlesController@getAllTitlesSearch');
Route::get('/genres_menu', 'Api\TitlesController@genresMenu');
Route::get('/get_genres_movies', 'Api\TitlesController@getGenresMovies');
Route::get('/get_genres_series', 'Api\TitlesController@getGenresSeries');

//select
Route::get('/get_actors', 'Api\ParticipantController@get_items_select');
Route::get('/get_states', 'Api\StateController@get_items_select');
Route::get('/get_stores_select', 'Api\StoreController@get_items_select');

//Title
Route::get('/get_titles', 'Api\TitlesController@index');
Route::post('/get_title_by_id', 'Api\TitlesController@show');
Route::post('/set_titles', 'Api\TitlesController@store');
Route::put('/update_title/{id}', 'Api\TitlesController@update');
Route::delete('/delete_title/{id}', 'Api\TitlesController@destroy');

//Participants
Route::get('/get_actors', 'Api\ParticipantController@index');
Route::post('/get_actor_by_id', 'Api\ParticipantController@show');
Route::post('/set_actor', 'Api\ParticipantController@store');
Route::put('/update_actor/{id}', 'Api\ParticipantController@update');
Route::delete('/delete_actor/{id}', 'Api\ParticipantController@destroy');

//User
Route::get('/get_users', 'Api\UsersController@index');
Route::post('/get_user_by_id', 'Api\UsersController@show');
Route::put('/update_user/{id}', 'Api\UsersController@update');
Route::delete('/delete_user/{id}', 'Api\UsersController@destroy');

//Store


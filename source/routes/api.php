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

Route::get('/get_all_titles_search', 'Api\TitlesController@getAllTitlesSearch');
Route::get('/genres_menu', 'Api\TitlesController@genresMenu');
Route::get('/get_genres_movies', 'Api\TitlesController@getGenresMovies');
Route::get('/get_genres_series', 'Api\TitlesController@getGenresSeries');
Route::get('/get_stores', 'Api\StoresController@getStores');

//select + buttons
Route::get('/get_actors_select', 'Api\ParticipantController@get_items_select');
Route::get('/get_states_select', 'Api\StatesController@get_items_select');
Route::get('/get_stores_select', 'Api\StoresController@get_items_select');
Route::post('/confirm_user/{id}', 'Api\UsersController@confirm_user');

//Title
Route::get('/get_titles', 'Api\TitlesController@index');
Route::get('/get_title/{id}', 'Api\TitlesController@show');
Route::post('/set_titles', 'Api\TitlesController@store');
Route::put('/update_title/{id}', 'Api\TitlesController@update');
Route::delete('/delete_title/{id}', 'Api\TitlesController@destroy');

//Participants
Route::get('/get_actors', 'Api\ParticipantController@index');
Route::get('/get_actor/{id}', 'Api\ParticipantController@show');
Route::post('/set_actor', 'Api\ParticipantController@store');
Route::put('/update_actor/{id}', 'Api\ParticipantController@update');
Route::delete('/delete_actor/{id}', 'Api\ParticipantController@destroy');

//Discount
Route::post('/check_discount_code', 'Api\DiscountsController@checkDiscountCode');
Route::post('/make_reservation', 'Api\ReservationsController@makeReservation');
Route::post('/get_user_reservations', 'Api\ReservationsController@getUserReservations');
Route::post('/cancel_reservation', 'Api\ReservationsController@cancelReservation');

//User
Route::get('/get_users', 'Api\UsersController@index');
Route::get('/get_user/{id}', 'Api\UsersController@show');
Route::put('/update_user/{id}', 'Api\UsersController@update');
Route::delete('/delete_user/{id}', 'Api\UsersController@destroy');

//Store
Route::get('/get_stores', 'Api\StoresController@index');
Route::get('/get_store/{id}', 'Api\StoresController@show');
Route::post('/set_store', 'Api\StoresController@store');
Route::put('/update_store/{id}', 'Api\StoresController@update');
Route::delete('/delete_store/{id}', 'Api\StoresController@destroy');


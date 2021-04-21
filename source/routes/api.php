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

Route::get('/get_all_titles_search', 'Api\TitlesController@getAllTitlesSearch');
Route::get('/genres_menu', 'Api\TitlesController@genresMenu');
Route::get('/get_genres_movies', 'Api\TitlesController@getGenresMovies');
Route::get('/get_genres_series', 'Api\TitlesController@getGenresSeries');
Route::get('/get_stores', 'Api\StoresController@getStores');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/login_info', 'Api\UsersController@get_user_info');

    //Settings
    Route::post('/update_user_name', 'Api\UsersController@updateName')->middleware('permission:Basic permissions');;
    Route::post('/update_user_surname', 'Api\UsersController@updateSurname')->middleware('permission:Basic permissions');;
    Route::post('/update_user_password', 'Api\UsersController@updatePassword')->middleware('permission:Basic permissions');;
    Route::post('/update_user_address', 'Api\UsersController@updateAddress')->middleware('permission:Basic permissions');;

    //select + buttons
    Route::get('/get_actors_select', 'Api\ParticipantController@get_items_select');
    Route::get('/get_states_select', 'Api\StatesController@get_items_select');
    Route::get('/get_stores_select', 'Api\StoresController@get_items_select');
    Route::get('/get_languages_select', 'Api\LanguageController@get_items_select');
    Route::post('/confirm_user/{id}', 'Api\UsersController@confirm_user')->middleware('permission:Confirm users');

    //Title
    Route::get('/get_all_titles', 'Api\TitlesController@index')->middleware('permission:View all titles');
    Route::get('/get_one_title/{url}', 'Api\TitlesController@show')->middleware('permission:Edit all titles');
    Route::post('/set_titles', 'Api\TitlesController@store')->middleware('permission:Edit all titles');
    Route::put('/update_title/{url}', 'Api\TitlesController@update')->middleware('permission:Edit all titles');
    Route::delete('/delete_title/{id}', 'Api\TitlesController@destroy')->middleware('permission:Edit all titles');
    Route::post('/upload_image', 'Api\TitlesController@upload_image')->middleware('permission:Upload images');

    //Participants
    Route::get('/get_all_actors', 'Api\ParticipantController@index')->middleware('permission:View all participants');
    Route::get('/get_one_actor/{id}', 'Api\ParticipantController@show')->middleware('permission:Edit all participants');
    Route::post('/set_actor', 'Api\ParticipantController@store')->middleware('permission:Edit all participants');
    Route::put('/update_actor/{id}', 'Api\ParticipantController@update')->middleware('permission:Edit all participants');
    Route::delete('/delete_actor/{id}', 'Api\ParticipantController@destroy')->middleware('permission:Edit all participants');

    //Reservations
    Route::post('/check_discount_code', 'Api\DiscountsController@checkDiscountCode')->middleware('permission:Basic permissions');
    Route::post('/make_reservation', 'Api\ReservationsController@makeReservation')->middleware('permission:Basic permissions');
    Route::post('/get_user_reservations', 'Api\ReservationsController@getUserReservations')->middleware('permission:Basic permissions');
    Route::post('/get_user_reservations_by_email', 'Api\ReservationsController@getUserReservationsByEmail')->middleware('permission:Basic permissions');
    Route::post('/pay_reservation', 'Api\ReservationsController@payReservation')->middleware('permission:Basic permissions');
    Route::post('/return_reservation', 'Api\ReservationsController@returnReservation')->middleware('permission:Basic permissions');
    Route::post('/cancel_reservation', 'Api\ReservationsController@cancelReservation')->middleware('permission:Basic permissions');
    Route::post('/pay_fines', 'Api\ReservationsController@payFines')->middleware('permission:Basic permissions');

    //User
    Route::get('/get_all_users', 'Api\UsersController@index')->middleware('permission:View_all_users');
    Route::get('/get_one_user/{id}', 'Api\UsersController@show')->middleware('permission:Basic permissions');
    Route::post('/set_user', 'Api\UsersController@store')->middleware('permission:Add all users');
    Route::get('/get_user_by_id', 'Api\UsersController@getUserById')->middleware('permission:Edit all users');
    Route::put('/update_user/{id}', 'Api\UsersController@update')->middleware('permission:Edit all users');
    Route::delete('/delete_user/{id}', 'Api\UsersController@destroy')->middleware('permission:Edit all users');

    //Store
    Route::get('/get_all_stores', 'Api\StoresController@index')->middleware('permission:View all stores');
    Route::get('/get_one_store/{id}', 'Api\StoresController@show')->middleware('permission:Edit all stores');
    Route::post('/set_store', 'Api\StoresController@store')->middleware('permission:Edit all stores');
    Route::put('/update_store/{id}', 'Api\StoresController@update')->middleware('permission:Edit all stores');
    Route::delete('/delete_store/{id}', 'Api\StoresController@destroy')->middleware('permission:Edit all stores');

    //Discount
    Route::get('/get_all_discounts', 'Api\DiscountsController@index')->middleware('permission:View all discounts');
    Route::post('/set_discount', 'Api\DiscountsController@store')->middleware('permission:Edit all discounts');
    Route::delete('/delete_discount/{id}', 'Api\DiscountsController@destroy')->middleware('permission:Edit all discounts');

    //roles
    Route::get('/permissions', 'Api\RoleManager@permissionsIndex')->middleware('permission:View all permissions');
    Route::get('/roles', 'Api\RoleManager@rolesIndex')->middleware('permission:View all permissions');
    Route::post('/roles/{role}/assign/{user}', 'Api\RoleManager@rolesAddUser')->middleware('permission:Assign role');
    Route::post('/roles/{role}/unassign/{user}', 'Api\RoleManager@rolesRemoveUser')->middleware('permission:Remove role');
});

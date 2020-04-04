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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['jwt.verify']], function () {

	Route::get('login/check', "UserController@LoginCheck");
    Route::post('logout', "UserController@Logout");
    
    Route::get('user', "UserController@index");
    Route::get('user/{limit}/{offset}', "UserController@getAll");
    
    Route::get('daily', "DailyController@index");
    Route::get('daily/{limit}/{offset}/{id_user}', "DailyController@getAll");
    Route::post('daily', "DailyController@store");
    Route::put('daily/{id}', "DailyController@update");;
    Route::delete('daily/{id}', "DailyController@destroy");
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'UserController@login'); //login
Route::post('register', 'UserController@register'); //registet


Route::group(['middleware' => ['jwt.verify']], function () {

Route::post('logout', "UserController@logout"); //cek token
Route::get('login/check', "UserController@LoginCheck"); //cek token
Route::get('user/index', "UserController@index"); //read semua user
Route::get('user/{limit}/{offset}', "UserController@getAll"); //read dengan limit user

//daily_scrum
Route::get('daily_scrum', "DailyScrumController@index"); //read daily_scrum
Route::get('daily_scrum/{limit}/{offset}/{id_users}', "DailyScrumController@getAll"); //read daily_scrum
Route::post('daily_scrum', 'DailyScrumController@store'); //create daily_scrum
Route::delete('daily_scrum/{id}', "DailyScrumController@delete"); //delete daily_scrum
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

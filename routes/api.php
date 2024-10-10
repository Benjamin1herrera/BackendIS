<?php

use Illuminate\Support\Facades\Route;

// Route::middleware(['api', 'auth'])->group(function (){
//     Route::controller(\App\Http\Controllers\AuthController::class)->group(function (){
//         Route::post('login', 'login')->withoutMiddleware(['api', 'auth']);;
//         Route::post('logout', 'logout');
//         Route::get('user', 'UserLogged');
//     });

//     Route::controller(\App\Http\Controllers\UsersController::class)->group(function (){
//         Route::post('register', 'register')->withoutMiddleware(['api', 'auth']);
//         Route::delete('delete/{id}', 'delete')->withoutMiddleware(['api', 'auth']);
//     });

// });

Route::post('register', 'App\Http\Controllers\UsersController@register')->withoutMiddleware(['api', 'auth']);
Route::get('login', 'App\Http\Controllers\AuthController@login')->name('login');

//User
Route::get('user', 'App\Http\Controllers\AuthController@UserLogged')->middleware('auth:api');
Route::post('user/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');
Route::get('user/getAll', 'App\Http\Controllers\UsersController@getAll')->middleware('auth:api');
Route::delete('user/delete/{id}', 'App\Http\Controllers\UsersController@delete')->middleware('auth:api');

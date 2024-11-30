<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'App\Http\Controllers\AuthController@login')->name('login');

// Register
Route::post('register', 'App\Http\Controllers\UsersController@register')->withoutMiddleware(['api', 'auth']);

//RegisterClient
Route::post('register-client', 'App\Http\Controllers\UsersController@registerClient')->withoutMiddleware(['api', 'auth']);

// Product routes
Route::post('registerproduct', 'App\Http\Controllers\ProductController@registerNewProduct')->withoutMiddleware(['api', 'auth']);

// Change Password
Route::post('change-password', 'App\Http\Controllers\UsersController@changePassword')->withoutMiddleware(['api', 'auth']);

// User routes
Route::get('user', 'App\Http\Controllers\AuthController@UserLogged')->middleware('auth:api');
Route::post('user/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');
Route::get('user/getAll', 'App\Http\Controllers\UsersController@getAll')->middleware('auth:api');
Route::delete('user/delete/{id}', 'App\Http\Controllers\UsersController@delete')->middleware('auth:api');

//Manage Clients
Route::get('user/manageCustomers', 'App\Http\Controllers\UsersController@manageCustomers')->withoutMiddleware('auth:api');
<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'App\Http\Controllers\AuthController@login')->name('login');

// Register
Route::post('register', 'App\Http\Controllers\UsersController@register')->withoutMiddleware(['api', 'auth']);

//RegisterClient
Route::post('register-client', 'App\Http\Controllers\UsersController@registerClient')->withoutMiddleware(['api', 'auth']);

// Product routes
Route::post('registerproduct', 'App\Http\Controllers\ProductController@registerNewProduct')->withoutMiddleware(['api', 'auth']);
Route::post('registerbook', 'App\Http\Controllers\ProductController@registerNewBook')->withoutMiddleware(['api', 'auth']);
Route::post('registermovie', 'App\Http\Controllers\ProductController@registerNewMovie')->withoutMiddleware(['api', 'auth']);
Route::get('getallproducts', 'App\Http\Controllers\ProductController@getAllProducts')->withoutMiddleware(['api','auth']);


// Rutas para deshabilitar/habilitar y editar Usuarios
Route::post('disableuser', 'App\Http\Controllers\UsersController@disableUser')->withoutMiddleware(['api', 'auth']);
Route::post('enableuser', 'App\Http\Controllers\UsersController@enableUser')->withoutMiddleware(['api', 'auth']);
Route::post('update-contact-info', 'App\\Http\\Controllers\\UsersController@updateContactInfo')->withoutMiddleware(['api', 'auth']);

// Change Password
Route::post('change-password', 'App\Http\Controllers\UsersController@changePassword')->withoutMiddleware(['api', 'auth']);

// User routes
Route::get('user', 'App\Http\Controllers\AuthController@UserLogged')->middleware('auth:api');
Route::post('user/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');
Route::get('user/getAll', 'App\Http\Controllers\UsersController@getAll')->middleware('auth:api');
Route::delete('user/delete/{id}', 'App\Http\Controllers\UsersController@delete')->middleware('auth:api');

//Manage Clients
Route::get('user/manageCustomers', 'App\Http\Controllers\UsersController@manageCustomers')->withoutMiddleware('auth:api');
Route::get('user/manageWorkers', 'App\Http\Controllers\UsersController@manageWorkers')->withoutMiddleware('auth:api');

// Rutas para Carrito de Compras
Route::post('carrocompras/addproduct', 'App\Http\Controllers\CarroComprasController@addproduct')->withoutMiddleware('auth:api');
Route::post('carrocompras/removeproduct', 'App\Http\Controllers\CarroComprasController@removeProduct')->withoutMiddleware('auth:api');
Route::post('carrocompras/clear', 'App\Http\Controllers\CarroComprasController@clearCart')->withoutMiddleware('auth:api');
Route::get('carrocompras/getall', 'App\Http\Controllers\CarroComprasController@getAllCartItems')->withoutMiddleware('auth:api');
Route::get('carrocompras/calculate-total', 'App\Http\Controllers\CarroComprasController@calculateTotalRental')->withoutMiddleware('auth:api');

//Rutas para Solicitudes De Arriendo 
Route::post('solicitudesarriendo/transfer-from-cart', 'App\Http\Controllers\SolicitudesArriendoController@transferFromCart')->withoutMiddleware('auth:api');
Route::post('solicitudesarriendo/approve-request', 'App\Http\Controllers\SolicitudesArriendoController@approveRequest')->withoutMiddleware('auth:api');
Route::post('solicitudesarriendo/reject-request', 'App\Http\Controllers\SolicitudesArriendoController@rejectRequest')->withoutMiddleware('auth:api');
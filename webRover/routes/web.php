<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Rutas del API
    //Rutas UserMethods
    Route::post('/user/register', 'App\Http\Controllers\RegisterUserController@register');
    Route::post('/user/login', 'App\Http\Controllers\LoginUserController@login');
    Route::resource('/user', 'App\Http\Controllers\UserController');
    
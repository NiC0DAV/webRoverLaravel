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
    //Rutas User
    Route::post('/user/register', 'App\Http\Controllers\UserController@userRegister');
    Route::post('/user/login', 'App\Http\Controllers\UserController@userLogin');
    Route::put('/user/update', 'App\Http\Controllers\UserController@userUpdate');
    //Rutas GreenHouse
    Route::resource('/greenhouse', 'App\Http\Controllers\GreenhouseController');
    Route::get('/greenhouse/view/{1}', 'App\Http\Controllers\GreenhouseController@getGreenHousesByUser');//Metodo debe almacenar solo datos en variable
    // ->middleware(App\Http\Middleware\ApiAuthMiddleware::class); para validacion mas seguro con uploads
    
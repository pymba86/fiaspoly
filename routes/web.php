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

Route::get('/', 'PolyController@index');
Route::post('/', 'PolyController@store');

Route::get('/{id:[0-9]+}', 'PolyController@show');
Route::put('/{id:[0-9]+}', 'PolyController@update');
Route::delete('/{id:[0-9]+}', 'PolyController@destroy');

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

Route::get('{id}', 'PolyController@show')
    ->where('id', '[0-9]+');

Route::put('{id}', 'PolyController@update')
    ->where('id', '[0-9]+');

Route::delete('{id}', 'PolyController@destroy')
    ->where('id', '[0-9]+');

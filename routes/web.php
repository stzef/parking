<?php

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
    return view('layouts/welcome');
});

Auth::routes();

Route::group(['prefix'=>'movimientos'], function(){
	Route::group(['prefix' => 'entrada'], function(){
		Route::get('/', 'MovimientosController@index')->name('entrada');
		Route::post('/create','MovimientosController@createEntrada')->name('create');
	});
});
Route::group(['prefix' => 'api'], function(){
	Route::group(['prefix' => 'tarifas'], function(){
		Route::get('/', "APIController@tarifas");
	});
	Route::group(['prefix' => 'tipovehiculo'], function(){
		Route::get('/', "APIController@tipovehiculo");
	});
});
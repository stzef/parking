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
		Route::get('/ticket/{cmovi}', "MovimientosController@ticketEntrada");
		Route::post('/create','MovimientosController@createEntrada')->name('create');
	});
	Route::group(['prefix' => 'salida'], function(){
		Route::get('/', 'MovimientosController@salida')->name('salida');
		Route::get('/ticket/{cmovi}', "MovimientosController@ticketSalida");
		Route::post('/create','MovimientosController@createSalida')->name('create');
	});
	Route::group(['prefix' => 'list'], function(){
		Route::get('/', 'MovimientosController@lista')->name('lista');
		Route::get('/report/{date1}/{date2}', 'MovimientosController@reportFechas');
	});
	Route::group(['prefix' => 'tickets'],function(){
		Route::get('/', 'MovimientosController@tickets')->name('tickets');
	});
	Route::group(['prefix' => 'config'], function(){
		Route::get('/', 'MovimientosController@config')->name('config');
	});
	Route::post('/time','MovimientosController@setTime')->name('time');
	Route::post('/params','MovimientosController@saveParams')->name('params');

});
Route::group(['prefix' => 'api'], function(){
	Route::group(['prefix' => 'tarifas'], function(){
		Route::get('/', "APIController@tarifas");
	});
	Route::group(['prefix' => 'tipovehiculo'], function(){
		Route::get('/', "APIController@tipovehiculo");
	});
	Route::group(['prefix' => 'sedes'], function(){
		Route::get('/', "APIController@sedes");
	});
	Route::group(['prefix' => 'params'], function(){
		Route::get('/', "APIController@params");
	});
	Route::group(['prefix' => 'movimientos'], function(){
		Route::post('/', "APIController@movimientos");
	});
	Route::group(['prefix' => 'roles'], function(){
		Route::get('/', "APIController@roles");
	});
});

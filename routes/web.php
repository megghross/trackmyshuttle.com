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
//
//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', 'HomeController@index')->name("dashboard")->middleware("auth");


Route::get('/live-tracking', 'LiveTrackingController@index')->name('live-tracking');


Route::get('/routes', 'RoutesController@index')->name('routes');


Route::get('/shuttles', 'ShuttlesController@index')->name('shuttles');


Route::get('/support', 'ShuttlesController@index')->name('support');







//    ->middleware("role:admin")

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


Route::get('/', 'HomeController@index')->name("home");

Route::get('/dashboard', 'HomeController@dashboard')->name("dashboard");


Route::get('/live-tracking', 'LiveTrackingController@index')->name('live-tracking');


Route::get('/off-route', 'OffRouteController@index')->name('off-route');


Route::get('/routes', 'RoutesController@index')->name('routes');


Route::get('/shuttles', 'ShuttlesController@index')->name('shuttles');


Route::get('/support', 'ShuttlesController@index')->name('support');


Route::get('/simulation', 'SimulationController@index')->name('simulation');


Route::get('/test', function(){
    return view('test');
});



Route::prefix("iframe")->name("iframe.")->group(function() {
    Route::get('/routes', function(){
        return view('routes.fragments.routes');
    })->name("routes");


    Route::get('/livetracking', function(){
        return view('livetracking.fragments.livetracking');
    })->name("livetracking");

	Route::get('/livetrackingv2', function(){
		return view('livetracking.fragments.livetrackingv2');
	})->name("livetrackingv2")->middleware('auth');

    Route::get('/offroute', function(){
		return view('offroute.fragments.offroute');
	})->name("offroute");

    Route::get('/simulation', function(){
		return view('simulation.fragments.simulation');
	})->name("simulation");

});



//    ->middleware("role:admin")

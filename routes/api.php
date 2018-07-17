<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::name("util.")->group(function(){
    Route::get('/shuttles', "UtilityController@GetShuttles")->name("getShuttles");

    Route::post('/updatelocation', "UtilityController@UpdateLocation")->name("update");


    Route::post('/updatelocation', "UtilityController@UpdateLocation2")->name("updateLocation");


});



Route::prefix("routes")->name("routes.")->group(function(){
    Route::post('', "RoutesController@ProcessData")->name("main");
//    Route::post('/save', "RoutesController@SaveData")->name("save");
//    Route::post('/edit', "RoutesController@SaveData")->name("edit");
//    Route::post('/delete', "RoutesController@SaveData")->name("delete");
});



Route::prefix("dashboard")->name("dashboard.")->group(function(){
    Route::get('/load', "HomeController@LoadData")->name("load");
    Route::get('/loaddrivers', "HomeController@LoadDrivers")->name("loaddrivers");
    Route::get('/getdashboarddata', "HomeController@GetDashboardData")->name("getdashboarddata");
});



Route::prefix("livetracking")->name("livetracking.")->group(function(){

	Route::post('/fetchRoute', "LiveTrackingController@FetchRoute")->name("fetchRoute");
});



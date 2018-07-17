<?php

namespace App\Http\Controllers;

use App\Helpers\MapHelpers;
use App\Models\Marker;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class LiveTrackingController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
//        $this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('livetracking.index');
	}


	/***
	 * @param Request $request
	 */
	public function FetchRoute(Request $request)
	{
		try {
			$routeId = $request->input('routeId');
			$route = Route::where('id', $routeId)->first();
			$markers = Marker::where('route', $route->name)->get()->toArray();
			$mapHelper = new MapHelpers($markers);
			$response = $mapHelper->getResponse();
			return response()->json(['status' => true, 'message' => 'success', 'data' => json_decode($response), 'markers'=>$markers]);

		} catch (\Exception $e) {
			return response()->json(['status' => false, 'message' => 'Some problem while fetching information', 'exception' => $e->getMessage()]);

		}


	}
}

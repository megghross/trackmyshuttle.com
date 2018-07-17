<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\Response;
use App\Events\LocationUpdate;
use App\Helpers\ETAHelpers;
use App\Helpers\MapHelpers;
use App\Helpers\NearbyThingsHelper;
use App\Models\Device;
use App\Models\LocationLog;
use App\Models\Marker;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use stdClass;

class UtilityController extends Controller
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
    public function GetShuttles()
    {
        try {

            $routesData = Route::get();

            $dataset = array();
            $i = 0;
            foreach ($routesData as $route) {
                $route->wayPoints = Marker::where('route', $route->name)->get();
                $route->devices = Device::where("routeId", $route->id)->get();
                $dataset[$i] = $route;
                $i++;
            }

            return response()->json($dataset);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Some problem inserting data to database', 'exception' => $e->getMessage()]);
        }
    }


    public function UpdateLocation(Request $request){
    	$serialNumber = $request->input("serialNumber");
        $lat = $request->input("lat");
        $long = $request->input("long");
        $locationdatetime = $request->input("locationdatetime");
        $device_text = $request->input("device_text");
        $device = Device::where("serialNumber", $serialNumber)->first();
        $route = Route::where('id', $device->routeId)->first();
        $markers = Marker::where('route', $route->name)->get()->toArray();
//        $coordinates =  explode("\n", $route->coordinates);
        $mapHelper = new MapHelpers($markers);
        $nearestMarker = $mapHelper->NearestPointOfRoute($lat, $long);
        $NearbyPlaces = new NearbyThingsHelper($lat, $long, 1500);
        $nearbyStation = $NearbyPlaces->GetNearestStation();
        $offRoute = $mapHelper->DetectOffRoute($nearestMarker);

        $resp = new StdClass();

        if($offRoute){
            $resp->msg = "Away from assigned route.";
        }
        else{
            $resp->msg = "Shuttle moving on its assigned Route";
        }

        $resp->probablity = $mapHelper->FuellingProbablity($nearestMarker, $nearbyStation);

        $resp->result = 1;
        $resp->routeDistance = $nearestMarker->distance;
        $resp->gas_stationDistance = $nearbyStation->distance;

        return response()->json($resp);
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function UpdateLocation2(Request $request){
		$serialNumber = $request->input("serialNumber");
		$lat = $request->input("lat");
		$long = $request->input("long");
		$locationdatetime = Carbon::now();
		$device = Device::where("serialNumber", $serialNumber)->first();
		//Get the assigned route.
		$route = Route::where('id', $device->routeId)->first();
		//Get the markers of assigned route.
		$markers = Marker::where('route', $route->name)->get()->toArray();

		$mapHelper = new MapHelpers($markers);
		$locationUpdate = new LocationUpdate($serialNumber, $lat, $long);
		$locationUpdate->orignalRoute = $route->id;
		$log = new LocationLog();
		$log->serialNumber = $serialNumber;
		$log->lat = $lat;
		$log->lng = $long;
		$data = [
			"assignedRouteId"=>$device->routeId
		];



		$previousLocation = LocationLog::where('serialNumber', $serialNumber)->orderBy('created_at', 'DESC')->first();
		if($previousLocation==null){
			$previousLocation = $log;
		}
		$res = $mapHelper->findCurrentAndPreviousPoints($log, $previousLocation);


		$currentNearestPosition = $res->CurrentPoint;

		if($currentNearestPosition->distance > 200){

			$log->routeId = -1;
			$data['offroute'] = true;
			$data['positions'] = $res;
			$data['nearestMarker'] = $currentNearestPosition;

//			$data['lowestRoute'] = $lowestRoute;


//			$lowestRoute = self::ChooseRouteId($lat, $long);
//
//			if($lowestRoute->distance < 200){
//				$log->routeId = $lowestRoute->routeId;
//			}
//			else{

//			}
		}
		else{
			$log->routeId = $device->routeId;
		}



		if($log->routeId!=-1){
			if($log->routeId!=$route->id){
				$route = Route::where('id', $log->routeId)->first();
				$markers = Marker::where('route', $route->name)->get()->toArray();
				$mapHelper = new MapHelpers($markers);
			}
		}

		$locationUpdate->currentRoute = $log->routeId;

		$data['nearestStop'] = $mapHelper->NearestStop($lat, $long);
		$data['currentPosition'] = $res->CurrentPoint;
		$data['previousPosition'] = $res->PreviousPoint;

		$locationUpdate->nearestStop = $data['nearestStop'];

		$locationUpdate->currentPoint = $res->PreviousPoint;
		$locationUpdate->predictedPoint = $res->CurrentPoint;

		$log->data = json_encode($data);
		$log->save();

		$res = broadcast($locationUpdate)->toOthers();

		$resp = new StdClass();
		$resp->msg = 'Everything looks okay :-/';
		$resp->result = 1;
		return response()->json($resp);

	}

	public function ChooseRouteId($lat, $long){
		$routes = Route::get();
		$lowestMark = new StdClass();
		$lowestMark->distance = 0;
		foreach($routes as $route){
			$markers = Marker::where('route', $route->name)->get()->toArray();
			$mapHelper = new MapHelpers($markers);
			$nearestMarker = $mapHelper->NearestPointOfRoute($lat, $long);
			if($lowestMark->distance==0 || $lowestMark->distance > $nearestMarker->distance){
				$lowestMark->distance = $nearestMarker->distance;
				$lowestMark->stepIndex = $nearestMarker->stepIndex;
				$lowestMark->legIndex = $nearestMarker->legIndex;
				$lowestMark->pointIndex = $nearestMarker->pointIndex;
				$lowestMark->routeId = $route->id;
			}

		}
		return $lowestMark;


	}
}

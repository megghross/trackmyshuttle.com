<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\Response;
use App\Helpers\ETAHelpers;
use App\Helpers\MapHelpers;
use App\Helpers\NearbyThingsHelper;
use App\Models\Device;
use App\Models\Marker;
use App\Models\Route;
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
        $MapHelper = new MapHelpers($markers);
        $nearestMarker = $MapHelper->NearestPointOfRoute($lat, $long);
        $NearbyPlaces = new NearbyThingsHelper($lat, $long, 1500);
        $nearbyStation = $NearbyPlaces->GetNearestStation();
        $offRoute = $MapHelper->DetectOffRoute($nearestMarker);

        $resp = new StdClass();

        if($offRoute){
            $resp->msg = "Away from assigned route.";
        }
        else{
            $resp->msg = "Shuttle moving on its assigned Route";
        }

        $resp->probablity = $MapHelper->FuellingProbablity($nearestMarker, $nearbyStation);

        $resp->result = 1;
        $resp->routeDistance = $nearestMarker->distance;
        $resp->gas_stationDistance = $nearbyStation->distance;

        return response()->json($resp);



    }
}

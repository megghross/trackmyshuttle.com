<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\Response;
use App\Helpers\ETAHelpers;
use App\Models\Device;
use App\Models\Marker;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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
            $this->db->fetch_data('*', 'live_routes_data');
            $routesData = $this->db->getResultSet();
            $dataset = array();

            $i = 0;
            foreach ($routesData as $route) {
                $this->db->fetch_data('address, type, name, latitude, longitude', 'live_route_detail', 'where routeId = ' . $route->id);
                $route->wayPoints = $this->db->getResultSet();

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
        $coordinates =  explode("\n", $route->coordinates);
        $markers = Marker::where('route', $route->name)->get()->toArray();
        $EtaHelper = new ETAHelpers();
        $point = array($lat,  $long);
//        List($min, $index) = $EtaHelper->getMinimumDistance($point, $coordinates);
        List($min, $index) = $EtaHelper->getMinimumDistanceFromMarkers($point, $markers);
//        dd($min, $index);

        if($index>0){
            $markers = array_slice($markers, 1, (Count($markers) - 1));
        }

        $markers[0]["lat"] = $point[0];
        $markers[0]["lng"] = $point[1];
        $eta = $EtaHelper->getGoogleETA($markers);

        dd(($eta/60)." mins");

    }
}

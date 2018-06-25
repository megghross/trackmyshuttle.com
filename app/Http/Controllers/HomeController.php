<?php

namespace App\Http\Controllers;

use App\Helpers\ETAHelpers;
use App\Models\Device;
use App\Models\Driver;
use App\Models\Marker;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use stdClass;

class HomeController extends Controller
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
        return view('home');
    }


    public function Dashboard()
	{
    	return view('dashboard');
	}

    public function LoadData(){
        try {
            $routesData = Route::get(["id", "name", "color", "length"]);
            $dataset = array();
            $i = 0;
            foreach ($routesData as $route) {
                $route->wayPoints = Marker::where('route', str_replace("'", "\'", $route->name))
                    ->get(['address', 'type', 'lat', 'lng'])->toArray();
                $dataset[$i] = $route;
                $i++;
            }
            return response()->json($dataset);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Some problem inserting data to database', 'exception' => $e->getMessage()]);

        }
    }

    public function LoadDrivers(){
        return response()->json(Driver::get());
    }
    public function  GetDashboardData(){
        $devices = Device::get();
        $dataSet = array();
        $i = 1;
        foreach ($devices as $device) {
            $item = new StdClass();
            $item->name = "device" . $i;
            $item->serial_number = $device->serialNumber;
            $item->id = $device->id;
            $item->shuttleName = $device->shuttleName;
            $item->routeId = $device->routeId;
            $item->driverId = $device->driverId;
            $dataSet[] = $item;
            $i++;
        }
        $response = new StdClass();
        $response->status = 1;
        $response->devices = $dataSet;
        return response()->json($response);
    }
}

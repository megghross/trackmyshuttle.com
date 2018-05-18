<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoutesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('routes.index');
    }


    //for loading data to maps
    public function LoadData()
    {
        $routes = Route::get()->toArray();
        $markers = Marker::orderBy('route')->get()->toArray();
        return response()->json(array(
            'route_data' => $routes,
            'marker_data' => $markers
        ));
    }


    public function SaveData(Request $request)
    {
        $last_modified_datetime=date("Y-m-d H:i:s");
        if ($request->input("action") == 'add') {
            $data = json_decode($request->input("data"), true);
            $route_data = $data["route_data"];
            $id = $route_data["id"];
            $name = str_replace("'", "\'", $route_data["name"]);
            $color = str_replace("'", "\'", $route_data["color"]);
            $length = str_replace("'", "\'", $route_data["length"]);
            $coordinates = $route_data["coordinates"];
            $num_rows = Route::where("name", $name)->get()->count();
            if ($num_rows > 0) {
                return response()->json(array("result" => "fail"));
            }
            $route = new Route();
            $route->name = $name;
            $route->color = $color;
            $route->length= $length;
            $route->coordinates= $coordinates;
            $route->last_modified_datetime= $last_modified_datetime;
            $route->save();
            //add route markers
            $marker_data=$data["marker_data"];

            for($i=0;$i<count($marker_data);$i++){
                $marker = new Marker();
                if(isset($marker_data[$i]["name"])){
                    $marker->name =str_replace("'","\'", $marker_data[$i]["name"]);
                }
                else{
                    $marker->name ="undefined";
                }
                $marker->address =str_replace("'","\'", $marker_data[$i]["address"]);
                $marker->type=$marker_data[$i]["type"];
                $marker->lat=$marker_data[$i]["lat"];
                $marker->lng=$marker_data[$i]["lng"];
                $marker->last_modified_datetime=$last_modified_datetime;
                $marker->route = $name;
                $marker->save();
            }
        }
    }
}

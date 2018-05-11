<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\Response;
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
}

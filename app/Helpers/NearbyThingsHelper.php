<?php
/**
 * Created by PhpStorm.
 * User: Arsla
 * Date: 5/7/2018
 * Time: 2:35 AM
 */

namespace App\Helpers;


use App\ShuttleLocation;
use Illuminate\Support\Carbon;
use OutOfBoundsException;
use stdClass;

class NearbyThingsHelper
{

    private $NearbyStations;
    private $Latitude;
    private $Longitude;
    private $Radius;
    private $CurrentLocation;
    private $APIKEY = "AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM";

    public function __construct($lat, $lng, $radius)
    {
        $this->Latitude = $lat;
        $this->Longitude = $lng;
        $this->CurrentLocation = $lat.", ".$lng;
        $this->Radius = $radius;
        $this->Process();
    }

    private function Process()
    {
        $str = GetJson("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".urlencode($this->CurrentLocation)."&radius=".urlencode($this->Radius)."&types=gas_station&key=".$this->APIKEY);
        $this->NearbyStations = json_decode($str)->results;
        return true;
    }

    public function test(){
        dd($this->NearbyStations);
    }




    public function GetNearestStation(){
        $minDistance = 9999;
        $nearestStation = new StdClass();
        foreach ($this->NearbyStations as $gas_station){
            $curDistance = $this->distance($this->Latitude, $this->Longitude, $gas_station->geometry->location->lat, $gas_station->geometry->location->lng, 'meters');

            if($curDistance<$minDistance){
                $minDistance = $curDistance;
                $nearestStation->name = $gas_station->name;
                $nearestStation->location = $gas_station->geometry->location;
                $nearestStation->distance = $minDistance;
                $nearestStation->nearby = $gas_station->vicinity;
            }

        }
        return $nearestStation;
    }


    public function GetNearbyPlaces(){
        return $this->NearbyStations;
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit = "K")
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        }
        else if ($unit== "METERS"){
            return ($miles * 1.609344 * 1000);
        }
        else {
            return $miles;
        }
    }

}
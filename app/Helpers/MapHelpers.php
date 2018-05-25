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

class MapHelpers
{

    private $Router;
    private $Markers;
    private $APIKEY = "AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM";

    public function __construct($Markers = null)
    {
        $this->Markers = $Markers;
        $this->InitRoute();
    }

    private function InitRoute()
    {
        $markers = $this->Markers;
        $waypoints = implode("|", array_map(function ($stop) {
            return $stop["address"];
        }, array_slice($markers, 1, (Count($markers) - 2))));
        $str = GetJson("https://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($markers[0]["address"]) . "&destination=" . urlencode($markers[Count($markers) - 1]["address"]) . "&waypoints=" . urlencode($waypoints) . "&key=".$this->APIKEY);
        $this->Route = json_decode($str)->routes[0];
        return true;
    }


    public function ShowRouteInstruction()
    {
        if ($this->Route == null) {
            return false;
        }
        $count = 1;
        foreach ($this->Route->legs as $leg) {
            foreach ($leg->steps as $step) {
                echo $count . "-" . $step->html_instructions . "----- Distance: " . $step->distance->text . ' Time : ' . $step->duration->text;
                echo "</br>";
//                $count += Count(PolylineEncoder::decodeValue($step->polyline->points));
                $count++;
            }
        }

//        echo $count;
//        echo "=====";
//        echo Count(PolylineEncoder::decodeValue($data->overview_polyline->points));
    }


    public function GetFullPolyLineArray()
    {
        if ($this->Route == null) {
            return false;
        }
        $points = array();
        foreach ($this->Route->legs as $leg) {
            foreach ($leg->steps as $step) {
                $points = array_merge($points, PolylineEncoder::decodeValue($step->polyline->points));
            }
        }
        return $points;
    }


    public function GetLatLngArray($leg)
    {
        $points = array();

        foreach ($leg->steps as $step) {
            $points = array_merge($points, PolylineEncoder::decodeValue($step->polyline->points));
        }
        return $points;

    }


    public function NearestPointOfRoute($lat, $lng)
    {
        if ($this->Route == null) {
            return false;
        }
        $minDistance = 99999999;
        $nearestMark = new StdClass();
        $legIndex = 0;
        foreach ($this->Route->legs as $leg) {
            $stepIndex = 0;
            foreach ($leg->steps as $step) {
                $latlngArray = PolylineEncoder::decodeValue($step->polyline->points);
                $pointIndex = 0;
                foreach ($latlngArray as $latlng) {

                    $curDistance = $this->distance($latlng['x'], $latlng['y'], $lat, $lng, 'meters');
                    if ($curDistance < $minDistance) {
                        $minDistance = $curDistance;
                        $nearestMark->distance = $minDistance;
                        $nearestMark->unit = 'meters';
                        $nearestMark->stepIndex = $stepIndex;
                        $nearestMark->legIndex = $legIndex;
                        $nearestMark->pointIndex = $pointIndex;
                    }

                    $pointIndex++;

                }
                $stepIndex++;
            }
            $legIndex++;
        }
        return $nearestMark;
    }
    public function DetectOffRoute($nearestPoint){
        if($nearestPoint->distance>50){
            return true;
        }
        else{
            return false;
        }
    }


    public function FuellingProbablity($nearestPoint, $nearestStation){
        $offRoute = $this->DetectOffRoute($nearestPoint);
        $isStationNearer = false;
        if($nearestPoint->distance > $nearestStation->distance){
            $isStationNearer = true;
        }
        $probablity = ( ($offRoute?1:0) * .25 ) + ( ($isStationNearer?1:0) * .50 );

        return round($probablity*100);
    }

    public function getLeg($index = -1)
    {
        if ($index == -1) {
            return $this->Route->legs;
        } else {
            return $this->Route->legs[$index];
        }
    }

    public function getPointLocation($leg = 0, $step = 0, $point = 0){
        try {
            $latlngArray = PolylineEncoder::decodeValue($this->Route->legs[$leg]->steps[$step]->polyline->points);
            $retPoint = $latlngArray[$point];
        } catch (OutOfBoundsException $ex) {
            return false;
        }
        return $retPoint;
    }

    public function getStep($leg = 0, $step = 0)
    {
        try {
            $retStep = $this->Route->legs[$leg]->steps[$step];
        } catch (OutOfBoundsException $ex) {
            return false;
        }
        return $retStep;
    }

    public function GetFullETA()
    {
        $time = 0;
        foreach ($this->Route->legs as $leg) {
            $time += $leg->duration->value;
        }

        return $time;
    }

    public function getMinimumDistance($point, $array)
    {
        $min = 99999;
        $index = -1;
        $i = 0;
        foreach ($array as $pt) {
            $pt = explode(",", $pt);
            $distance = $this->distance($point[0], $point[1], $pt[0], $pt[1]);
            if ($distance < $min) {
                $min = $distance;
                $index = $i;
            }
            $i++;
        }
        return array($min, $index);
    }

    public function getMinimumDistanceFromMarkers($point, $array)
    {
        $min = 99999;
        $index = -1;
        $i = 0;
        foreach ($array as $pt) {
            $distance = $this->distance($point[0], $point[1], $pt["lat"], $pt["lng"]);
            if ($distance < $min) {
                $min = $distance;
                $index = $i;
            }
            $i++;
        }
        return array($min, $index);
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

    public $valueOf = array(
        "messageIdentifier" => 0,
        "srNo" => 1,
        "unixTime" => 2,
        "eventReason" => 3,
        "duration" => 4,
        "latitude" => 5,
        "longitude" => 6,
        "headingDegree" => 7,
        "ignition" => 8,
        "rpm" => 9,
        "speedMph" => 10,
        "fuelLevel" => 11,
        "noOfSatelites" => 12,
        "locationAge" => 13,
        "cellSignal" => 14,
        "engineSpeed" => 15,
    );


    public function DecodeJson()
    {
        $json = file_get_contents(resource_path("data.json"));
        $json = json_decode($json);
        foreach ($json as $testItem) {
            $values = explode(",", $testItem->device_text);
            if ($values[$this->valueOf["eventReason"]] != "IGN_OFF") {
                $shuttleLocation = new ShuttleLocation();
                $shuttleLocation::create([
                    "serialNumber" => $values[$this->valueOf["srNo"]],
                    "lat" => $values[$this->valueOf["latitude"]],
                    "long" => $values[$this->valueOf["longitude"]],
                    "locationdatetime" => new Carbon($testItem->createdAt),
                    "device_text" => $testItem->device_text
                ]);
            }
        }
        echo "Yes";

    }
}
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

class ETAHelpers
{
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
        foreach ($json as $testItem){
            $values = explode(",", $testItem->device_text);
            if($values[$this->valueOf["eventReason"]]!="IGN_OFF"){
                $shuttleLocation = new ShuttleLocation();
                $shuttleLocation::create([
                    "serialNumber"=> $values[$this->valueOf["srNo"]],
                    "lat"=> $values[$this->valueOf["latitude"]],
                    "long" => $values[$this->valueOf["longitude"]],
                    "locationdatetime"=> new Carbon($testItem->createdAt),
                    "device_text"=>$testItem->device_text
                ]);
            }
        }
        echo "Yes";

    }


    public function getGoogleETA($markers){

        $waypoints = implode("|", array_map(function ($stop) {
            return $stop["lat"] . ',' . $stop["lng"];
        }, array_slice($markers, 1, (Count($markers) - 2))));

        $str = GetJson("https://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($markers[0]["lat"] . "," . $markers[0]["lng"]) . "&destination=" . urlencode($markers[(Count($markers) - 1)]["lat"]. "," . $markers[(Count($markers) - 1)]["lng"]) . "&waypoints=" . urlencode($waypoints) . "&key=AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM");
        $data = json_decode($str)->routes[0];
        $time=0;
        foreach ($data->legs as $leg){
            $time += $leg->duration->value;
        }

        return $time;
    }




}
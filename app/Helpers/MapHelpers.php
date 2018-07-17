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

/**
 * @property  Route
 */
class MapHelpers
{

	private $Route;
	private $Response;
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
		$this->Response = GetJson("https://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($markers[0]["address"]) . "&destination=" . urlencode($markers[Count($markers) - 1]["address"]) . "&waypoints=" . urlencode($waypoints) . "&key=" . $this->APIKEY);
		$this->Route = json_decode($this->Response )->routes[0];
		return true;
	}


	public function getResponse(){
		return $this->Response;
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

	/***
	 * @param $lat
	 * @param $lng
	 * @return bool|stdClass
	 */
	public function NearestStop($lat, $lng)
	{
		if ($this->Route == null) {
			return false;
		}
		$minDistance = 99999999;
		$nearestMark = new StdClass();
		foreach ($this->Markers as $marker) {
			$curDistance = $this->distance($marker['lat'], $marker['lng'], $lat, $lng, 'meters');
			if ($curDistance < $minDistance) {
				$minDistance = $curDistance;
				$nearestMark->distance = $minDistance;
				$nearestMark->unit = 'meters';
				$nearestMark->stop = $marker;
			}
		}
		return $nearestMark;
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

	public function findCurrentAndPreviousPoints($currentLog, $previousLog)
	{

		$currentNearestPoints = $this->NearestPointsOfRoute($currentLog->lat, $currentLog->lng);

		$previousNearestPoints = $this->NearestPointsOfRoute($previousLog->lat, $previousLog->lng);
		$result = $this->ChoosePreviousAndCurrent($currentNearestPoints, $previousNearestPoints);

		return $result;



	}

	private function ChoosePreviousAndCurrent($currentNearestPoints, $previousNearestPoints){


		$minPoint = new StdClass();
		$minPoint->diff = 999999999999999;
		$pointsArray = array();

		foreach ($currentNearestPoints as $CurrentPoint){
			foreach ($previousNearestPoints as $PreviousPoint){
				$indexAPoints = (int)(str_pad($CurrentPoint->legIndex, 2, "0", STR_PAD_LEFT)
					.''.
					str_pad($CurrentPoint->stepIndex, 2, "0", STR_PAD_LEFT)
					.''.
					str_pad($CurrentPoint->pointIndex, 2, "0", STR_PAD_LEFT));

				$indexBPoints = (int)(str_pad($PreviousPoint->legIndex, 2, "0", STR_PAD_LEFT)
					.''.
					str_pad($PreviousPoint->stepIndex, 2, "0", STR_PAD_LEFT)
					.''.
					str_pad($PreviousPoint->pointIndex, 2, "0", STR_PAD_LEFT));

				$diff = abs($indexAPoints - $indexBPoints);
				if($diff<$minPoint->diff){
					$minPoint->diff = $diff;
					$minPoint->CurrentPoint = $CurrentPoint;
					$minPoint->PreviousPoint = $PreviousPoint;
				}
			}
		}

		return $minPoint;
	}
	public function NearestPointsOfRoute($lat, $lng)
	{
		if ($this->Route == null) {
			return false;
		}
		$minDistance = 99999999;
		$nearestMarks = array();
		$legIndex = 0;
		foreach ($this->Route->legs as $leg) {
			$stepIndex = 0;
			foreach ($leg->steps as $step) {
				$latlngArray = PolylineEncoder::decodeValue($step->polyline->points);
				$pointIndex = 0;
				foreach ($latlngArray as $latlng) {
					$nearestMark = new StdClass();

					$curDistance = $this->distance($latlng['x'], $latlng['y'], $lat, $lng, 'meters');
					$nearestMark->distance = $curDistance;
					$nearestMark->unit = 'meters';
					$nearestMark->stepIndex = $stepIndex;
					$nearestMark->legIndex = $legIndex;
					$nearestMark->pointIndex = $pointIndex;


					$pointIndex++;
					$nearestMarks[] = $nearestMark;

				}
				$stepIndex++;
			}
			$legIndex++;
		}


		return $this->sortNearestMarksArray($nearestMarks);
	}

	public function sortNearestMarksArray($nearestMarks)
	{

		for ($i = 0; $i < Count($nearestMarks); $i++) {
			for ($j = 0; $j < Count($nearestMarks)-(1+$i); $j++) {
				if( $nearestMarks[$j]->distance > $nearestMarks[$j + 1]->distance )
				{
					list( $nearestMarks[$j + 1], $nearestMarks[$j] ) =
						array( $nearestMarks[$j], $nearestMarks[$j + 1] );
				}
			}
		}
		return array_slice($nearestMarks, 0, 3); ;
	}

	public function DetectOffRoute($nearestPoint)
	{
		if ($nearestPoint->distance > 50) {
			return true;
		} else {
			return false;
		}
	}


	public function FuellingProbablity($nearestPoint, $nearestStation)
	{
		$offRoute = $this->DetectOffRoute($nearestPoint);
		$isStationNearer = false;
		if ($nearestPoint->distance > $nearestStation->distance) {
			$isStationNearer = true;
		}
		$probablity = (($offRoute ? 1 : 0) * .25) + (($isStationNearer ? 1 : 0) * .50);

		return round($probablity * 100);
	}

	public function getLeg($index = -1)
	{
		if ($index == -1) {
			return $this->Route->legs;
		} else {
			return $this->Route->legs[$index];
		}
	}

	public function getPointLocation($leg = 0, $step = 0, $point = 0)
	{
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
		} else if ($unit == "METERS") {
			return ($miles * 1.609344 * 1000);
		} else {
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
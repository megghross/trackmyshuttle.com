<?php 
    //require("config.php");    
    include 'constants.php';
	include 'class.core.php';	
	include 'class.dbconnector.php';
    include "class.device.php";
    
	/* https://support.ladesk.com/061754-How-to-make-REST-calls-in-PHP */	
	$serial = "";
	$command = "";
	$org = "";	
	if (isset($_POST["serial"])) {
	    $serial = $_POST["serial"];
	}
	if (isset($_POST["command"])) {
	    $command = $_POST["command"];
	}
	if (isset($_POST["token"])) {
	    $token = $_POST["token"];
	}
	$msg = "";
	$status = "";
	//$authorization = "authorizationToken: " . $token;
	$service_url = 'https://yjwzuj5kvg.execute-api.us-east-1.amazonaws.com/prod/lockcommand?serial='.$serial.'&command='.$command.'&token='.$token;
   	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	
	echo $curl_response;	
	//echo json_encode(array('status' => $status,'message'=>$msg ));
	
?>
<?php if(session_id()=='') { session_start(); }
    //require("config.php");    
    include 'constants.php';
	include 'class.core.php';	
	include 'class.dbconnector.php';
    include "class.device.php";
	require_once("class.log.php");
    
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
	/*if (isset($_POST["token"])) {
	    $token = $_POST["token"];
	}*/
	$msg = "";
	$status = "";	
	//$service_url = 'https://yjwzuj5kvg.execute-api.us-east-1.amazonaws.com/prod/lockcommand?serial='.$serial.'&command='.$command.'&token='.$token.'&source=web';
	$service_url = 'https://iseeply27k.execute-api.us-east-1.amazonaws.com/prod/webcommand?serial='.$serial.'&command='.$command;
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
	
	$objLog = new log();
	$objLog->AddLog("Serial - " . $serial . " -- Command - " . $command ,"chart.php","","");
	
	echo $curl_response;	
	//echo json_encode(array('status' => $status,'message'=>$msg ));
	
?>
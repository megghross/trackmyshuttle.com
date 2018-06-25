<?php 
    require("config.php");
    
	/* https://support.ladesk.com/061754-How-to-make-REST-calls-in-PHP */
	
	$service_url = 'https://5nid6cte3k.execute-api.us-east-1.amazonaws.com/prod/device-access?serial=ZAA00001&command=LOCK';
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	$decoded = json_decode($curl_response);
	
	echo json_encode(array('status' => 'success','message'=> $decoded));
		
?>
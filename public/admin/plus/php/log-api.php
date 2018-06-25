<?php

function EventLog($page,$action,$orignal_value,$new_value)
{
		//$myfile = fopen("log.txt", "a") or die("Unable to open file!");
		$email = $_SESSION['user_email'];
		$ipaddress = getUserIP();
		
		$data = sprintf("%s,%s,%s,%s,%s" . PHP_EOL, $ipaddress,date("F j Y g:i a"), $email, $page, $action);
		
		//file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/log.txt', $data, FILE_APPEND);		
		file_put_contents('log.txt', $data, FILE_APPEND);			
		
		
}

// Function to get the user IP address
function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


?>
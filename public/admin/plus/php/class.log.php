<?php
	
class log extends core
{
    var $connector;
    var $error;
    
    function __construct() {
       // $this->connector = parent::db();
		$core = new core();
        $this->connector = $core->db();
    }
    
    function AddLog($action,$source,$oldval,$newval){
    	$ipaddress = $this->getUserIP();
		$userid = 0;
		if (isset($_SESSION["userid"]))
			$userid = $_SESSION["userid"];
		
		$sql = "INSERT INTO `log` (`userid`, `email`, `action`, `ipaddress`, `oldval`, `newval`,source) VALUES (".$userid.",'".$_SESSION["user_email"]."','$action', '$ipaddress', '$oldval', '$newval','$source')";
		//echo $sql;//die();
		$result = $this->connector->query($sql);		
		
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
	    
}

?>

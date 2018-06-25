<?php 

	require("config.php");
	include("dynamodb-api.php");	
	$retval = data_from_dynamodb("");
	echo $retval;		
?>

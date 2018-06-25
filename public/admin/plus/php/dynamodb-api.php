<?php 

	require_once("config.php");
	
	function data_from_dynamodb($org_key)
	{
		global $conn, $dynamodb;		
		
		$sql = "Select * FROM device"; 
		if ($org_key!="")
			$sql .= " WHERE org_key='$org_key'";
		$devices = "";
		$res = mysqli_query($conn,$sql); 
		while($row = mysqli_fetch_array($res))
		{
			$serial = $row['device_serial'];
			$time  =  $row['device_time'];
			$first_alert = $row['device_firstalert_time'];
			
			$resDy = $dynamodb->query([
				'TableName' => 'device_data',
				'KeyConditionExpression' => 'serial = :v_id',
				'ExpressionAttributeValues' =>  [
					':v_id' => ['S' => $serial]
				],
				'Limit' => 1,
				'ScanIndexForward' => false
			]);
			
			if($resDy['Count'] == 1){
				$devices = $devices . $serial . " -- " ;
				foreach ($resDy['Items'] as $rowDy) {
					
					$payload = $rowDy['payload']['M'];
					
					$timestamp = $rowDy['timestamp']['N'];				
					$swver  = $payload['swver']['S'];
					$lckrev = $payload['swrev']['S'];
					$lockid = $payload['lockid']['S'];
					$type   = $payload['type']['S'];
					$cond	= $payload['cond']['S'];
					$t 		= $payload['t']['N'];
					$lstat  = $payload['lstat']['N'];
					$dstat  = $payload['dstat']['N'];
					$cstat  = $payload['cstat']['N'];
					$xtmp  	= $payload['xtmp']['N'];
					$xprb 	= $payload['xprb']['N'];
					$xpow  	= $payload['xpow']['N'];
					$pwcnt  = $payload['pwcnt']['N'];
					$rbcnt  = $payload['rbcnt']['N'];
					$rbcau  = $payload['rbcau']['N'];
					$period = $payload['period']['N'];
					$auto   = $payload['auto']['S'];
				}
						
				if($timestamp!=$time){
					
					$istat = 0;
					if($xtmp=="1"||$xpow=="1"||$xprb=="1"||$cstat=="1"||$cstat=="2"){  
						if($first_alert==0){
							$alert_time = $timestamp;
						}
						else{
							$alert_time = $first_alert;
						}
					}
					else{
						$alert_time = 0;
					}
					
					$sqlupdate = "UPDATE device SET device_sw_version   = '$swver',
													device_lock_rev		= '$lckrev',
													device_lockid 		= '$lockid',
													device_type 		= '$type',
													device_condition 	= '$cond',
													device_temperature  = '$t',
													device_lock_state	= '$lstat',
													device_door_state 	= '$dstat',
													device_comm_state 	= '$cstat',
													device_locked_temp 	= '$xtmp',
													device_locked_power = '$xpow',
													device_locked_probe = '$xprb',
													device_inactivity   = '$istat',
													device_pwrup_count  = '$pwcnt',
													device_reboot_count = '$rbcnt',
													device_reboot_cause = '$rbcau',
													device_data_period  = '$period',
													device_auto_time    = '$auto',
													device_firstalert_time   = '$alert_time',
													device_time = '$timestamp' WHERE device_serial = '$serial'";
					//echo $sqlupdate;	
					mysqli_query($conn,$sqlupdate);
				}
				else{
					$istat = 0;
					$dtime = round($timestamp/1000);
					$ago = time() - $dtime;
					if($ago>600){ 
						$istat=1; 				
						$sqlupdate = "UPDATE device SET device_inactivity = '$istat' WHERE device_serial = '$serial'";	
						mysqli_query($conn,$sqlupdate);
					}
				}
			}
		}
		
		return $devices;

	}
	

?>

<?php 
	include_once("config.php");
    include_once("sendsms.php");
    
	$sql = "Select * FROM location"; $res = mysqli_query($conn,$sql); 
	while($row = mysqli_fetch_array($res))
	{
		$loc_key  = $row['loc_key'];
		$loc_name = $row['loc_name'];
		$loc_timezone = $row["loc_timezone"];
		$user1 = $row['user_id'];
		$user2 = $row['user_id2'];
		$user3 = $row['user_id3'];
		
		$message = array();
		$did = array();
		$email_flag = 0;
		
		$sql2 = "select * FROM device WHERE loc_key='$loc_key' AND device_mute='0' AND (device_locked_temp='1' OR device_locked_power='1' OR device_locked_probe='1' OR device_comm_state='1' OR device_inactivity='1')"; 
		$res2 = mysqli_query($conn,$sql2); 
		while($row2 = mysqli_fetch_array($res2))
		{
				
				$m1 = "";$m2="";$m3="";$m4="";$m5="";
				$mflag = 0;
				if($row2['device_locked_temp']==1){
					$m1 = "LOCKED FOR OVERTEMP, "; 
					$mflag=1;
				}
				if($row2['device_locked_power']==1){
					$m2 = "LOCKED FOR POWER LOSS, ";
					$mflag=1;
				}
				if($row2['device_locked_probe']==1){
					$m3 = "LOCKED FOR PROBE FAILURE, ";
					$mflag=1;
				}
				if($row2['device_comm_state']==1){
					$m4 = "LOCK COMM. FAILURE, ";
					$mflag=1;
				}
				
				if($mflag==1){
					// Use $device_alert_time to generate time info. Convert time to Location TimeZone.
					/*
					if($loc_timezone == "Eastern Time")      {$z="America/New_York";}
					else if($loc_timezone == "Central Time") {$z="America/Chicago";}
					else if($loc_timezone == "Mountain Time"){$z="America/Denver";}
					else if($loc_timezone == "Pacific Time") {$z="America/Los_Angeles";}
					else if($loc_timezone == "Alaska Time")  {$z="America/Anchorage";}
					else if($loc_timezone == "Hawaii Time")  {$z="Pacific/Honolulu";}
					else if($loc_timezone == "Amsterdam")  {$z="Europe/Amsterdam";}
					else if($loc_timezone == "Belgrade")  {$z="Europe/Belgrade";}
					else if($loc_timezone == "Berlin")  {$z="Europe/Berlin";}
					else if($loc_timezone == "Bratislava")  {$z="Europe/Bratislava";}
					else if($loc_timezone == "Brussels")  {$z="Europe/Brussels";}
					else if($loc_timezone == "Budapest")  {$z="Europe/Budapest";}
					else if($loc_timezone == "Copenhagen")  {$z="Europe/Copenhagen";}
					else if($loc_timezone == "Ljubljana")  {$z="Europe/Ljubljana";}
					else if($loc_timezone == "Madrid")  {$z="Europe/Madrid";}
					else if($loc_timezone == "Paris")  {$z="Europe/Paris";}
					else if($loc_timezone == "Prague")  {$z="Europe/Prague";}
					else if($loc_timezone == "Rome")  {$z="Europe/Rome";}
					else if($loc_timezone == "Sarajevo")  {$z="Europe/Sarajevo";}
					else if($loc_timezone == "Skopje")  {$z="Europe/Skopje";}
					else if($loc_timezone == "Stockholm")  {$z="Europe/Stockholm";}
					else if($loc_timezone == "Vienna")  {$z="Europe/Vienna";}
					else if($loc_timezone == "Warsaw")  {$z="Europe/Warsaw";}
					else if($loc_timezone == "Zagreb")  {$z="Europe/Zagreb";}
					else if($loc_timezone == "Athens")  {$z="Europe/Athens";}
					else if($loc_timezone == "Bucharest")  {$z="Europe/Bucharest";}	*/
					
					
					$epoch = round($row2['device_firstalert_time']/1000);
					$dt = new DateTime("now", new DateTimeZone($loc_timezone));
					$dt = $dt->setTimestamp($epoch);
					$device_time = $dt->format('g:i A l, d F Y');				
					$message[] = "Module ".$row2['device_name']." reported ".$m1.$m2.$m3.$m4.$m5. "  since " . $device_time . " (" . $loc_timezone . ")"; //6:15AM Wednesday, 13 March 2017 "; 						
				}
				
				$currenttime = new DateTime('NOW', new DateTimeZone('UTC'));
				
				if($row2['device_inactivity']==1){		
					$devicetimestamp = round($row2['device_time']/1000);  //device_timestamp IN MILISECONDS
					if(isset($devicetimestamp)) {
						$device_date = new DateTime();
						$device_date->setTimestamp($devicetimestamp);
						$interval=date_diff($currenttime,$device_date);					
						$dev_years  = $interval->y; 	
						$dev_months = $interval->m; 	
						$dev_days   = $interval->d; 	
						$dev_hours  = $interval->h; 	
						$dev_totalhours = ($dev_years*8760) + ($dev_months*730) + ($dev_days*24) + ($dev_hours);
						
						$message[] = "Module ".$row2['device_name']." has LOST CLOUD CONNECTIVITY for over " . $dev_totalhours . " Hours" ;  // use $device_timestamp to generate this info						
					}
					
				}
				
				$did[] = $row2["device_id"];
				
				$lastnotify_time = $row2['device_lastnotify_time'];  //LASTNOTIFYTIME IS IN SECONDS
				$hours = 0;
				$totalhours = 0;
				if ($lastnotify_time>0) {
					$start_date = new DateTime();
					$start_date->setTimestamp($lastnotify_time);
					$interval=date_diff($currenttime,$start_date);					
					$years  = $interval->y; 	
					$months = $interval->m; 	
					$days   = $interval->d; 	
					$hours  = $interval->h; 	
					$totalhours = ($years*8760) + ($months*730) + ($days*24) + ($hours);
				}				
				//echo $totalhours;
				//if ( $totalhours > 6 || $lastnotify_time==0 ) {
				if ( $totalhours > 1 || $lastnotify_time==0 ) {
					$email_flag = 1;
				}	
		}
		
		if ($email_flag==1)
		{
			/*
			$qry = "SELECT user1.user_email email1,user2.user_email email2,user3.user_email email3 FROM location left join `user` user1 on location.user_id=user1.user_id";
			$qry .= " left join `user` user2 on location.user_id2=user2.user_id";
			$qry .= " left join `user` user3 on location.user_id3=user3.user_id where loc_key='$loc_key'";			
			$emailArray = array();
			$response = mysqli_query($conn,$qry); 
			if(mysqli_num_rows($response) > 0) {
				$rslt = mysqli_fetch_array($response);
				if ($rslt["email1"]!="")
					$emailArray[] = $rslt["email1"];
				if ($rslt["email2"]!="")
					$emailArray[] = $rslt["email2"];
				if ($rslt["email3"]!="")
					$emailArray[] = $rslt["email3"];
			}
			
			$to = $emailArray; //Recipient Email Address
			$subject = "FreshTraq Alert for Location - " . $loc_name; //Email Subject  */
			$body = "";	
			
			foreach ($message as $key => $value) {
			    $body = $body . $value . " - ";
			}			
			//SendSMS($body);			
			//$number = "+919558165133";			
			$number = "+13125155538";
			sms($number,$body);			
			echo "<br/>Send SMS Notification : " . $loc_name;
		} else {
			
			echo "<br/>No message";
		}
	}
	
?>
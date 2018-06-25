<?php 
    require("config.php");
    require_once("iot-api.php");
    require_once("log-api.php");
    
include_once 'constants.php';
	include_once 'class.core.php';	
	include_once 'class.dbconnector.php';		
    require_once("class.log.php");
    use Aws\DynamoDb\Marshaler;
	$marshaler = new Marshaler();
    if(isset($_SESSION['userkey'])){
		$user_key = mysqli_real_escape_string($conn,$_SESSION['userkey']);
	}
	
	/* ======================== GET ======================== */
	if(isset($_GET['mode'])) {
		
        if($_GET['mode']=='account_getprofile'){
			$sql = "SELECT * FROM user WHERE user_key ='$user_key'";
            $res = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($res);   
            if($count==1){
                $row = mysqli_fetch_array($res);
				$uemail  = $row['user_email'];
				$ufirst  = trim($row['user_first_name']);
				$ulast   = trim($row['user_last_name']);
				$uphone  = trim($row['user_phone']);
				$urole   = $row['user_role'];
				$okey    = $row['org_key'];

				$sql = "SELECT * FROM organization WHERE org_key = '$okey'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);
				if($count==1){
					$row = mysqli_fetch_array($res);
					$oname 	 = $row['org_name'];
					$oddr    = $row['org_street'];
					$ocity	 = $row['org_city'];
					$ostate	 = $row['org_state'];
					$ozip	 = $row['org_zip'];
					$octry   = $row['org_country'];
					$ophone	 = $row['org_phone'];
				}
				
				echo json_encode(array('status' => 'success',
									   'user_email'      => $uemail,
									   'user_first_name' => $ufirst,
									   'user_last_name'  => $ulast,
									   'user_phone'      => $uphone,
									   'org_name'		 => $oname,
									   'org_street'	 => $oddr,
									   'org_city'	     => $ocity,
									   'org_state'	     => $ostate,
									   'org_zip'	     => $ozip,
									   'org_country'	 => $octry,
									   'org_phone'   	 => $ophone,
									   'role_name'	     => $urole
								));
            }
		}
		elseif($_GET['mode']=='org_getprofile'){
			$org_id = mysqli_real_escape_string($conn,$_GET['org_id']); 
			$sql = "SELECT * FROM organization WHERE org_id = '$org_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);
			
			if($count==1){
				$row = mysqli_fetch_array($res);
				$oname 	 = $row['org_name'];
				$oddr    = $row['org_street'];
				$ocity	 = $row['org_city'];
				$ostate	 = $row['org_state'];
				$ozip	 = $row['org_zip'];
				$octry   = $row['org_country'];
				$ophone	 = $row['org_phone'];
				$org_key = $row['org_key'];
				$token_key = $row['token_key'];
				$user_code = $row['user_code'];
			}

			echo json_encode(array('status' => 'success',
								   'org_name'		 => $oname,
								   'org_street'	     => $oddr,
								   'org_city'	     => $ocity,
								   'org_state'	     => $ostate,
								   'org_zip'	     => $ozip,
								   'org_country'	 => $octry,
								   'org_phone'   	 => $ophone,
								   'org_key'		 => $org_key,
  								   'token_key'		 => $token_key,
  								   'user_code'		 => $user_code,
								   'lat'             => $row['org_lat'],
								   'lng'             => $row['org_lng'],
								   'zone'            => $row['org_timezone']
							)); 
		}
		elseif($_GET['mode']=='org_user_login'){
			$org_id = mysqli_real_escape_string($conn,$_GET['org_id']); 

			if ($_SESSION['user_role']=='Platform-Admin')
			{
				$sql = "SELECT * FROM organization WHERE org_id = '$org_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);
			
				if($count==1){
					$row = mysqli_fetch_array($res);
					$org_key = $row['org_key'];	
					$oname 	 = $row['org_name'];				
				}

				$_SESSION['orgkey'] = $org_key;
				$_SESSION['orgname'] = $oname;

				$retval = array('status'=>'success','redirect'=>'dashboard.php');
			} else {
				$retval = array('status'=>'success','redirect'=>'');
			}

			echo json_encode($retval);	
			
		}
		else if($_GET['mode']=='user_rolelist'){
			$role = mysqli_real_escape_string($conn,$_GET['user_role']); 	
						
			if($role=='Platform-Admin'){
				$answer[0] = array("id"=>'Admin',"text"=>'Admin');
				$answer[1] = array("id"=>'Technician',"text"=>'Technician');
			}
			else if($role=='Admin'){
				$answer[0] = array("id"=>'Technician',"text"=>'Technician');
			}
			echo json_encode($answer);	
		}
		else if($_GET['mode']=='device_config'){
			$device_id = mysqli_real_escape_string($conn,$_GET['device_id']); 
			$sql = "SELECT * FROM devices WHERE id ='$device_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
            if($count==1){
                $row = mysqli_fetch_array($res);
				$serial = $row['serialNumber'];
				$device_name = $serial;
				$org_key = $row['org_key'];
				echo json_encode(array('status'=>'success','name'=>$device_name,'serial_id'=>$serial,'org_key'=>$org_key));
			}
		}
		else if($_GET['mode']=='device_orglist'){
			$sql = "SELECT * FROM organization";
            $res = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($res)){
				$answer[] = array("id"=>$row['org_key'],"text"=>$row['org_name']);
            }
            echo json_encode($answer);	
		}
		else if($_GET['mode']=='device_shuttlelist'){
			$org_key = mysqli_real_escape_string($conn,$_GET['org_key']);
			$serial = mysqli_real_escape_string($conn,$_GET['serial']);  
			$sql2 = "SELECT * FROM device where device_serial = '$serial'";
			
            $res2 = mysqli_query($conn,$sql2);
            $row = mysqli_fetch_array($res2);

            $route_id = $row['route_id'];

            $route_name = '';

            $sql3 = "SELECT * FROM route WHERE id='$route_id'";			
            
			$res3 = mysqli_query($conn,$sql3); 
			$count = mysqli_num_rows($res3); 
			if($count==1){
                $row = mysqli_fetch_array($res3);
				$route_name = $row['name'];
			
			}


			$sql = "SELECT * FROM shuttle where org_key = '$org_key' AND (routeName = '$route_name' or routeName IS NULL)";

            $res = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($res);
            if($count > 0){
            while($row = mysqli_fetch_array($res)){
            	$sql1 = "SELECT * FROM device where device_serial = '$serial'";

             $res1 = mysqli_query($conn,$sql1);
             $rows = mysqli_fetch_array($res1);
                   $shuttleid = $rows['shuttleid'];
                   
               /*$answer[] = array("shuttleid"=>$shuttleid);*/
				$answer[] = array("id"=>$row['id'],"text"=>$row['shuttleName'],"count"=>1,"shuttleid"=>$shuttleid);
            }
           }else{
           	 $answer[] = array("count"=>0);
           }
              
             

            echo json_encode($answer);	
		}
		else if($_GET['mode']=='device_routelist'){
			$org_key = mysqli_real_escape_string($conn,$_GET['org_key']);
			$serial = mysqli_real_escape_string($conn,$_GET['serial']); 
			$sql = "SELECT * FROM route where org_key = '$org_key'";
            $res = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($res);
            if($count > 0){
            while($row = mysqli_fetch_array($res)){
            	$sql1 = "SELECT * FROM device where device_serial = '$serial'";

             $res1 = mysqli_query($conn,$sql1);
             $rows = mysqli_fetch_array($res1);
                   $route_id = $rows['route_id'];
				$answer[] = array("id"=>$row['id'],"text"=>$row['name'],"count"=>1,"route_id"=>$route_id);
            }
            }else{
           	 $answer[] = array("count"=>0);
           }
            echo json_encode($answer);	
		}
		else if($_GET['mode']=='device_loclist_2'){
			$org_key = mysqli_real_escape_string($conn,$_GET['org_key']); 
			$sql = "SELECT * FROM location WHERE org_key = '$org_key'";
            $res = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($res)){
				$answer[] = array("id"=>$row['loc_key'],"text"=>$row['loc_name']);
            }
            echo json_encode($answer);	
		}
    }
	
	/* ======================== POST ======================== */
	
    else if(isset($_POST['mode'])){
		if($_POST['mode']=='user_roleupdate'){
			
			$user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
			$role = mysqli_real_escape_string($conn,$_POST['role']);
		
			$sql = "UPDATE user SET user_role = '$role' WHERE user_id = '$user_id'";
			if(mysqli_query($conn,$sql)){
				echo json_encode(array('status' => 'success','message'=> 'Role Updated !'));
			}
			else{
				echo json_encode(array('status' => 'error','message'=> 'Unexpected Error !'));
			}
		}
		else if($_POST['mode']=='reset'){		
		
			$email = mysqli_real_escape_string($conn,stripslashes($_POST['email']));
			$code = mysqli_real_escape_string($conn,stripslashes($_POST['code']));
			$pass = mysqli_real_escape_string($conn,stripslashes($_POST['pass_new']));
					
			$sql = "SELECT * FROM user WHERE user_email='$email' AND user_crypto='$code'";
			
			$res = mysqli_query($conn,$sql);
			$count = mysqli_num_rows($res);   
			if($count==1){
				$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
				$password = hash('sha256', $pass . $salt); 
				for($round = 0; $round < 65536; $round++){$password = hash('sha256', $password . $salt); }
				$sql2 = "UPDATE user SET user_password='$password',user_salt='$salt',user_crypto='' WHERE user_email = '$email'";
				if(mysqli_query($conn,$sql2)){
					$status = "success";
					$msg = "Password Reset ! Please login with your new password ...";
				}
				else{
					$status = "error";
					$msg = "Error ! Could not Reset Password ! ";
				}
			}
			else{
				$status = "error";
				$msg = "Error ! Could not Reset Account Password ! ";
			}     
			echo json_encode(array('status' => $status,'message'=> $msg));		
		}
		elseif($_POST['mode']=='org_new'){
			
			$name = mysqli_real_escape_string($conn,$_POST['name']);
			$org_key = md5(uniqid(rand(),TRUE));
			$token_key = md5(uniqid(rand(),TRUE));
			
			$sql = "INSERT INTO organization (org_key,org_name,org_country,token_key,org_createdById,org_lastModifiedById) VALUES('$org_key','$name','United States','$token_key',".$_SESSION['userid'].",".$_SESSION['userid'].")";
			//echo $sql;
			mysqli_query($conn,$sql);
				
			$loc_key = md5(uniqid(rand(),TRUE));
			$loc_name = $name." (HQ)";
			$sql = "INSERT INTO location (loc_key,loc_name,org_key)VALUES('$loc_key','$loc_name','$org_key')";
			//echo $sql;
			mysqli_query($conn,$sql);
			
			$objLog = new log();
			$objLog->AddLog("Organization Created - " . $name,"org.php","","");
			
			echo json_encode(array('status' => 'success','message'=> 'Organization Created !'));
		}
		elseif($_POST['mode']=='tracker_add'){
			       $tracker_key = $_POST['tracker_key2'];
					$tracker_channel   = $_POST['tracker_channel2'];
					$tracker_serial =	$_POST['tracker_serial2'];
					$tracker_mac = $_POST['tracker_mac2'];
					$org_id = $_POST['org_id'];
					$Createddate = date("Y-m-d h:i:s");
			$ip_address = get_client_ip();
			
			$sql = "INSERT INTO tracker (tracker_key,tracker_createdById,tracker_channel,tracker_serial,tracker_mac,tracker_orgkey,tracker_created,tracker_lastModified,tracker_lastModifiedById,tracker_ip,tracker_certificate,tracker_hardware,tracker_software,tracker_state,tracker_sim,tracker_connection,tracker_lastComm) VALUES('$tracker_key',".$_SESSION['userid'].",'$tracker_channel','$tracker_serial','$tracker_mac','$org_id','$Createddate','$Createddate',".$_SESSION['userid'].",'".$ip_address."',1,'','','','','','$Createddate')";
			
			mysqli_query($conn,$sql);
				
			
			
			echo json_encode(array('status' => 'success','message'=> 'Tracker Created !'));
		}
		elseif($_POST['mode']=='org_updateprofile'){
			
			$org_id = mysqli_real_escape_string($conn,stripslashes($_POST['org_id']));
            $org_name = mysqli_real_escape_string($conn,stripslashes($_POST['org_name']));
            $org_address = mysqli_real_escape_string($conn,stripslashes($_POST['org_address']));
			$org_city = mysqli_real_escape_string($conn,stripslashes($_POST['org_city']));
			$org_state = mysqli_real_escape_string($conn,stripslashes($_POST['org_state']));
			$org_zip = mysqli_real_escape_string($conn,stripslashes($_POST['org_zip']));
			$org_country = mysqli_real_escape_string($conn,stripslashes($_POST['org_country']));
			$org_phone = mysqli_real_escape_string($conn,stripslashes($_POST['org_phone']));
			$lat = mysqli_real_escape_string($conn,$_POST['lat']);
			$lng = mysqli_real_escape_string($conn,$_POST['lng']);
			$zone = mysqli_real_escape_string($conn,$_POST['zone']);
			
			$sql = "UPDATE organization SET org_name    = '$org_name',
										    org_street = '$org_address',
                                   	        org_city    = '$org_city',
											org_state   = '$org_state',
											org_zip     = '$org_zip',
											org_country = '$org_country',
										    org_phone   = '$org_phone',
											org_lat     = '$lat',
											org_lng    = '$lng',
											org_timezone = '$zone' WHERE org_id = '$org_id'";	
			
			if(mysqli_query($conn,$sql)){
				
				$objLog = new log();
				$objLog->AddLog("Organization Updated - " . $org_name,"org.php","","");
				
				echo json_encode(array('status' => 'success','message'=> 'Organization Updated !'));
			}
			else{
				echo json_encode(array('status' => 'error','message'=> 'Error during Organization Update !'));
			}
        }
        elseif($_POST['mode']=='code_generate'){
			
            $orgkey = mysqli_real_escape_string($conn,stripslashes($_POST['orgkey']));
			$user_code = mysqli_real_escape_string($conn,$_POST['user_code']);


			$sql = "SELECT user_code FROM organization WHERE user_code='$user_code' and org_key != '$orgkey'";			
			$res = mysqli_query($conn,$sql); 
			$count = mysqli_num_rows($res); 
			if($count==0){
                
				$sql = "UPDATE organization SET user_code = '$user_code' WHERE org_key = '$orgkey'";	
			
				if(mysqli_query($conn,$sql)){
					if ($user_code=="")
						$msg = "Code Removed";
					else
						$msg = "Code Updated";
					
					echo json_encode(array('status' => 'success','message'=> $msg));
				}
				else{
					echo json_encode(array('status' => 'error','message'=> 'Error during code generation !'));
				}
			}
			else {
				echo json_encode(array('status' => 'error','message'=> 'Code already exists for other organization !'));
			}
			
        }
		else if($_POST['mode']=='device_assignment'){
			
			$device_id = mysqli_real_escape_string($conn,$_POST['device_id']);
			$org_key = mysqli_real_escape_string($conn,$_POST['org_key']);
					
			
			$sql = "UPDATE devices SET org_key = '$org_key' WHERE id = '$device_id'";
			$result = mysqli_query($conn,$sql);

			/* if ( false===$result ) {
				file_put_contents('error.txt','Source: [POST] common.php/device_assign\n'.mysqli_error($conn));
			} */
			//EventLog("inventory.php","Moduel Assign : Old Org - " . $org_name . ", Old Loc - " . $loc_name . " :: New Org - " . $org_name_new . ", New Loc - " . $loc_name_new ,"","");
			//$objLog = new log();
			//$objLog->AddLog("Moduel Assign : Old Org - " . $org_name . ", Old Loc - " . $loc_name . " :: New Org - " . $org_name_new,"inventory.php",$loc_name,$loc_name_new);
		
			echo json_encode(array('status' => 'success','message'=> 'Module Assignment Updated !'));
		}
		else if($_POST['mode']=='device_new'){
			
			$serial     = mysqli_real_escape_string($conn,$_POST['new_serial']);
			$iccid     = mysqli_real_escape_string($conn,$_POST['new_iccid']);
			$org_key	= mysqli_real_escape_string($conn,$_POST['new_orgkey']);
			$sql = "SELECT * FROM devices WHERE BINARY serialNumber='$serial'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
			if($count==0){
				
				//createThing($serial,$new_thing_no,$org_key,$loc_key,$conn);
				$sql2 = "INSERT INTO devices (serialNumber,iccid,org_key)VALUES('$serial','$iccid','$org_key')" ;
				mysqli_query($conn,$sql2);
												
				echo json_encode(array('status' => 'success','message'=> 'Module Added !'));
			} else {
				echo json_encode(array('status' => 'error','message'=> 'Module already exists !'));
			}
		}
		else if($_POST['mode']=='shuttle_new'){
			
			$shuttleName = mysqli_real_escape_string($conn,$_POST['shuttleName']);
			$org_key	= mysqli_real_escape_string($conn,$_POST['new_orgkey']);
			 $last_modified_datetime   = date("Y-m-d h:i:s");
			
			$sql = "INSERT INTO shuttle (shuttleName,org_key,color,coordinates,length,last_modified_datetime)VALUES('$shuttleName','$org_key','','',0,'$last_modified_datetime')";
			
			mysqli_query($conn,$sql);
												
			echo json_encode(array('status' => 'success','message'=> 'Shuttle Added !'));
			
		}
		else if($_POST['mode']=='device_period'){
			
			$period = mysqli_real_escape_string($conn,$_POST['period']);
			$device_id = mysqli_real_escape_string($conn,$_POST['device_id']);
			
			$sql = "select device_data_period_cmd from device WHERE device_id = '$device_id'";
			$res = mysqli_query($conn,$sql); 
			$count = mysqli_num_rows($res); 
			if($count==1){
                $row = mysqli_fetch_array($res);
				$device_data_period_cmd = $row['device_data_period_cmd'];
			}
			
			$sql = "UPDATE device SET device_data_period_cmd = '$period' WHERE device_id = '$device_id'";
			$result = mysqli_query($conn,$sql);
		
			
			//EventLog("inventory.php","Update Device Old Period " . $device_data_period_cmd . " Min - New Period : " . $period . " Min","","");
			$objLog = new log();
			$objLog->AddLog("Update Device Old Period " . $device_data_period_cmd . " Min - New Period : " . $period . " Min","inventory.php",$device_data_period_cmd,$period);
			
			echo json_encode(array('status' => 'success','message'=> 'Data Period Updated !'));
		} 
		else if($_POST['mode']=='devicedata_clear'){
			$device_id = mysqli_real_escape_string($conn,$_POST['device_id']);
			$sql = "SELECT * FROM device WHERE device_id ='$device_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
			$serial = "";
            if($count==1){
                $row = mysqli_fetch_array($res);
				$serial = $row['device_serial'];				
				
				dynamodb_deleteitems($serial);
				
				/* Partial Erasing of Data from mySQL DB */
				$sql = "UPDATE device SET 	device_temperature='0',
											device_type='U',
											device_condition='U',
											device_time=0 
											WHERE device_id = '$device_id'";
				mysqli_query($conn,$sql);
				
				//$page,$action,$orignal_value,$new_value
				//EventLog("inventory.php","Module Erase : " . $serial,"","");
				$objLog = new log();
				$objLog->AddLog("Module Data Erase : " . $serial,"inventory.php","","");
				
				echo json_encode(array('status' => 'success','message' => $serial . ' data erased!'));
			} else {
				echo json_encode(array('status' => 'error','message' => $serial . ' not found!'));	
			}
		}
		else if($_POST['mode']=='devicedata_delete'){
			$device_id = mysqli_real_escape_string($conn,$_POST['device_id']);
			$sql = "SELECT * FROM devices WHERE id ='$device_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
			$serial = "";
            if($count==1){
                $row = mysqli_fetch_array($res);
				$serial = $row['serialNumber'];				
				
				/*Note - Delete DynamoDB data for Production */
				//dynamodb_deleteitems($serial);			
				$sql = "DELETE FROM devices WHERE id ='$device_id'"; mysqli_query($conn,$sql);		

				$objLog = new log();
				$objLog->AddLog("Module Delete : " . $serial,"inventory.php","","");
				
				echo json_encode(array('status' => 'success','message' => $serial . ' Tracker and data deleted!'));				
			} 
			else {
				echo json_encode(array('status' => 'error','message' => $serial . ' not found!'));	
			}
		} 
    }
	
	function dynamodb_deleteitems($serial){
		
		global $marshaler, $dynamodb; 
		
		$marshalArr = array(
			':v_id' => $serial
		);
		$jsonform = json_encode($marshalArr);
		$tableName = 'device_data';
		$eav = $marshaler->marshalJson($jsonform);
		
		do {
			$params = [
				'TableName' => $tableName,
				'ProjectionExpression' => '#tmstmp, serial',
				'KeyConditionExpression' =>
					'serial = :v_id',
				'ExpressionAttributeNames'=> [ '#tmstmp' => 'timestamp' ],
				'ExpressionAttributeValues'=> $eav
			];
			
			if(isset($response) && isset($response['LastEvaluatedKey'])) {
				$params['ExclusiveStartKey'] = $response['LastEvaluatedKey'];
			}

			$response = $dynamodb->query($params);

			$i=0;
			foreach ($response['Items'] as $row) {
				
				$timestm = $row['timestamp']['N'];
				
				$res = $dynamodb->deleteItem(array(
					'TableName' => $tableName,
					'Key' => array(
						'serial'  => array('S' => $serial),
						'timestamp' => array('N' => $timestm)
					)
				));
				
				file_put_contents("response.txt",$res,FILE_APPEND);			
				
			}
			
		} while(isset($response['LastEvaluatedKey']));
	}
	
	function directory_delete($dirPath) {
		if (! is_dir($dirPath)) {
			//throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				//self::deleteDir($file);
				$this->deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
?>
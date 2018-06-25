<?php
	session_start();
	$site_path = realpath(dirname(__FILE__));
	include 'constants.php';
	include 'class.core.php';	
	include 'class.dbconnector.php';	
	include 'sendemail.php';	
	require_once("class.log.php");	
	include 'log-api.php';		
	
	if(isset($_POST['mode'])) {
	
		$mod = $_POST['mode'];
		
		if ($mod=="user_invite") {
			
			include 'class.user.php';		
			$org_key = $_SESSION["orgkey"];			
			if (isset($_POST["user_org"]))	{
				$org_key = $_POST["user_org"];			
			}
			
			$user_role = $_POST['user_role'];
			if ($user_role=="Platform-Admin")
				$org_key = "";
			$ojbUser = new user();
			$email = strtolower($_POST["email"]);
			$retval = $ojbUser->AddUser($_POST['user_role'],$_POST['first_name'],$_POST["last_name"],$email,$_POST["phone"],$org_key);				
			
			$objLog = new log();
			$objLog->AddLog("Invite Users : " . $email,"users.php","","");
			
			header('Content-Type: application/json');	
			if ($retval=="error" ) {
				echo json_encode(array('status' => 'error','message'=> 'Error sending Invitation !'));
			}
			else if ($retval=="exists") {
				echo json_encode(array('status' => 'error','message'=> 'User Account already exists !'));					
			}
			else{					
				echo json_encode(array('status' => 'success','message'=> 'User Invited !'));
			}			
		}
		else if ($mod=="user_detail") {
			include 'class.user.php';	
			$user_email =  $_POST["user_email"];				
			$ojbUser = new user();			
			$retval = $ojbUser->GetUserDetails($user_email);
			header('Content-Type: application/json');									
			echo json_encode($retval);		
		}
		else if ($mod=="user_update") {
			include 'class.user.php';	
			$user_email = $_POST["user_email"];			
			
			$ojbUser = new user();	
			
			$first_name = mysqli_real_escape_string($ojbUser->connector->link,stripslashes($_POST['first_name']));
			$last_name = mysqli_real_escape_string($ojbUser->connector->link,stripslashes($_POST['last_name']));	
			$phone = mysqli_real_escape_string($ojbUser->connector->link,stripslashes($_POST['phone']));	
			$user_role = 	$_POST['user_role'];							
			$retval = $ojbUser->UpdateUser($first_name,$last_name,$phone,$user_email,$user_role);	
			
			//$page,$action,$orignal_value,$new_value
			//EventLog("users.php","Update Users : " . $user_email,"","");
			$objLog = new log();
			$objLog->AddLog("Update Users : " . $user_email,"users.php","","");
			
			
			header('Content-Type: application/json');									
			echo $retval;		
		}
		else if ($mod=="reset") {
			include 'class.user.php';			
			$pass_new = stripslashes($_POST['pass_new']);
			$pass_curr = stripslashes($_POST['pass_curr']);			
			$ojbUser = new user();			
			$retval = $ojbUser->ChangePassword($pass_new,$pass_curr);
			
			header('Content-Type: application/json');										
			echo json_encode($retval);		
		}
		else if ($mod=="reset_password") {
			include 'class.user.php';			
			$pass_new = stripslashes($_POST['password']);
			$email = stripslashes($_POST['email']);
			$ojbUser = new user();			
			$retval = $ojbUser->ResetPassword($pass_new,$email);
			
			header('Content-Type: application/json');										
			echo json_encode($retval);		
		}
		else if ($mod=="getDeviceList") {
			include("class.device.php");
			$objDevice = new device();
			$org_key = mysqli_real_escape_string($objDevice->connector->link,stripslashes($_POST['org_key']));
			$loc_id = mysqli_real_escape_string($objDevice->connector->link,stripslashes($_POST['loc_id']));	
			
			$resDevice = $objDevice->getDeviceList($org_key,$loc_id);
			header('Content-Type: application/json');									
			echo json_encode($resDevice);		
		}
		
	}
?>
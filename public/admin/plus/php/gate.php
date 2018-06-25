<?php 
    require("config.php");
    require("../../PHPMailer_5.2.0/class.phpmailer.php");
    include_once("sendemail.php");
    
    include_once 'constants.php';
	include_once 'class.core.php';	
	include_once 'class.dbconnector.php';		
    require_once("class.log.php");
    
    $mode = mysqli_real_escape_string($conn,stripslashes($_POST['mode']));
    $showmessage = "";

 	$status = "";
    $msg = "";
    if($mode=="login"){
        $email = strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['login_email'])));
        $login_pass = mysqli_real_escape_string($conn,stripslashes($_POST['login_pass']));
    	$role = "";
            
       // $sql = "SELECT * FROM user WHERE BINARY user_email='$email'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
        $sql = "SELECT user.*,org_name FROM user left join organization on user.org_key=organization.org_key  WHERE user_email='$email'";
        $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
        if($count==1){
            $login_ok = false; 
            $row = mysqli_fetch_array($res);
            $user_status = $row['user_status'];
			$user_id = $row['user_id'];
			$user_key = $row['user_key'];
			
			if($user_status=="Active"){
            	$check_password = hash('sha256', $login_pass . $row['user_salt']); 
				for($round = 0; $round < 65536; $round++){
                    $check_password = hash('sha256', $check_password . $row['user_salt']);
                } 
                //echo $check_password;
                if($check_password === $row['user_password']){
                    $login_ok = true;
                }
                if($login_ok){ 
                    unset($row['user_salt']); 
                    unset($row['user_password']); 
					
					$role = $row['user_role'];
					if($role=="Platform-Admin"){
						$_SESSION['orgkey'] = "";
						$_SESSION['orgname'] = "";
						$_SESSION['org_name'] = "";
					}
					else{
						$_SESSION['orgkey'] = $row['org_key'];
						$_SESSION['orgname'] = $row['org_name'];
						$_SESSION['org_name'] = $row['org_name'];
					}
					
					$_SESSION['user_role'] = $role;
					$_SESSION['userkey'] = $user_key;
					$_SESSION['userid'] = $user_id;
					$_SESSION['user_email'] = $row['user_email'];
                    
					$status = "success";
					$msg = "Loading Account ...";  
                    mysqli_query($conn,"UPDATE user SET user_visits=user_visits+1,user_status='Active',user_crypto='' WHERE user_email = '$email'");
                    
              		$objLog = new log();
					$objLog->AddLog("Login","gate.php","","");
                }
                else{
                    $status = "error";
                    $msg = "Incorrect Email or Password !";
                }
            }
			else if($user_status=="Invited"){
				$status = "error";
                $msg = "Please check Email for Invitation Link !";
			}	
			else if($user_status=="Pending"){
				$status = "error";
                $msg = "Account is Pending Activation ! Please check Email for link to Activate Account !";
			}	
		}
        else{
            $status = "error";
            $msg = "Email is Not Registered !";
        }

		echo json_encode(array('status' => $status,'message'=> $msg,'email' => $email,'role'=> $role));
		$showmessage="true";
		//die();
		
    }
    elseif($mode=="register"){
        $email = strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['register_Email'])));
        $reg_pass = mysqli_real_escape_string($conn,stripslashes($_POST['register_pwd']));
		$reg_code = mysqli_real_escape_string($conn,stripslashes($_POST['invitation_Code']));
        
        if(!filter_var($_POST['register_Email'], FILTER_VALIDATE_EMAIL)) {
            $status = "error";
            $msg = "Invalid E-Mail Address !";
        }  
        elseif(empty($_POST['register_pwd'])) 
        {
            $status = "error";
            $msg = "Please enter a valid Password !";
        } 
        else{
            $sql = "SELECT * FROM user WHERE BINARY user_email='$email'";
            $res = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($res);

            if($count==1){
                $row = mysqli_fetch_array($res);
				$status="error";
                $msg = "This Email is already registered !";
            }
            else{
                $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
                $password = hash('sha256', $reg_pass . $salt); 
                for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); }
                
				// Insert Organization
				//$org_key = md5(uniqid(rand(),TRUE));
				$sql="SELECT * FROM organization WHERE user_code = '".$reg_code."'";
			
				$qry = mysqli_query($conn,$sql);
				$count_org_key = mysqli_num_rows($qry);
               if($count_org_key == 1){

			    $res = mysqli_fetch_object($qry);
               $org_key = $res->org_key;

			    $key = md5(uniqid(rand(),TRUE));
                $crypto = md5(uniqid(rand(),TRUE));
				// During live application, use email to validate Registration before setting account status to ACTIVE
                $sql = "INSERT INTO user (user_key,user_email,user_password,user_salt,user_crypto,user_status,org_key,user_role) VALUES('$key','$email','$password','$salt','$crypto','Active','$org_key','Org-Admin')";

                $result = mysqli_query($conn,$sql);
				
				//$id=mysqli_insert_id();
				if ( false===$result ) {
					file_put_contents('error.txt','Source: [POST] gate.php/register\n'.mysqli_error($conn));
					$status = "error";
					$msg = "Unexpected Error ! Please contact support !";
				}
				else{
					$sql_sel="select * from user where user_email='".$email."'";
					$sel=mysqli_query($conn,$sql_sel);
					$sel_f=mysqli_fetch_object($sel);
				
					
					$body = file_get_contents("../../emailtemplate/account-activation.html");

					$body = str_replace("[USERKEY]", $sel_f->user_key, $body);
					$body = str_replace("[EMAIL]", $email, $body);
					
					$subject    = "Track My Shuttle - Account Invitation";

				
					SendEmail($email, $subject , $body);
					
					
					$status = "success";
					$msg = "Signup complete ! Please check your email to Activate account !";



					$sql = "SELECT user.*,org_name FROM user left join organization on user.org_key=organization.org_key  WHERE user_email='$email'";
        $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
        if($count==1){
            $login_ok = false; 
            $row = mysqli_fetch_array($res);
            $user_status = $row['user_status'];
			$user_id = $row['user_id'];
			$user_key = $row['user_key'];
			
			if($user_status=="Active"){
            	$check_password = hash('sha256', $reg_pass . $row['user_salt']); 
				for($round = 0; $round < 65536; $round++){
                    $check_password = hash('sha256', $check_password . $row['user_salt']);
                } 
                //echo $check_password;
                if($check_password === $row['user_password']){
                    $login_ok = true;
                }
                if($login_ok){ 
                    unset($row['user_salt']); 
                    unset($row['user_password']); 
					
					/*
					$sql = "SELECT * FROM user WHERE user_key='$user_key'"; $res = mysqli_query($conn,$sql); 
					$row = mysqli_fetch_array($res);	*/
					
					$_SESSION['orgkey'] = $row['org_key'];
					$_SESSION['orgname'] = $row['org_name'];
					$_SESSION['userkey'] = $user_key;
					$_SESSION['userid'] = $user_id;
					$_SESSION['user_role'] = $row['user_role'];
					$_SESSION['org_name'] = $row['org_name'];
					$_SESSION['user_email'] = $row['user_email'];
                    $role = $row['user_role'];
					$status = "success";
					$msg = "Loading Account ...";  
                    mysqli_query($conn,"UPDATE user SET user_visits=user_visits+1,user_status='Active',user_crypto='' WHERE user_email = '$email'");
                    
              		$objLog = new log();
					$objLog->AddLog("Login","gate.php","","");


                }
                
            }
				
		}
        else{
            $status = "error";
            $msg = "Email is Not Registered !";
        }

				
				}
              }else{
              	$status="error";
                 $msg = "Code didn't match";
				}	
            }
        }
    }
	elseif($mode=="invite"){
        $email = strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['inv_email'])));
        $inv_pass = mysqli_real_escape_string($conn,stripslashes($_POST['inv_pass']));
		$inv_code = mysqli_real_escape_string($conn,stripslashes($_POST['inv_code']));
        
        if(!filter_var($_POST['inv_email'], FILTER_VALIDATE_EMAIL)) {
            $status = "error";
            $msg = "Invalid E-Mail Address !";
        }  
        elseif(empty($_POST['inv_pass'])) 
        {
            $status = "error";
            $msg = "Please enter a valid Password !";
        } 
		elseif(empty($_POST['inv_code'])) 
        {
            $status = "error";
            $msg = "Please enter a valid Activation Code !";
        } 
        else{
            $sql = "SELECT * FROM user WHERE BINARY user_email='$email' AND user_status='Invited'";
            $res = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($res);
            if($count==1){
				$row = mysqli_fetch_array($res);
					if($row['user_crypto']=="$inv_code"){
						$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
						$password = hash('sha256', $inv_pass . $salt); 
						for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); }
				
						$sql = "UPDATE user SET user_password='$password',user_salt='$salt',user_status='Active',user_crypto='' WHERE user_email = '$email'";
						
						$result = mysqli_query($conn,$sql);
						if ( false===$result ) {
							file_put_contents('error.txt','Source: [POST] common.php/usernew_invite\n'.mysqli_error($conn));
							$status = "error";
							$msg = "Unexpected Error 1 ! Please contact support !";
						}
						else{
							$status = "success";
							$msg = "Invitation Activated ! Loading Login Page ...";
						}
					}
					else{
						$status="error";
						$msg = "Invalid Invitation Code !" . $inv_code . "***" . $row['user_crypto'];
					}
            }
            else{
                $status = "error";
                $msg = "Invalid E-Mail or Activation Code !";
            }
        }
    }

    elseif($mode=="resetpassword"){
	    $email=strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['email'])));
	   	$user_crypto=strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['user_crypto'])));
	   	$new_password=$_POST['new_password'];
	   	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

	    $sql = "SELECT * FROM user WHERE user_email='$email' and user_crypto ='$user_crypto'";
	    //echo $sql;
	       
	    $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
	    if($count==1){
	      	$password = hash('sha256', $new_password . $salt);
	        for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); }
	        $sql = "UPDATE user SET user_password='$password' ,user_salt = '$salt' WHERE user_email = '$email'";
			$result = mysqli_query($conn,$sql);
	       $msg = "Succesfull changed!";
	    } else{
	    	$status = "error";
	        $msg = "Something went wrong!";
	    }

	}
	elseif($mode=="recover"){
		$email=strtolower(mysqli_real_escape_string($conn,stripslashes($_POST['rec_email'])));
		if(!filter_var($_POST['rec_email'], FILTER_VALIDATE_EMAIL)) {
            $status = "error";
            $msg = "Invalid E-Mail Address !";
        }
		else{
            $sql_rec = "SELECT * FROM user WHERE BINARY user_email='$email'";
          //  echo $sql_rec;
            $res_rec = mysqli_query($conn,$sql_rec);
            $count_rec = mysqli_num_rows($res_rec);
            if($count_rec==1){
				$row_rec = mysqli_fetch_array($res_rec); 
				
				$crypto = md5(uniqid(rand(),TRUE));
				if($row_rec['user_status']=="Active"){
					$sql_up = "UPDATE user SET user_crypto='$crypto' WHERE user_email = '$email'";
					$res_up = mysqli_query($conn,$sql_up);
					if($res_up!=false)
					{
						$body = file_get_contents("../../emailtemplate/reset-password.html");

						$body = str_replace("[CODE]", $crypto, $body);
						$body = str_replace("[EMAIL]", $email, $body);
						
						$subject    = "Track My Shuttle - Reset password";
						
						SendEmail($email, $subject , $body);
					
						
						$status = "success";
						$msg = "Please check your email to Reset Password !";
					}
					else {
						$status = "error";
						$msg = "Email not registered !";	
					}
					
				}
				elseif($row_rec['user_status']=="Pending"){
					$status = "pending";
					$msg = "Account is Pending Activation !";
				}
			}
			else {
				$status = "error";
				$msg = "Email not registered !";
			}
		}
	}
	if ($showmessage=="")
    	echo json_encode(array('status' => $status,'message'=> $msg,'email' => $email));

?> 
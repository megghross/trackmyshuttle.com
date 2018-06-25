<?php
	
class user extends core
{
    var $connector;
    var $error;
    
    function __construct() {
       // $this->connector = parent::db();
		$core = new core();
        $this->connector = $core->db();
    }
    
    function AddUser($user_role,$first_name,$last_name,$email,$phone,$org_key){
		$sql = "SELECT * FROM user WHERE BINARY user_email='$email'"; 
		$res = $this->connector->query($sql); 
		$count =  $this->connector->getNumRows($res);
		if($count==0){
			$key = md5(uniqid(rand(),TRUE));
			$crypto = md5(uniqid(rand(),TRUE));
			$sql = "INSERT INTO user (user_key,user_email,user_crypto,user_salt,user_status,org_key,user_role,user_first_name,user_last_name,user_phone) VALUES 
				('$key','$email','$crypto','temp_salt','Invited','$org_key','$user_role','$first_name','$last_name','$phone')";
			$result = $this->connector->query($sql);
			if (false===$result ) {
				file_put_contents('error.txt','Source: [POST] common.php/usernew_invite\n'.$sql);
				return "error";
			}
			$to = $email; //Recipient Email Address
			$subject = "Track My Shuttle - Account Invitation"; //Email Subject
			/*$body = "
			<html>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>triteq</title>
				<meta name='viewport' content='width=device-width, initial-scale=1'>
				</head>
				<body>
				<div>
					<div style='font-family:Arial, Helvetica, sans-serif; background-color:#e4e4e4;max-width:578px; width:100%; margin:0 auto; padding-bottom:2%;'>
						<div style='max-width:443px; width:100%; margin:0 auto;'>
							<div style=' text-align:center; width:100%; margin:0 auto; padding:40px 0px 10px;'><img src='http://portal.freshtraq.com/plus/img/logo1.png' width='300'/></div>
							<div style='width:100%; margin:0 auto; text-align:center;'>
								<h1 style='color:#bf0000; text-align:center; font-size:24px; font-weight:500; letter-spacing:1px; padding-bottom:10px;'> 
								Welcome to TriTeq!
								</h1>
								<div style=' background-color:#7f7f7f; width:100%; margin:0 auto; text-align:center;'>
								<h2 style='color:#fff; font-size:18px; font-weight:600; text-align:center;padding: 10px 0px;'>
									Instructions to Activate FreshTraq Account
								</h2>
								</div>
								<div style='background-color:#fff; width:98%; margin:0 auto; text-align:center;'>	
								<a href='#' style=' text-decoration:underline; color:#3f48cc; text-align:center; font-size:26px; padding-top:26px;' ><?php echo $email;?></a>
								<p style=' color:#252525; font-size:11px; font-weight:500; text-align:center; padding-top:26px; margin-bottom:23px;'>Click button to Activate Invite.</p>
								<div style=' padding-bottom:5%;width:100%; margin:0 auto; text-align:center;'><a href='http://portal.freshtraq.com/gate.php?tab=invite&email=$email&code=$crypto' style='background-color:#19b16a; color:#fff; font-size:16px; font-weight:600; text-align:center; display:block; width:40%; text-decoration:none; padding:10px 20px; border-radius:2px; margin:0 auto;'>Activate</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</body>
			</html>";*/

			$body = file_get_contents("../../emailtemplate/invite.html");

			$body = str_replace("[USERKEY]", $crypto, $body);
			$body = str_replace("[EMAIL]", $email, $body);

			//SendEmail($email, $subject , $body);



			return "inserted";
				
		}
		else
		{
			return "exists";			
		}
		
	}
    
	function ResetPassword($pass_new,$email) {
		
		if  ($email!="" && $pass_new!="")
		{
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
			$password = hash('sha256', $pass_new . $salt); 
			for($round = 0; $round < 65536; $round++){$password = hash('sha256', $password . $salt); }
			$sql = "UPDATE user SET user_password='$password',user_salt='$salt',user_crypto='' WHERE user_email = '$email'";
			if($this->connector->query($sql)){
				$status = "success";
				$msg = "Password Reset ! Please login with your new password ...";				
			}
			else{
				$status = "error";
				$msg = "Error ! Could not Reset Password ! ";
			}
			return json_encode(array('status' => $status,'message'=> $msg));	
		}
		else {
			$status = "error";
			$msg = "Error ! Could not Reset Password ! ";
			return json_encode(array('status' => $status,'message'=> $msg));	
		}
	}
 
    function ChangePassword($pass_new,$pass_curr) {
		
			$userkey = $_SESSION['userkey'];			
			$sql = "SELECT * FROM user WHERE BINARY user_key = '$userkey'";
			$res = $this->connector->query($sql); 
			$count =  $this->connector->getNumRows($res);
			if($count>0){	
				$row = $this->connector->fetchArray($res);			
				$check_password = hash('sha256', $pass_curr . $row['user_salt']); 
				for($round = 0; $round < 65536; $round++){
	                $check_password = hash('sha256', $check_password . $row['user_salt']);
	            } 
	            $login_ok = false;
	            if($check_password === $row['user_password']){
                    $login_ok = true;
                }
                if($login_ok){ 
					$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
					$password = hash('sha256', $pass_new . $salt); 
					for($round = 0; $round < 65536; $round++){$password = hash('sha256', $password . $salt); }
					$sql = "UPDATE user SET user_password='$password',user_salt='$salt',user_crypto='' WHERE user_key = '$userkey'";
					if($this->connector->query($sql)){
						$status = "success";
						$msg = "Password Reset ! Please login with your new password ...";
						
					}
					else{
						$status = "error";
						$msg = "Error ! Could not Reset Password ! ";
					}
				}
				else {
					$status = "error";
					$msg = "Current Password not match ! ";
					
				}
			}
			return json_encode(array('status' => $status,'message'=> $msg));	
	}
    
    function GetUserRole($user_key)
    {
		
		$result = $this->connector->query("SELECT user_role FROM user WHERE user_key = '".$user_key."'");
		if($this->connector->getNumRows($result)) {
			while($row = $this->connector->fetchArray($result)) {
				return $row['user_role'];
			}
		}
		return false;
    }
     function GetUserDetails($user_email)
    {
		
		$result = $this->connector->query("SELECT * FROM user WHERE user_email = '".$user_email."'");
		if($this->connector->getNumRows($result)) {
			$row = $this->connector->fetchArray($result);
			return $row;			
		}
		return false;
    }
    function UpdateUser($first_name,$last_name,$phone,$user_email,$user_role) {
		 
        
		$sql = "UPDATE user SET user_first_name = '$first_name',
                                user_last_name = '$last_name',user_role='$user_role',
                                user_phone = '$phone' WHERE user_email = '$user_email'";	                    
		
		if($this->connector->query($sql)){
			return json_encode(array('status' => 'success','message'=> 'Profile Updated !'));
		}
		else{
			return json_encode(array('status' => 'error','message'=> 'Error during Profile Update !'));
		}
		
	}
    

}

?>

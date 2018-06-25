<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/aws/aws-autoloader.php';
	
	use  Aws\Ses\SesClient;
	
	
	define('REGION','us-east-1'); 
	
	//define('SENDER','triteq.aws@gmail.com'); 
	define('SENDER','amit@iotmatix.com'); 
		
	function SendEmail($toemail,$subject,$body) {
		/* for testing use $sendEmail */
		$sendEmail = array();
		if (is_array($toemail))
			$sendEmail = $toemail;
		else
			$sendEmail = array($toemail);		
		
		try {
			
			$client = SesClient::factory(array(
			  'version'   => 'latest',
			  'region'    => REGION,
			  'credentials' => array(
			    'key'       => 'AKIAIK4J4IRBWOAUSVFQ',
    			'secret'    => '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',
			  ),
			));

			
			$request = array();
			$request['Source'] = SENDER;
			$request['Destination']['ToAddresses'] = $sendEmail;
			$request['Message']['Subject']['Data'] = $subject;
			$request['Message']['Body']['Html']['Data'] = $body;
			
			$result = $client->sendEmail($request);
			$messageId = $result->get('MessageId');
			
			/*
			$myfile = fopen("emaillog.txt", "a") or die("Unable to open file!");
			$text = "Subject : " . $subject . " Email To : " . implode(",", $sendEmail) . " -- Send ID : " . $messageId;
			fwrite($myfile, PHP_EOL . "******". date("d/m/Y h:i:s", time()) . PHP_EOL . $text);
			fclose($myfile);	
			*/
			return true;

		} catch (Exception $e) {
			
			/*
			//$text = "Email To : " .  implode(",", $sendEmail). " Message : " . $e->getMessage()."\n";
			$text = "Subject : " . $subject . " Email To : " . implode(",", $sendEmail);
			$myfile = fopen("emaillog.txt", "a") or die("Unable to open file!");
			fwrite($myfile, PHP_EOL . "******". date("d/m/Y h:i:s", time()) . PHP_EOL . $text);
			fclose($myfile);	
			*/
			return false;
		}		
	}		
			
			
?>
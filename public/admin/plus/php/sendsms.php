<?php
	//require dirname( __FILE__ ) . '/vendor/autoload.php';	
	require_once($_SERVER['DOCUMENT_ROOT'].'/aws/aws-autoloader.php');
	use Aws\Sns\SnsClient;

	function SendSMS($message) {
		try {		
			$client = SnsClient::factory(array(
						  'version'   => 'latest',
						  'region'    => 'us-east-1',
						  'credentials' => array(
						    'key'       => 'AKIAJEBSBA5JJVIFBIJQ',
						    'secret'    => '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',
						  ),
						));
			
			//$topicARN = 'arn:aws:sns:us-east-1:732326806212:sms-reminders';
			$topicARN = 'arn:aws:sns:us-east-1:732326806212:sms-test';
			
			$payload = array(
			    'phoneNumber' => $topicARN,
			    'Message' => $message,
			    'MessageStructure' => 'string',
			);			
			
		    //$result = $client->publish( $payload );
		    $result = $client->publish( $payload );
		    
		    echo '<br/>Sent message: "' . $message . '"';
		    
		} catch ( Exception $e ) {
		    echo "<br/>Send Failed!\n" . $e->getMessage();
		}
	}
	function sms($number, $message)
	{
	    if (empty($number) || empty($message)) {
	        return false;
	    }
	    $SnsClient = SnsClient::factory(array(
						  'version'   => 'latest',
						  'region'    => 'us-east-1',
						  'credentials' => array(
						    'key'       => 'AKIAJEBSBA5JJVIFBIJQ',
						    'secret'    => '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',
						  ),
						));
	    $result = $SnsClient->publish(array(
	    	'Message' => $message, 
	    	'PhoneNumber' => $number, 
	    	'MessageAttributes' => 
	    		array('AWS.SNS.SMS.SMSType' => array('StringValue' => 'Transactional', 'DataType' => 'String'), 
	    			  'AWS.SNS.SMS.SenderID' => array('StringValue' => 'TriTeq', 'DataType' => 'String')
	    	)));
	    
	    echo '<br/>Sent message: "' . $message . '"';
	    
	    return $result['MessageId'];
	}
	
	//echo sms("+919558165133","Hello vijay");


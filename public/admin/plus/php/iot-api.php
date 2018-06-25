<?php
use Aws\Iot\IotClient;
//require_once('../../config.php');
require_once('constants.php');

function deleteThing($device_name,$certificateid)
{
	$awsIoTClient  = new IotClient([
	    'version' => 'latest',
	    'region'  => 'us-east-1',
	    'http' => [ 'verify' => false ],
	    'credentials' => [
	        'key'    => KEY,
	        'secret' => SECRETE,
	    ]    
	]);
	
	$policyName = "Triteq_Proto_Policy";
	
	$resultThing = null;
	try {				
		//CERTIFICATE DEACTIVATE
		$result = $awsIoTClient->updateCertificate([
		    'certificateId' => $certificateid, // REQUIRED
		    'newStatus' => 'INACTIVE', // REQUIRED
		]);
		
		$certArn = 'arn:aws:iot:us-east-1:732326806212:cert/' . $certificateid;
		
		//DETACH PRINCIPAL POLICY
		$result = $awsIoTClient->detachPrincipalPolicy([
		    'policyName' => $policyName, // REQUIRED
		    'principal' => $certArn, // REQUIRED
		]);
		
		//DETACH THING PRINCIPAL
		$result = $awsIoTClient->detachThingPrincipal([
		    'principal' => $certArn, // REQUIRED
		    'thingName' => $device_name, // REQUIRED
		]);
		
		//DELETE CERTIFICATE
		$result = $awsIoTClient->deleteCertificate([
		    'certificateId' => $certificateid, // REQUIRED
		]);	
		//DELETE THING
		$result = $awsIoTClient->deleteThing([
		    'thingName' => $device_name, // REQUIRED
		]);	
	}
	catch (ResourceAlreadyExistsException $e) {
		echo 'Message: Thing already exists in your account with different tags';			
	}
	catch(Exception $e) {		
		echo 'Message: ' .$e->getMessage();
	}
	
}


function createThing($serial,$thing_no,$org_key,$loc_key,$conn)
{
	$awsIoTClient  = new IotClient([
	    'version' => 'latest',
	    'region'  => 'us-east-1',
	    'http' => [ 'verify' => false ],
	    'credentials' => [
	        'key'    => KEY,
	        'secret' => SECRETE,
	    ]    
	]);
	
	$policyName = "Triteq_Proto_Policy";
	$thingTypeName = "Triteq_Proto";
	
	$startNo = substr($serial, -5);
	$serialNo = substr($serial,0,-5);
	
	if (is_numeric($startNo)) {
		$startNo = $startNo;
	
	$TotalNo = $startNo + $thing_no - 1;
	
	for($i=$startNo;$i<=$TotalNo;$i++){
		
		$thingName = $serialNo . str_pad($i,5,'0',STR_PAD_LEFT);	
		$resultThing = null;
		try {
			$resultThing = $awsIoTClient->createThing([
				'thingName' => $thingName,
				'thingTypeName' => $thingTypeName,		    
			]);	

			if 	($resultThing['thingArn']!=null) {
				$result = $awsIoTClient->createKeysAndCertificate([
				'setAsActive' => TRUE,
			]);
			$certArn = $result['certificateArn'];
			$certId = $result['certificateId'];
			$certPem = $result['certificatePem'];
			$privateKey = $result['keyPair']['PrivateKey'];
			$publicKey = $result['keyPair']['PublicKey'];

			$awsIoTClient->attachPrincipalPolicy([
				'policyName' => $policyName,
				'principal' => $certArn
			]);
			$awsIoTClient->attachThingPrincipal([
				'principal' => $certArn,
				'thingName' => $thingName
			]);	
			$path = '../../iot/' . $thingName;
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}

			$certname = substr($certId, 0, 10) ;	
							
			$myfile = fopen($path . "/" . $certname . "-certificate.pem.crt", "a") or die("Unable to open file!");
			fwrite($myfile, $certPem);	
			fclose($myfile);
			
			$myfile = fopen($path . "/client.cer", "a") or die("Unable to open file!");
			fwrite($myfile, $certPem);	
			fclose($myfile);
			
			$myfile = fopen($path . "/" . $certname . "-privatekey.pem", "a") or die("Unable to open file!");
			fwrite($myfile, $privateKey);									
			fclose($myfile);
			
			$myfile = fopen($path . "/privkey.cer", "a") or die("Unable to open file!");
			fwrite($myfile, $privateKey);	
			fclose($myfile);
			
			
			$sql = "INSERT INTO device (device_serial,device_name,org_key,loc_key,certificateid)VALUES('$thingName','$thingName','$org_key','$loc_key','$certId')" ;
			mysqli_query($conn,$sql);
			
			//EventLog("location.php","Module Add : " . $thingName,"","");
			$objLog = new log();
			$objLog->AddLog("Module Add : " . $thingName,"inventory.php","","");
			
			}
		}
		catch (ResourceAlreadyExistsException $e) {
			echo 'Message: Thing already exists in your account with different tags';			
		}
		catch(Exception $e) {
			
  			echo 'Message: ' .$e->getMessage();
		}
	}
}


}

?>
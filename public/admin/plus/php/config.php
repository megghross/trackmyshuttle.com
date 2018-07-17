<?php
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
	
	/* DYNAMO DB CONNECTION */	
	
	require $_SERVER['DOCUMENT_ROOT'].'/aws/aws-autoloader.php';
	date_default_timezone_set('UTC');
	    
    use Aws\DynamoDb\Exception\DynamoDbException;	
	
	$sdk = new Aws\Sdk([
       'region'   => 'us-east-1',
        'version'  => 'latest',
         'credentials' => [
            'key'    => 'AKIAI7UGADZN6KTU5DZA',
            'secret' => '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'
        ]
   ]);

	// Create a new DynamoDB client
	$dynamodb = $sdk->createDynamoDb();
        
	/*	Triteq AWS Development	*/
	
	$islive = 0;

	if ($islive==0){
		$username = "root"; 
		$password = ""; 		
		$host     = "localhost";
	    $dbname   = "trackmyshuttle";
	}
	else {
		$username = "dbuclients";
		$password = "Vclients20755!v";
		$host     = "159.65.228.181"; //"34.229.9.115";
	    $dbname   = "dbclients_trackmyshuttle";
	}


	
    $conn = mysqli_connect("$host","$username","$password","$dbname")or die("cannot connect"); 
    if (mysqli_connect_errno()) {die('Could not connect: ' . mysqli_connect_error());}		

   
    if (session_status() == PHP_SESSION_NONE) {
   	 session_start();
	}
?>

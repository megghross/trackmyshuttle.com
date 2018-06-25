<?php
     require("../plus/php/config.php");
	 
  $para = [
   "TableName"=> "device_data"
];

$params = [
    'TableName' => 'device_data',
    'KeySchema' => [
        [
            'AttributeName' => 'data_serial',
            'KeyType' => 'HASH'  //Partition key
        ],
    [
            'AttributeName' => 'id',
            'KeyType' => 'RANGE'  //Partition key
        ]
    ],
    'AttributeDefinitions' => [
         [
            'AttributeName' => 'data_serial',
            'AttributeType' => 'S' 
        ],
       [
            'AttributeName' => 'id',
            'AttributeType' => 'N' 
        ]
       
    ],
    'ProvisionedThroughput' => [
        'ReadCapacityUnits' => 10,
        'WriteCapacityUnits' => 10
    ]
];

if($_GET['action'] == 'delete')
$dynamodb->deleteTable($para);
else if($_GET['action'] == 'create')
{

try {
    $result = $dynamodb->createTable($params);
    echo 'Created table.  Status: ' . 
        $result['TableDescription']['TableStatus'] ."\n";

} catch (DynamoDbException $e) {
    echo "Unable to create table:\n";
    echo $e->getMessage() . "\n";
}
}
else if($_GET['action'] == 'populate')
{
$sql = "SELECT * FROM device_data WHERE 1=1"; 
		$res = mysqli_query($conn,$sql); 
		while($row = mysqli_fetch_array($res))
		{
			$result = $dynamodb->putItem(array(
				'TableName' => 'device_data',
				'Item' => array(
					'id'      				=> array('N' 	=> $row['id']),
					'data_serial'      		=> array('S' => $row['data_serial']),
					'data_lock_id'      	=> array('S' => $row['data_lock_id']),
					'data_temperature'      => array('N' => $row['data_temperature']),
					'data_max_tmptr'      	=> array('N' => $row['data_max_tmptr']),
					'data_lock_state'      	=> array('N' => $row['data_lock_state']),
					'data_door_state'      	=> array('N' => $row['data_door_state']),
					'data_sensor_state'     => array('N' => $row['data_sensor_state']),
					'data_power_state'      => array('N' => $row['data_power_state']),
					'data_overtmptr_state'  => array('N' => $row['data_overtmptr_state']),
					'data_cooldown_state'   => array('N' => $row['data_cooldown_state']),
					'data_overtmptr_time'   => array('N' => $row['data_overtmptr_time']),
					'data_cooldown_time'    => array('N' => $row['data_cooldown_time']),
					'data_timestamp'      	=> array('S' => $row['data_timestamp'])
					
					)
			));	
		}
  }
?>

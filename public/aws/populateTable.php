<?php
 require("../plus/php/config.php");


		$sql = "SELECT * FROM device_data WHERE 1=1"; 
		$res = mysqli_query($conn,$sql); 
		while($row = mysqli_fetch_array($res))
		{
			  	echo "<pre>";
			  	print_r($row);
			  	echo "</pre>";
				$result = $dynamodb->putItem(array(
				    'TableName' => 'device_data',
				    'Item' => array(
				        'data_lock_id'      => array('S' => $row['data_lock_id']),
					    'id'      => array('N' => $row['id']),
					    'data_lock_state'      => array('N' => $row['data_lock_state']),
					    'data_door_state'      => array('N' => $row['data_door_state']),
					    'data_sensor_state'      => array('N' => $row['data_sensor_state']),
					    'data_overtmptr_state'      => array('N' => $row['data_overtmptr_state']),
					    'data_cooldown_state'      => array('N' => $row['data_cooldown_state']),
					    'data_overtmptr_time'      => array('N' => $row['data_overtmptr_time']),
					    'data_cooldown_time'      => array('N' => $row['data_cooldown_time']),
					    'data_max_tmptr'      => array('N' => $row['data_max_tmptr']),
					    'data_temperature'      => array('N' => $row['data_temperature']),
					    'data_serial'      => array('S' => $row['data_serial']),
					    'data_timestamp'      => array('S' => $row['data_timestamp'])
					    
					    )
				));
print_r($result);	
			}
			

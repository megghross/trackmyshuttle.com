<?php
	include("config.php");
	if(isset($_SESSION['userkey'])){
		require("../../dhtmlx/apps/connector/grid_connector.php");
	    require("../../dhtmlx/apps/connector/db_mysqli.php");
		
		$search = "";
		if (isset($_GET["search"]))
			$search = $_GET["search"];

		//$sql = "select device.* FROM device dev inner join organization org on dev.org_key=org.org_key inner join location loc on loc.loc_key=dev.loc_key";
		$sql = "SELECT dev.*,org.org_name FROM devices dev INNER JOIN organization org ON dev.org_key=org.org_key";
		if ($search!="")
			$sql = $sql . " where (CONCAT(serialNumber, org_name) like '%". $search ."%')";


		$data = new GridConnector($conn,"MySQLi");
		//$data->enable_log("file.txt");
		
		/* PHP Connector EVENTS */
		/* http://docs.dhtmlx.com/connector__php__events_reference.html */
		
		/* ------ Data Loading -------*/
		$data->event->attach("beforeSort","before_sort");
		$data->event->attach("beforeFilter","before_filter"); 
		$data->event->attach("beforeRender","before_render"); 
		$data->event->attach("beforeOutput","before_output");
		/* ------ Data Updating -------*/
		$data->event->attach("beforeProcessing","before_processing");
		$data->event->attach("beforeUpdate","before_update");
		$data->event->attach("beforeInsert","before_insert");
		$data->event->attach("beforeDelete","before_delete");
		$data->event->attach("afterUpdate","after_update");
		$data->event->attach("afterInsert","after_insert");
		$data->event->attach("afterDelete","after_delete");
		$data->event->attach("afterProcessing","after_processing");
		$data->event->attach("onDBError","on_dberror");
		
		/* Data Update/Delete - http://docs.dhtmlx.com/connector__php__complex_queries.html#simplequeries
		 INSERT functionality using AJAX via project-pad.php */
		/* Data Rendering  - http://docs.dhtmlx.com/connector__php__basis.html */
		
		$data->render_sql($sql,"id","dfault,serialNumber,iccid,org_key,erase,delete,dtime");
	}
	
	/* http://docs.dhtmlx.com/connector__php__dataaction_object_methods.html */
	function before_sort($sorted_by){}
	function before_filter($filter_by){}
	function before_render($action){
		
		global $conn;
		
		/*
		$timestamp = $action->get_value("device_time");
		
		if($timestamp=="0"){
			$action->set_value("device_time",'<i style="font-size:90%;color:#EB3E19;">'.'No Data Found'.'</i>');
		}
		else{
			$comm = $action->get_value("device_comm_state");
			$inactive = $action->get_value("device_inactivity");
			
			if($inactive == 1 || $comm > 0 ){  
				$action->set_value("device_fault",'plus/img/icons/warning.png^>');
			}
			else{
				$action->set_value("device_fault",'plus/img/icons/dot.png^>');
			}
			
			$timeago = get_timeago($timestamp);
			$action->set_value("device_time",'<i style="font-size:90%;">'.$timeago.'</i>');
		}
		*/
		
		//$action->set_value("dtime",'<i style="font-size:90%;color:#EB3E19;">'.'No Data Found'.'</i>');
		$action->set_value("dtime",'<i style="font-size:90%;">'.'5 secs ago'.'</i>');
		$action->set_value("dfault",'plus/img/icons/dot.png^>');
		$action->set_value("erase",'plus/img/icons/erase.png^erase');
		$action->set_value("delete",'plus/img/icons/trash-o.png^delete');
		
		$org_key = $action->get_value("org_key");
		$sql = "SELECT org_name FROM organization WHERE org_key ='$org_key'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
		if($count==1){
			$row = mysqli_fetch_array($res);
			$action->set_value("org_key",$row['org_name']);
		}
		else{
			$action->set_value("org_key",'<i style="font-size:90%;color:#EB3E19;">'.'No Organization'.'</i>');
		}	
	}
	function before_output(){}
	function before_processing($action){}
	function before_update($action){}
	function before_insert($action){}
	function before_delete($action){}
	function after_update($action){}
	function after_insert($action){}
	function after_delete($action){}
	function after_processing($action){}
	function on_dberror($action, $exception){}

	function get_timeago($t)
	{
		$ptime = round($t/1000);
		$estimate_time = time() - $ptime;
		if( $estimate_time < 1 )
		{
			return 'less than 1 second ago';
		}
		$condition = array( 
					12 * 30 * 24 * 60 * 60  =>  'year',
					30 * 24 * 60 * 60       =>  'month',
					24 * 60 * 60            =>  'day',
					60 * 60                 =>  'hour',
					60                      =>  'minute',
					1                       =>  'second'
		);
		foreach( $condition as $secs => $str )
		{
			$d = $estimate_time / $secs;
			if( $d >= 1 )
			{
				$r = round( $d );
				return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
			}
		}
	}	
?>

<?php
	include("config.php");
	if(isset($_SESSION['userkey'])){
		require("../../dhtmlx/apps/connector/grid_connector.php");
	    require("../../dhtmlx/apps/connector/db_mysqli.php");
		
		$sql = "Select * FROM organization";
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
		
		/* Data Update/Delete - http://docs.dhtmlx.com/connector__php__complex_queries.html#simplequeries */
		/* Data Rendering  - http://docs.dhtmlx.com/connector__php__basis.html */	
		$data->render_sql($sql,"org_id","org_name,user_login,modules,shuttles,routes,users,org_delete","org_delete,user_login","org_key");
	}
	
	/* http://docs.dhtmlx.com/connector__php__dataaction_object_methods.html */
	function before_sort($sorted_by){}
	function before_filter($filter_by){}
	function before_render($action){
		
		global $conn; 
		
		$key = $action->get_value("org_key");
		$res = mysqli_query($conn, "SELECT count(*) FROM user WHERE org_key='$key'"); $row = mysqli_fetch_row($res); $num_user = $row[0];	
		$res = mysqli_query($conn, "SELECT count(*) FROM device WHERE org_key='$key'"); $row = mysqli_fetch_row($res); $num_device = $row[0];
		$res = mysqli_query($conn, "SELECT count(*) FROM shuttle WHERE org_key='$key'"); $row = mysqli_fetch_row($res); $num_shuttle = $row[0];
		$res = mysqli_query($conn, "SELECT count(*) FROM route WHERE org_key='$key'"); $row = mysqli_fetch_row($res); $num_route = $row[0];
		
		$action->set_value("users","<font color=#66646d>".$num_user." Users</font>");	
		$action->set_value("modules","<font color=#66646d>".$num_device." Trackers</font>");	
		$action->set_value("shuttles","<font color=#66646d>".$num_shuttle." Shuttles</font>");	
		$action->set_value("routes","<font color=#66646d>".$num_route." Routes</font>");	
		
		if ($num_device > 0 ) {			
			$action->set_value("org_delete",'plus/img/icons/trash-o.png^deleteNo');
		} else {
			$action->set_value("org_delete",'plus/img/icons/trash-o.png^delete');
		}

		$action->set_value("user_login",'plus/img/icons/external-link.png^userlogin');

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
	
?>

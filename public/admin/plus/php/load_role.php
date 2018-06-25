<?php
	include("config.php");
	if(isset($_SESSION['userkey'])){
		require("../../dhtmlx/apps/connector/grid_connector.php");
		require("../../dhtmlx/apps/connector/db_mysqli.php");
				
		$org_key = $_SESSION['orgkey'];
		$data = new GridConnector($conn,"MySQLi");
		//$data->enable_log("file_role.txt");
		
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
		$data->sql->attach("Delete","DELETE FROM role WHERE role_id='{role_id}'");
		/* Data Rendering  - http://docs.dhtmlx.com/connector__php__basis.html */
		$data->render_sql("SELECT * FROM role WHERE org_key='$org_key'","role_id","role_name,role_allow_edit");
	}
	
	/* http://docs.dhtmlx.com/connector__php__dataaction_object_methods.html */
	function before_sort($sorted_by){}
	function before_filter($filter_by){}
	function before_render($action){
		
		if($action->get_value("role_allow_edit")=="1")	{
			$action->set_value("role_allow_edit",'plus/img/icons/trash-o.png^delete');
		}
		else{
			$action->set_value("role_allow_edit",'plus/img/icons/minus-circle.png^deny');			
		}
	}
	function before_output(){}
	function before_processing($action){}
	function before_update($action){}
	function before_insert($action){}
	function before_delete($action){
		
		global $org_key;
		global $conn; 
			
		// In user_role, set all roles within this organization to "Default"
		$role_id = $action->get_value("role_id");
		$sql = "SELECT role_id FROM role WHERE (org_key='$org_key' AND role_name='Default' AND role_allow_edit='0')"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
		if($count==1){
			$row = mysqli_fetch_array($res);
			$default_role_id = $row['role_id'];
			mysqli_query($conn,"UPDATE user SET role_id = '$default_role_id' WHERE org_key = '$org_key' AND role_id = '$role_id'");
		}
		
		$sql = "SELECT org_key FROM role WHERE role_id='$role_id'"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
		if($count==1){
			$row = mysqli_fetch_array($res);
			$org_key = $row['org_key'];
			$sql = "SELECT role_id FROM role WHERE (org_key='$org_key' AND role_name='Default' AND role_allow_edit='0')"; $res = mysqli_query($conn,$sql); $count = mysqli_num_rows($res);   
			if($count==1){
				$row = mysqli_fetch_array($res);
				$default_role_id = $row['role_id'];
				mysqli_query($conn,"UPDATE user SET role_id = '$default_role_id' WHERE org_key = '$org_key' AND role_id = '$role_id'");
			}
		}
		
	}
	function after_update($action){}
	function after_insert($action){}
	function after_delete($action){}
	function after_processing($action){}
	function on_dberror($action, $exception){}
?>

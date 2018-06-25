<?php
	include("config.php");
	if(isset($_SESSION['userkey'])){
		require("../../dhtmlx/apps/connector/grid_connector.php");
	    require("../../dhtmlx/apps/connector/db_mysqli.php");
		
		$org_key = $_SESSION['orgkey'];
		
		
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
		$data->sql->attach("Delete","DELETE FROM user WHERE user_id='{user_id}'");
		/* Data Rendering  - http://docs.dhtmlx.com/connector__php__basis.html */
		$data->render_sql("SELECT user.*,CONCAT(user_first_name,' ',user_last_name) as user_name,organization.org_name FROM user LEFT JOIN organization on user.org_key=organization.org_key","user_id","user_name,org_name,user_email,user_phone,user_role,user_status,edit,delete","user_first_name,user_last_name,user_key");
	}
	
	/* http://docs.dhtmlx.com/connector__php__dataaction_object_methods.html */
	function before_sort($sorted_by){}
	function before_filter($filter_by){}
	function before_render($action){
		
		global $conn;

		$action->set_value("edit",'plus/img/icons/edit.png^edit');
		$action->set_value("delete",'plus/img/icons/trash-o.png^delete');
		
		
	}
	function before_output(){}
	function before_processing($action){}
	function before_update($action){
		$action->remove_field("edit");
		$action->remove_field("delete");
	}
	function before_insert($action){}
	function before_delete($action){}
	function after_update($action){}
	function after_insert($action){}
	function after_delete($action){}
	function after_processing($action){}
	function on_dberror($action, $exception){}
?>

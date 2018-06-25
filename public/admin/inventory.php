<?php
	session_start();
	include 'plus/php/constants.php';
	include 'plus/php/authentication.php';
	include SITEPATH . '/admin/plus/php/class.core.php';	 
	include SITEPATH . '/admin/plus/php/class.dbconnector.php';	
	
	if (!($_SESSION['user_role']=="Platform-Admin")) {
		header("location:../index.php");
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("header.php");	?>
<style>
.bootstrap-timepicker-widget table td input {
	width: 45px;
}
.select2-search-choice-close:before{
	content:'';
}
</style>
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class="">
  
<?php include('top.php'); ?>

<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  
 <?php include("sidebar.php") ?>
  
  <!-- BEGIN PAGE CORE-->
<div class="page-content"> 
	<div class="content">
	
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<form id="frm_mod">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">New Tracker</h4>
				  </div>
				  <div class="modal-body">
					<div class="row form-row">
						<div class="col-md-12">	
							<input name="new_formID" id="new_formID" type="text"  style="text-transform: uppercase;" class="form-control" placeholder="Serial"></div>
					</div>
					<div class="row form-row">
						<div class="col-md-12">	
							<input name="new_iccID" id="new_iccID" type="text"  style="" class="form-control" placeholder="ICCID"></div>
					</div>
					<div class="row form-row p-b-10">
						<div class="col-md-12">	
							<input type="hidden" id="new_selO" style="width:100%"/>
						</div>
					</div>
									
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="mod_cancel">Cancel</button>
					<button type="button" class="btn btn-primary" id="mod_create">Create</button>
				  </div>
				</form>
			  </div>
			</div>
		</div>

		<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<form id="frm_delete">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">Confirm Tracker Delete</h4>
					<p class="no-margin">Tracker and all Data will be deleted.</p>
					<br>
				  </div>
				  <div class="modal-body">
					  <div class="row form-row" id="div_pass_delete">
						<div class="input-append col-md-11 col-sm-11 primary">
						  <input type="input" name="mod_to_delete" id="mod_to_delete" class="form-control" placeholder="Enter Tracker Serial"></div>
					  </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="delete_cancel">Cancel</button>
					<button type="button" class="btn btn-danger" id="delete_confirm">Confirm Delete</button>
				  </div>
				</form>
			  </div>
			</div>
		</div>
		
		<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<form id="frm_erase">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">Confirm Data Erase</h4>
					<p class="no-margin">All Tracker Data will be deleted.</p>
					<br>
				  </div>
				  <div class="modal-body">
					  <div class="row form-row" id="div_pass_erase">
						<div class="input-append col-md-11 col-sm-11 primary">
						  <input type="input" name="mod_to_erase" id="mod_to_erase" class="form-control" placeholder="Enter Tracker Serial"></div>
					  </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="erase_cancel">Cancel</button>
					<button type="button" class="btn btn-danger" id="erase_confirm">Confirm Erase</button>
				  </div>
				</form>
			  </div>
			</div>
		</div>
	   
	   <div class="row">
			<div class="col-md-8">
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>Tracker <span class="semi-bold"></span>Management</h4>
					</div>
					<div class="grid-body no-border">
						<div><button type="button" class="btn btn-white btn-cons" data-toggle="modal" data-target="#myModal" id="add-mod">Add Tracker</button>
						<button type="button" class="btn btn-white" style="margin-bottom: 10px;" id="refresh-list"><i class="fa fa-refresh"></i></button>
						<input name="module-search" id="module-search" type="text"  style="width:200px; margin-bottom:10px;" class="pull-right" placeholder="Search"></div>
						<div id="grid-box" style="width:100%"></div>
						<div id="recinfoArea"></div>
					</div>
				</div>	  
			</div>

			<div class="col-md-4">
			  
				<div class="grid simple" id="div-assign">
					<div class="grid-title no-border">
					  <h4>Tracker <span class="semi-bold"></span> Assignment</h4>
					  <div class="tools"> <a href="javascript:;" class="collapse"></a>  </div>
					</div>
					<div class="grid-body no-border">
						<form class="form-no-horizontal-spacing" id="form1">	
							<div>
								<div class="row form-row">
									<div class="col-md-12">	
										<input name="formID" id="formID" type="text"  style="text-transform: capitalize;" class="form-control" placeholder="Serial #" readonly>		
									</div>
								</div>
								<div class="row form-row">
									<div class="col-md-12">	
										<input type="hidden" id="selO" style="width:100%"/>
									</div>
								</div>
							</div>
							<div class="form-actions">						
								<div class="pull-right">
								  <button class="btn btn-primary btn-cons" type="button" id="btn-save"><i class="icon-ok"></i>Apply</button>
								</div>
							  </div>
						</form>
					</div>
				</div>
					
			</div>
		</div>
		
	</div>
    </div>
  <!-- END PAGE CORE -->  
</div>
<!-- END CONTAINER --> 
<?php include("footer.php");?>
<!-- IoT JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core-min.js" type="text/javascript"></script>
<!-- Source https://github.com/awslabs/aws-iot-examples -->
<script type="text/javascript" src="plus/iot/bower_components/paho-mqtt-js/mqttws31.js"></script>
<script type="text/javascript" src="plus/iot/bower_components/cryptojslib/rollups/sha256.js"></script>
<script type="text/javascript" src="plus/iot/bower_components/cryptojslib/rollups/hmac-sha256.js"></script>
<script type="text/javascript" src="plus/iot/iot.js"></script>
<script type="text/javascript" src="plus/js/inventory.js"></script>
</body>
</html>
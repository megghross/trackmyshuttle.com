<?php

	session_start();
	include 'plus/php/constants.php';
	include 'plus/php/authentication.php';
	include SITEPATH . '/admin/plus/php/class.core.php';	 
	include SITEPATH . '/admin/plus/php/class.dbconnector.php';	
	include SITEPATH . '/admin/plus/php/class.timezone.php';	
	
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
				<form id="frm_org">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">New Organization</h4>
				  </div>
				  <div class="modal-body">
					<div class="row form-row">
					  <div class="col-md-12">
						<input type="text" class="form-control" placeholder="Organization Name" name="new_org_name" id="new_org_name">
					  </div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="org_cancel">Cancel</button>
					<button type="button" class="btn btn-primary" id="org_create">Create</button>
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
				<h4 id="myModalLabel" class="semi-bold">Confirm Organization Delete</h4>
				<p class="no-margin">Organization will be closed and data deleted.</p>
				<br>
			  </div>
			  <div class="modal-body">
				  <div class="row form-row" id="div_pass_delete">
					<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="input" name="org_to_delete" id="org_to_delete" class="form-control" placeholder="Enter Organization Name"></div>
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
	   
	   <div class="row">
			<div class="col-md-8">
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>Organization <span class="semi-bold"></span>Management</h4>
					</div>
					<div class="grid-body no-border">
						<div><button type="button" class="btn btn-white btn-cons" data-toggle="modal" data-target="#myModal" id="add-org">Add Organization</button>
						<button type="button" class="btn btn-white" style="margin-bottom: 10px;" id="refresh-org"><i class="fa fa-refresh"></i></button></div>
						<div id="grid-box" style="width:100%"></div>
					</div>
				</div>	  
			</div>

			<div class="col-md-4">
			  
				<div class="grid simple" id="div-address">
					<div class="grid-title no-border">
					  <h4><span class="semi-bold"></span>Address</h4>
					  <div class="tools"> <a href="javascript:;" class="collapse"></a>  </div>
					</div>
					<div class="grid-body no-border">
					<form class="form-no-horizontal-spacing" id="form_address">	
					  <div class="row column-seperation">
						<div class="col-md-12">
							<div class="row form-row">
							  <div class="col-md-12">
								<input name="org_name" id="org_name" type="text"  class="form-control" placeholder="Organization Name">
							  </div>
							</div>
							<div class="row form-row">
							  <div class="col-md-12">
								<input name="org_address" id="org_address" type="text"  class="form-control" placeholder="Address">
							  </div>
							</div>
							<div class="row form-row">
							  <div class="col-md-12">
								<input name="org_city" id="org_city" type="text"  class="form-control" placeholder="City">
							  </div>
							</div>
							<div class="row form-row">
								<div class="col-md-5">
									<input name="org_state" id="org_state" type="text"  class="form-control" placeholder="State">
								</div>
								<div class="col-md-7">
									<input name="org_zip" id="org_zip" type="text"  class="form-control" placeholder="Postal Code">
								</div>
							</div>
							<div class="row form-row">
							  <div class="col-md-12">
								<input name="org_country" id="org_country" type="text"  class="form-control" placeholder="Country">
							  </div>
							  
							</div>
							<div class="row form-row">
								<div class="col-md-12">
									<input name="org_phone" id="org_phone" type="text"  class="form-control" placeholder="Phone Number">
								</div>
							
							</div>
							<div class="row form-row" >
									<div class="col-md-6">
										<input name="formLat" id="formLat" type="text"  class="form-control" placeholder="Latitude">
									</div>
									<div class="col-md-6">
										<input name="formLng" id="formLng" type="text"  class="form-control" placeholder="Longitude">
									</div>
							</div>
							<div class="row form-row">
							  <div class="col-md-12">								    
								<select id="formTzone" style="width:100%">
									<option></option>
								<?php 
									$objtimezone = new timezone();
									$timezonelist = $objtimezone->timezone_list();
									foreach($timezonelist as $x => $x_value) { ?>
									<option value="<?php echo $x; ?>"><?php echo $x_value; ?></option>
								<?php }	?>
								</select>
							  </div>
							</div>
							<div class="row small-text">
							<p class="col-md-12">
							</p>
							</div>
					 
						</div>
					  </div>
						<div class="form-actions">
							<div class="pull-right">
							  <!--<button class="btn btn-white btn-cons" type="button">Cancel</button>-->
							  <input type="hidden" name="org_key" id="org_key"/>
							  <button class="btn btn-primary btn-cons" type="submit"><i class="icon-ok"></i>Apply</button>
							</div>
						 </div>
					</form>
					</div>
				</div>	

				<div class="grid simple" id="div-code">
					<div class="grid-title no-border">
					  <h4><span class="semi-bold"></span>Invitation Code</h4>
					</div>
					<div class="grid-body no-border">
					<form class="form-no-horizontal-spacing" id="code_generate">	
					  <div class="row column-seperation">
						<div class="col-md-12">
							<div class="row form-row">
							  <div class="col-md-12">
								<input name="user_code" id="user_code" type="text" style="text-transform: uppercase;"  class="form-control" placeholder="Enter Code">
							  </div>
							</div>
						</div>
					  </div>
						<div class="form-actions">
							<div class="pull-right">
							  <button class="btn btn-primary btn-cons" type="submit"><i class="icon-ok"></i>Apply</button>
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
<script type="text/javascript" src="plus/js/org.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfz3zjB9Lyq-jdToA3tuAWf3ppiX-BErc"></script>
</body>
</html>
<?php
	session_start();
	include 'plus/php/authentication.php';
	include 'plus/php/constants.php';
	include SITEPATH . '/admin/plus/php/class.core.php';	 
	include SITEPATH . '/admin/plus/php/class.dbconnector.php';		
	
	if (!($_SESSION['user_role']=="Platform-Admin")) {
		header("location:index.php");
	} 
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("header.php");?>		
</head>

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
				<form id="frm_edit">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">Edit User</h4>
				  </div>
				  <div class="modal-body">
					<div class="row form-row">
					  <div class="col-md-12">
					    <input name="formFirstName" id="formFirstName" type="text" class="form-control" placeholder="First Name"/>
						<input name="formLastName" id="formLastName" type="text" class="form-control" placeholder="Last Name"/>
						<input name="formPhone" id="formPhone" type="text"  class="form-control" placeholder="Phone Number"/>						
						<input name="frmPassword" id="frmPassword" type="text"  class="form-control" placeholder="Reset Password"/>						
						<select id="formUserRole" name="formUserRole" style="width:100%">
							<option value="Platform-Admin">Platform-Admin</option>
							<option value="Org-Admin">Org-Admin</option>
							<option value="End-User">End-User</option>
						</select>
				
						<input type="hidden" id="formEmail" style="width:100%"/>
					  </div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancel">Cancel</button>
					<button type="button" class="btn btn-primary" id="btn_editsave">Save</button>
					<button type="button" class="btn btn-danger" id="btn_resetpassword">Reset Password</button>
				  </div>
				</form>
			  </div>
			</div>
		</div>
			
		<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<form id="frm_invite">
				  <div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">Invite User</h4>
				  </div>
				  <div class="modal-body">
					<div class="row form-row">
					  <div class="col-md-12">
					  	<div class="form">
					  		<input name="formFirstName2" id="formFirstName2" type="text" class="form-control" placeholder="First Name" >	
					  	</div>
					    <div class="form">
					  		<input name="formLastName2" id="formLastName2" type="text" class="form-control" placeholder="Last Name">	
					  	</div>
					  	 <div class="form">
					  		<input name="formEmail2" id="formEmail2" type="text" class="form-control" placeholder="Email">	
					  	</div>
					  	<div class="form">
					  		<input name="formPhone2" id="formPhone2" type="text" class="form-control" placeholder="Phone">	
					  	</div>

					  	<div class="form p-b-10">
							<select id="formUserRole2" name="formUserRole2" style="width:100%">
							<option></option>
							<option value="Platform-Admin">Platform-Admin</option>
							<option value="Org-Admin">Org-Admin</option>
							<option value="End-User">End-User</option>
							</select>
					  	</div>
						<div class="row form-row" id="formOrg2">
							<div class="col-md-12">	
								<input type="hidden" id="new_selO" style="width:100%"/>							
							</div>
						</div>
						
						
					  </div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancel2" onclick="frm_invite.reset();">Cancel</button>
					<button type="button" class="btn btn-primary" id="btn_new">Invite</button>
				  </div>
				</form>
			  </div>
			</div>
		</div>
		
		<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
					<h4 id="myModalLabel" class="semi-bold">User will be deleted.</h4><br>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-danger" id="user_delete">Confirm Delete</button>
				</div>
			  </div>
			</div>
		</div>
	  		
		<div class="row">
			<div class="col-md-12">		  
			  <div class="grid simple">
				<div class="grid-title no-border">
				  <h4>User <span class="semi-bold"></span>Management</h4>
				</div>
				<div class="grid-body no-border">
				<form class="form-no-horizontal-spacing" id="form1Role">	
				  <div class="row column-seperation">
					<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
						<div class="grid simple" id="div-roleview" style="margin-bottom: 0px;">
							<div class="grid-body no-border">
								<div><button type="button" class="btn btn-white btn-cons" data-toggle="modal" data-target="#myModal2" id="add-user">Add User</button>
								<button type="button" class="btn btn-white" style="margin-bottom: 10px;" id="refresh-user"><i class="fa fa-refresh"></i></button></div>
								<div id="grid-box" style="width:100%"></div>
							</div>
						 </div>
					</div>
				  </div>
				</form>
			 <div id='grid-region'></div>
    <div class="loading-image" id="table-ajax-loader"></div>
    <div class="paginate-head">
        <div id="pagingArea"></div>
        &nbsp;
        <div id="infoArea"></div>
    </div>
				</div>
			  </div>
			</div>
		</div>
		
	</div>
	</div>
  <!-- END PAGE CORE -->  
	
	
  </div>
 <input  type="hidden" id="user_key" name="user_key" value=<?php echo json_encode($user_key); ?>/>
 <input  type="hidden" id="org_key" name="org_key" value=<?php echo json_encode($org_key); ?>/>
 <input  type="hidden" id="user_role" name="user_role" value=<?php echo json_encode($user_role); ?>/>
<!-- END CONTAINER --> 
<?php include("footer.php");	?>

<script src="plus/js/users.js?v=1.1" type="text/javascript"></script>


</body>
</html>
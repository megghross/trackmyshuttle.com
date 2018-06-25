<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) { die('Access denied'); };
?>
<!-- BEGIN CORE JS FRAMEWORK -->
<script src="arc/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/breakpoints.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>
<!-- BEGIN COMMON JS FRAMEWORK -->
<script src="arc/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
<script src="plus/plugins/select2/select2.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>                           
<script src="arc/assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>              
<script src="arc/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/boostrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-inputmask/jquery.inputmask.min.js" type="text/javascript"></script>                      
<script src="arc/assets/plugins/jquery-autonumeric/autoNumeric.js" type="text/javascript"></script>                             
<script src="arc/assets/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<!-- DHTMLX FRAMEWORK  -->
<script src="dhtmlx/dhtmlx.js" type="text/javascript"></script>
<script src="dhtmlx/dhtmlxdataprocessor.js" type="text/javascript"></script>
<script src="dhtmlx/apps/connector/connector.js" type="text/javascript"></script>
<!-- BEGIN JS TEMPLATE -->
<script src="arc/assets/js/core.js" type="text/javascript"></script>
<!-- BEGIN EXTRA JS -->
<script src="arc/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-jvectormap/js/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-jvectormap/js/jquery-jvectormap-us-lcc-en.js" type="text/javascript"></script>
<script src="arc/assets/plugins/skycons/skycons.js"></script>
<!-- CUSTOM JS  -->
<script src="plus/js/common.js" type="text/javascript"></script>

<div class="modal fade" id="mdlChangePassword" tabindex="-1" role="dialog" aria-labelledby="mdlChangePassword" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<form id="frm_reset_password">
		  <div class="modal-header">
			<h4 id="myModalLabel" class="semi-bold">Update Password</h4>
		  </div>
		  <div class="modal-body">
			<div class="row form-row">
			  <div class="col-md-12">
				<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_curr" id="pass_curr" class="form-control" placeholder="Current password">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> 
				</div>
				<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_new" id="pass_new" class="form-control" placeholder="New password">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> 
				</div>
				<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_new_again" id="pass_new_again" class="form-control" placeholder="Reenter New password">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> 
				</div>					  			  	
			  </div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancel_reset">Cancel</button>
			<button type="button" class="btn btn-primary" id="btnUpdatePassword">Update Password</button>
		  </div>
		</form>
	  </div>
	</div>
</div>


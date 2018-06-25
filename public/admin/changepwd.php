<?php
	require("plus/php/config.php");
	if(isset($_SESSION['userkey'])){
		if(isset($_GET['r'])) { $route = $_GET['r']."php"; }
			else{ $route = "dashboard.php"; }

	}
	else{header("location:index.php");}	
	
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title>Track My Shuttle</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="arc/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="arc/assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="arc/assets/plugins/jquery-notifications/css/messenger-theme-air.css" rel="stylesheet" type="text/css" media="screen"/>
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="arc/assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/css/magic_space.css" rel="stylesheet" type="text/css"/>
<link href="arc/assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
<style>ul.messenger.messenger-fixed.messenger-on-top {width: 660px;}</style>
<!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body data-original="plus/img/work.jpeg"  style="background-color: #e5e9ec !important;">
<div class="container">

	<div class="row login-container animated fadeInUp">  
	<div class="col-md-6 col-md-offset-2">
		<div class="grid simple">				
			<div class="grid-body no-border">
			  <form id="frm_reset">
				<div class="row-fluid">
				  <h3> <span class="semi-bold">Update Password</span></h3>
				  <br>
				  <div class="row form-row" id="div_pass_curr">
					<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_curr" id="pass_curr" class="form-control" value="" placeholder="Current password" autocomplete="off">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> </div>
				  </div>
				  <div class="row form-row" id="div_pass_new">
					<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_new" id="pass_new" class="form-control" value="" placeholder="new password" autocomplete="off">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> </div>
				  </div>
				  <div class="row form-row" id="div_pass_new2">
					<div class="input-append col-md-11 col-sm-11 primary">
					  <input type="password" name="pass_new2" id="pass_new2" class="form-control" value="" placeholder="new password again" autocomplete="off">
					  <span class="add-on"><span class="arrow"></span><i class="fa fa-lock"></i> </span> </div>
				  </div>
				</div>
				<div class="form-actions">
				  <div class="pull-right">
					<button type="button" class="btn btn-white btn-cons-md" id="pass_clear" onclick="location.href='<?php echo $_SERVER["HTTP_REFERER"]; ?>'">Cancel</button>
					<button type="button" class="btn btn-primary btn-cons-md" id="btnUpdatePassword">Update Password</button>
				  </div>
				</div>
			  </form>
			</div>
		</div>
	</div>
	</div>
	
</div>
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="arc/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-lazyload/jquery.lazyload.min.js" type="text/javascript"></script>
<script src="arc/assets/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<!-- END CORE TEMPLATE JS -->
<script>

$(document).ready(function() {
	$( "#pass_curr" ).focus();
	Messenger.options = {
		extraClasses: 'messenger-fixed messenger-on-top',
		theme: 'air'
	}
	$('#btnUpdatePassword').click(function(){
		$('#frm_reset').submit();
    });
	$('#pass_clear').click(function(){
		$('#pass_new,#pass_new2').val('');
		val.resetForm();
	})
	
	val = $("#frm_reset").validate({
		rules: {
			pass_new: {required: true, minlength: 8},
			pass_curr: {required: true, minlength: 8},
			pass_new2: {required: true, equalTo: "#pass_new"}
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "pass_new"){error.insertAfter('#div_pass_new')}
			else if (element.attr("name") == "pass_new2"){error.insertAfter('#div_pass_new2')}
			else if (element.attr("name") == "pass_curr"){error.insertAfter('#div_pass_curr')}
		},
		submitHandler: function(form) {
			//el1 = $('#frm_reset_password');
			//blockUI(el1);
			$.ajax({
				type: "POST",
				url: "plus/php/ajax.php",
				data: {mode:"reset",pass_curr:$('#pass_curr').val(),pass_new:$('#pass_new').val()},
				success: function(msg){
				//	unblockUI(el1);				
					msg = JSON.parse(msg);
					console.log(msg);
					if(msg.status=="error"){
						Messenger().post({message: msg.message, type: 'error'});
					}
					else{
						Messenger().post({message: msg.message, type: 'success'});	
						setTimeout(function(){ window.location = '../logout.php?r=<?php echo $_SERVER["HTTP_REFERER"]; ?>'; }, 2000);			
					}	
				},
				error: function(){
					$('#pass_clear').click();
				}				
			});
		}
	});
});

</script>
</body>
</html>
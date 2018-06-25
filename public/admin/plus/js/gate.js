

$(document).ready(function() {

	


	//$('#reg-code').mask('aaa-999');
	/*Messenger.options = {
		extraClasses: 'messenger-fixed messenger-on-top',
		theme: 'air'
	}*/
	
	/*$('#frm_login').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
		
        },
		
		errorPlacement: function (label, element) { // render error placement for each input type   
			
		},
		success: function (label, element) {
			
		},
		submitHandler: function(form) {
			
			$.ajax({ 
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_login').serialize()+"&mode=login",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){
						Messenger().post({message: msg.message, type: 'error'});
						//alert(msg.message);
					}
					else{
						if(msg.status=="success"){
							window.location.href = "org.php";
						}						
					}										
				},
				error: function(){
				}
			});
		}
	});	*/

$('#login-btn').on('click', function(e) {
	var inpObj = document.getElementById("frm_login");
	if(inpObj.checkValidity()) {
    	e.preventDefault();
    	$.ajax({ 
				type: "POST",
				url: "admin/plus/php/gate.php",
				data: $('#frm_login').serialize()+"&mode=login",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){
						//Messenger().post({message: msg.message, type: 'error'});
						//alert(msg.message);
						$(".w-form-fail").text(msg.message);
						$(".w-form-fail").show();
					}
					else{
						if(msg.status=="success"){
							if (msg.role!="Platform-Admin")
								window.location.href = "dashboard.php";
							else
								window.location.href = "admin/org.php";
						}						
					}										
				},
				error: function(){
				}
			});
    }
});


$('#register-btn').on('click', function(e) {
var inpObj = document.getElementById("email-form");
	if(inpObj.checkValidity()) {
    	e.preventDefault();
    	//alert($('#reg-form').serialize());
    	$.ajax({ 
				type: "POST",
				url: "admin/plus/php/gate.php",
				data: $('#email-form').serialize()+"&mode=register",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){
						//Messenger().post({message: msg.message, type: 'error'});
						//alert(msg.message);
						$(".w-form-fail").text(msg.message);
						$(".w-form-fail").show();
					}
					else{
						if(msg.status=="success"){
							$(".w-form-done").text(msg.message);
						$(".w-form-done").show();
							if (msg.role!="Platform-Admin")
								window.location.href = "dashboard.php";
							else
								window.location.href = "admin/org.php";
						}						
					}										
				},
				error: function(){
				}
			});
    }
});



	/*$('#frm_reset_password').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
			
		},
		errorPlacement: function (label, element) { // render error placement for each input type   
			
		},
		success: function (label, element) {
			
		},
		submitHandler: function(form) {
			
			$.ajax({ 
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_reset_password').serialize()+"&mode=recover",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
					else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
					else{
						$('#div-gate').hide();
						Messenger().post({message: msg.message, type: 'success'});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
											
				},
				error: function(){
				}
			});
		}
	});	*/

	$('#submit-reset-password').on('click', function(e) {
    var inpObj = document.getElementById("frm_reset_password");
	if(inpObj.checkValidity()) {
    	e.preventDefault();
    	$.ajax({ 
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_reset_password').serialize()+"&mode=recover",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
					else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
					else{
						$('#div-gate').hide();
						//Messenger().post({message: msg.message, type: 'success'});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
											
				},
				error: function(){
				}
			});
    }
});

	var password = document.getElementById("new_password")
  , confirmPassword = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirmPassword.value) {
    confirmPassword.setCustomValidity("Passwords Don't Match");
  } else {
    confirmPassword.setCustomValidity('');
  }
}

//password.onchange = validatePassword;
//confirmPassword.onkeyup = validatePassword;

	$('#submit-change-password').on('click', function(e) {

    var inpObj = document.getElementById("frm_change_password");
	if(inpObj.checkValidity()) {
    	e.preventDefault();
    	$.ajax({ 
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_change_password').serialize()+"&mode=resetpassword",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
					else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
					else{
						$('#div-gate').hide();
						//Messenger().post({message: msg.message, type: 'success'});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
											
				},
				error: function(){
				}
			});
    }
});


});


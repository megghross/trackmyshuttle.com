el = $('#div-gate');

$(document).ready(function() {
	
	Messenger.options = {
		extraClasses: 'messenger-fixed messenger-on-top',
		theme: 'air'
	}
	
	$('#login_email').val('');
	$('#login_pass').val('');
	$('#reg_email').val('');
	$('#reg_pass').val('');
	$('#rec_email').val('');
	
	$('#login_toggle').click(function(){
		$('#frm_login').show();
		$('#frm_register').hide();
		$('#frm_recover').hide();
		$('#frm_invite').hide();
		$('#frm_contact').hide();
	})
	$('#register_toggle').click(function(){
		$('#reg_email').val('');
		$('#reg_pass').val('');
		$('#frm_login').hide();
		$('#frm_register').show();
		$('#frm_recover').hide();
		$('#frm_invite').hide();
		$('#frm_contact').hide();
	})
	$('#recover_toggle').click(function(){
		$('#frm_login').hide();
		$('#frm_register').hide();
		$('#frm_recover').show();
		$('#frm_invite').hide();
		$('#frm_contact').hide();
	})
	$('#invite_toggle').click(function(){
		$('#frm_login').hide();
		$('#frm_register').hide();
		$('#frm_recover').hide();
		$('#frm_invite').show();
		$('#frm_contact').hide();
	})
	$('#contact_toggle').click(function(){
		$('#frm_login').hide();
		$('#frm_register').hide();
		$('#frm_recover').hide();
		$('#frm_invite').hide();
		$('#frm_contact').show();
	})
	
	$(".lazy").lazyload({
      effect : "fadeIn"
   });

   
	if(tab=="invite"){
		$('#invite_toggle').click();
		$("#inv_email").val(email);
		$("#inv_code").val(code);
	}
	
	$('#frm_login').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
			login_email: {required: true, email: true},
			login_pass: {required: true}
		},
		errorPlacement: function (label, element) { // render error placement for each input type   
			$('<span class="error"></span>').insertAfter(element).append(label)
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('success-control').addClass('error-control');  
		},
		success: function (label, element) {
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('error-control').addClass('success-control'); 
		},
		submitHandler: function(form) {
			blockUI(el);
			Messenger().hideAll();
			document.getElementById("login-btn").disabled = true;
			$.ajax({ 
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_login').serialize()+"&mode=login",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
					//else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
					else{
						$('#div-gate').hide();
						if(msg.status=="success"){
							if ($("#r").val()!='')
								window.location.href = $("#r").val();
							else
								window.location.href = "dashboard.php";
						}
						else {
							Messenger().post({message: msg.message, type: 'success'});
						}
					}
					document.getElementById("login-btn").disabled = false;
					unblockUI(el);
				},
				error: function(){
				}
			});
		}
	});	

	$('#frm_register').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
			reg_email: {required: true, email: true},
			reg_pass: {required: true, minlength: 8},
			reg_org: {required: true},
			reg_address: {required: true},
			reg_state: {required: true},
			reg_city: {required: true},
			reg_code: {required: true}
		},
		errorPlacement: function (label, element) { // render error placement for each input type   
			$('<span class="error"></span>').insertAfter(element).append(label)
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('success-control').addClass('error-control');  
		},
		success: function (label, element) {
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('error-control').addClass('success-control'); 
		},
		submitHandler: function(form) {
			blockUI(el);
			Messenger().hideAll();
			document.getElementById("reg-btn").disabled = true;
			$.ajax({
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_register').serialize()+"&mode=register",
				success: function(msg){ 
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error', showCloseButton: true});}
					else{
						$('#div-gate').hide();
						Messenger().post({type:msg.status, message: msg.message});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
					unblockUI(el);
					document.getElementById("reg-btn").disabled = false;
				},
				error: function(){
				}
			});
		}
	});
	
	
	$('#frm_invite').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
			inv_email: {required: true, email: true},
			inv_pass: {required: true, minlength: 8},
			inv_pass_confirm: {required: true, minlength: 8,equalTo: "#inv_pass"},
			inv_code: {required: true},
		},
		errorPlacement: function (label, element) { // render error placement for each input type   
			$('<span class="error"></span>').insertAfter(element).append(label)
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('success-control').addClass('error-control');  
		},
		success: function (label, element) {
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('error-control').addClass('success-control'); 
		},
		submitHandler: function(form) {
			blockUI(el);
			Messenger().hideAll();
			document.getElementById("inv-btn").disabled = true;
			$.ajax({
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_invite').serialize()+"&mode=invite",
				success: function(msg){
					document.getElementById("inv-btn").disabled = false;
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error', showCloseButton: true});}
					else{
						Messenger().post({type:msg.status, message: msg.message});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
					unblockUI(el);
				},
				error: function(){
				}
			});
		}
	});
	
	$('#frm_recover').validate({
		focusInvalid: false, 
		ignore: "",
		rules: {
			rec_email: {required: true,	email: true}
		},
		errorPlacement: function (label, element) { // render error placement for each input type   
			$('<span class="error"></span>').insertAfter(element).append(label)
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('success-control').addClass('error-control');  
		},
		success: function (label, element) {
			var parent = $(element).parent('.input-with-icon');
			parent.removeClass('error-control').addClass('success-control'); 
		},
		submitHandler: function(form) {
			blockUI(el);
			Messenger().hideAll();
			document.getElementById("rec-btn").disabled = true;
			$.ajax({
				type: "POST",
				url: "plus/php/gate.php",
				data: $('#frm_recover').serialize()+"&mode=recover",
				success: function(msg){
					msg = JSON.parse(msg);
					if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
					else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
					else{
						$('#div-gate').hide();
						Messenger().post({message: msg.message, type: 'success'});
						setTimeout(function(){window.location.href = "index.php";}, 5000);
					}
					unblockUI(el);
				},
				error: function(){
				}
			});
		}
	});
});

function msg_pop(msg,label,mode){
	Messenger().post({
		message: msg.message, type: 'error', showCloseButton: true,
		actions: {
			cancel: {
				label: label,
				action: function(){
					Messenger().hideAll();
					$.ajax({
						type: "POST",
						url: "plus/php/gate.php",
						data: {mode:mode,email:msg.email},
						success: function(msg){
							msg = JSON.parse(msg);
							Messenger().post({type:msg.status, message: msg.message});
							setTimeout(function(){window.location.href = "index.php";}, 5000);
						},
						error: function(){
						}
					});
					
				}
			},
		}
	});
}

function blockUI(el) {
	$(el).block({
		message: '<div class="loading-animator"></div>',
		css: {
			border: 'none',
			padding: '2px',
			backgroundColor: 'none'
		},
		overlayCSS: {
			backgroundColor: '#fff',
			opacity: 0.3,
			cursor: 'wait'
		}
	});
}
function unblockUI(el) {
	$(el).unblock();
}


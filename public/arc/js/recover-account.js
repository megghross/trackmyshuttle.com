$(document).ready(function() {
	$('#submit-reset-password').on('click', function(e) {
	    var inpObj = document.getElementById("frm_reset_password");
		if(inpObj.checkValidity()) {
	    	e.preventDefault();
	    	$.ajax({ 
					type: "POST",
					url: "admin/plus/php/gate.php",
					data: $('#frm_reset_password').serialize()+"&mode=recover",
					success: function(msg){
						msg = JSON.parse(msg);
						if(msg.status=="error"){Messenger().post({message: msg.message, type: 'error'});}
						else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
						else{
							$("#frm_reset_password").hide();
							$('.w-form-done').show();
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
$(document).ready(function() {
	$('#btnResetPassword').on('click', function(e) {
	    var inpObj = document.getElementById("reset-form");
		if(inpObj.checkValidity()) {
	    	e.preventDefault();
	    	$.ajax({ 
					type: "POST",
					url: "plus/php/gate.php",
					data: $('#reset-form').serialize()+"&mode=resetpassword",
					success: function(msg){
						msg = JSON.parse(msg);
						if(msg.status=="error"){
							//Messenger().post({message: msg.message, type: 'error'});
							$(".w-form-fail").show();
						}
						else if (msg.status=="pending") {msg_pop(msg,"Resend Activation Email","reverify");}
						else{
							$("#reset-form").hide();
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
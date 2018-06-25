function send(serial,command) {
	var formData1 = {serial:serial,command:command};	
	$.ajax({ 
		type: "POST",
		url: "plus/php/lockcommand.php",
		data: formData1,
		success: function(msg){
			
		},
		error: function(){
		}
	});
}
	
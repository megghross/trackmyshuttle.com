org_key = $("#org_key").val();
$.getJSON('plus/php/common.php',{mode:'account_getprofile'}, function(msg) {
		
$("#account_role").text(msg.role_name.toUpperCase());
$("#org_name").text(msg.org_name.toUpperCase());
$("#org_addr1").html(msg.org_address+"<br>"+msg.org_city+","+msg.org_state+","+msg.org_zip);
});
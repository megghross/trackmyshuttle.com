	$("#formUserRole2,#formUserRole").select2({
		placeholder: "Select Role"
	});
	$("#formPhone2").mask("(999) 999-9999");
	
	user_key = $("#user_key").val();
	user_role = $("#user_role").val();
	
	grid = new dhtmlXGridObject('grid-box');
	grid.setImagePath("dhtmlx/imgs/");	
	grid.setHeader("Name,Organization,Email,Phone,Role,Status,,");
	grid.setInitWidths("*,200,250,150,125,75,40,60");
	grid.setColAlign("left,left,left,left,left,left,center,left");     
	grid.setColTypes("ro,ro,ro,ro,ro,ro,img,img");
	grid.setColSorting("str,str,str,str,str,str,str"); 
	grid.enableAutoHeight(true);
	grid.setColumnMinWidth(150,0);
    grid.init();
	grid.load("plus/php/load_user.php",function(){
		
	});
	grid.objBox.style.width="103%";
	

	grid.attachEvent("onMouseOver", function(id,ind){
		var cellObj = grid.cells(id,ind);
		cellObj.cell.style.cursor="pointer";
	});
	
	var dp = new dataProcessor("plus/php/load_user.php");
	dp.init(grid);

	dp.defineAction("deleted",function(response){
		action="deleted";
		return true;
	})
	
	/* User Edit */
	grid.attachEvent("onRowSelect", function(rid,index){
	//	alert(index);
		if(index==6){	
			rowid = rid;
			cellval = grid.cells(rid,index).getValue();
			cellval = cellval.split("^");
			if(cellval[1]=="edit"){				
					user_email = grid.cells(rid,2).getValue();						
					$('#myModal').modal('show');	
					$.post('plus/php/ajax.php',{mode:'user_detail',user_email: user_email}, function(data) {
						$("#formFirstName").val(data.user_first_name);
						$("#formLastName").val(data.user_last_name);
						$("#formPhone").val(data.user_phone);
						$("#formUserRole").val(data.user_role).trigger("change");						
						$("#formEmail").val(user_email);
						$("#frmPassword").val('');
						$("#formPhone").mask("(999) 999-9999");
					});				
			}
		}
		else if(index==7){
			cellval = grid.cells(rid,index).getValue();
			cellval = cellval.split("^");
			if(cellval[1]=="delete"){		
				$('#myModal3').modal('show');
			}
		}
	});
	
	/* Refresh */
	$('#refresh-user').click(function(){
		grid_reload();
	})
	
	/* Grid Reload */
	function grid_reload(){
		grid.clearAll();
		grid.load("plus/php/load_user.php",function(){
		});
	}
	
	/* Invite New User */
	/* Add New Module */
	$('#add-user').on('click', function(){  
	   $.getJSON('plus/php/common.php',{mode:'device_orglist'}, function(data) {					
			$("#new_selO").select2({
				placeholder: "Select Organization",
				allowClear: true,
				data: data
			});
	   })
	});
		
	
	$('#btn_new').click(function(){
		$('#frm_invite').submit();
	})
	
	val = $("#frm_invite").validate({
		rules: {
			formFirstName2: {
				required: true,
				minlength: 2
			},
			formLastName2: {
				required: true,
				minlength: 2
			},
			formEmail2: {
				required: true,
				email:true
			},
			formPhone2: {
				required: true,
				minlength: 8
			},
			formUserRole2: {
				required: "#formOrg2:visible"
			}
		},
		submitHandler: function(form) {
			formUserRole2 = $('#formUserRole2').val();
			if (!$('#new_selO').val() && formUserRole2!='Platform-Admin'){
				new_orgid='0'; 
				msg_error("Please Select Organization");
			}
			else{
				el1 = $('#frm_invite');
				blockUI(el1);
				formFirstName2 = $('#formFirstName2').val();
				formLastName2 = $('#formLastName2').val();
				formEmail2 = $('#formEmail2').val();
				formPhone2 = $('#formPhone2').val();
				
				if (formUserRole2!='Platform-Admin')
					formUserOrg2 = $("#new_selO").select2("data").id;
				else
					formUserOrg2 =0;
				
				$.ajax({
					type: "POST",
					url: "plus/php/ajax.php",
					data: {
						mode:"user_invite",
						first_name: formFirstName2,
						last_name: formLastName2,
						email:formEmail2,
						phone: formPhone2,
						user_role:formUserRole2,
						user_org:formUserOrg2
					},
					success: function(msg){
						unblockUI(el1);
						//msg = JSON.parse(msg);
						if(msg.status=="error"){msg_error(msg.message);}
						else{
							msg_success(msg.message);
							$('#frm_invite')[0].reset();
							$("#formUserRole2").select2();
							$("#formUserRole2,#formUserRole").select2({
								placeholder: "Select Role"
							});
							grid_reload();	
							$('#myModal2').modal('hide');
							$('#btn_cancel2').click();	
						}
					},				
					error: function(){
						unblockUI(el1);
					}
				});
			}
		}
	});
	$("#btn_cancel2").click(function () {
   $("#frm_invite").validate().resetForm();
   $("#frm_invite").removeClass("has-error");

});
	/* Delete User */
	$('#user_delete').click(function(){
		$('#myModal3').modal('hide');
		grid.deleteSelectedItem();
		grid.selectRow(0,true,true,true);
		msg_success('User deleted!');	
	})
	
	
	$('#btn_resetpassword').click(function(){
		email = $("#formEmail").val();
		password = $("#frmPassword").val();
		if ($("#frmPassword").val().length < 8) {
			alert("Please enter at least 8 characters.");
			return false;			
		}
		else {		
			$.ajax({
				type: "POST",
				url: "plus/php/ajax.php",
				data: {
					mode:"reset_password",
					email: email,
					password: password
				},
				success: function(msg){					
					if(msg.status=="error")
					{
						msg_error(msg.message);
					}
					else{
						msg_success("Password changed!!!");						
						
					}							
				},
				error: function(){}
			});
			$('#myModal').modal('hide');
			$('#btn_cancel').click();	
		}
	});
	
	
	/* Edit User */
	$('#btn_editsave').click(function(){
		$('#frm_edit').submit();
	})
	
	val = $("#frm_edit").validate({
		rules: {
			formFirstName: {required: true,minlength: 2},
			formLastName: {required: true,minlength: 2},
			formPhone: {required: true,minlength: 2}
		},
		submitHandler: function(form) {
			
			formFirstName = $('#formFirstName').val();
			formLastName = $('#formLastName').val();
			formPhone = $('#formPhone').val();
			formEmail = $('#formEmail').val();
			formUserRole = $('#formUserRole').val();			
			$.ajax({
				type: "POST",
				url: "plus/php/ajax.php",
				data: {
					mode:"user_update",
					first_name: formFirstName,
					last_name: formLastName,
					phone : formPhone,
					user_email: formEmail,
					user_role: formUserRole			
				},
				success: function(msg){					
					if(msg.status=="error"){msg_error(msg.message);}
					else{
						msg_success(msg.message);						
						grid_reload();		
					}
							
				},
				error: function(){}
			});
		
			$('#myModal').modal('hide');
			$('#btn_cancel').click();	
		}
	});	
$(function() {
   $('#frm_invite').keypress(function(e) {
        if(e.which == 13) {
            $('#btn_new').click();
        }
    });

   $("#formUserRole2").on("change",function(){
   		var role = $(this).val();
   		if(role=='Platform-Admin'){
   			$("#formOrg2").hide();
   		}else {
   			$("#formOrg2").show();
   		}
   });

});

$(function() {
   $('#frm_edit').keypress(function(e) {
        if(e.which == 13) {
            jQuery('#btn_editsave').click();
        }
    });
});
	el1 = $('#div-address');
	
	var $zone = $("#formTzone").select2();
	$("#timezone").select2();
	
	grid = new dhtmlXGridObject('grid-box');
	grid.setImagePath("../dhtmlx/imgs/");	
	grid.setHeader("Organizations,,,,,,");
	grid.setInitWidths("*,45,100,100,100,100,75");
	grid.setColAlign("left,left,left,left,left,left,left");     
	grid.setColTypes("ro,img,ro,ro,ro,ro,img");
	grid.enableAlterCss("","");
	grid.enableAutoHeight(true);
	grid.setColumnMinWidth(150,0);
	grid.init();
	grid.load("plus/php/load_org.php",function(){});
	grid.objBox.style.width="103%";
		
	var dp = new dataProcessor("plus/php/load_org.php");
	dp.init(grid);

	dp.defineAction("deleted",function(response){
		action="deleted";
		return true;
	})
	
	grid.attachEvent("onMouseOver", function(id,ind){
		var cellObj = grid.cells(id,ind);
		cellObj.cell.style.cursor="pointer";
	});
	
	grid.attachEvent("onXLE", function(grid_obj,count){
		grid.selectRow(0,true,false,true);
	});
	
	grid.attachEvent("onRowSelect", function(rid,index){
		org_id = rid;
		if(index==6){
			cellval = grid.cells(rid,index).getValue();
			cellval = cellval.split("^");
			if(cellval[1]=="delete"){				
				$('#myModal2').modal('show');
			}  else {
				msg_error("Please reassign Modules before Deleting Organization !");				
			}
		} else if(index==1){
			blockUI(el1);
			cellval = grid.cells(rid,index).getValue();
			cellval = cellval.split("^");
			$.getJSON('plus/php/common.php',{mode:'org_user_login',org_id:org_id}, function(msg) {
				location.href='../dashboard.php';
			});										
		}
		else{
			divs_show();
			blockUI(el1);

			$.getJSON('plus/php/common.php',{mode:'org_getprofile',org_id:org_id}, function(msg) {
				
				$("#org_name").val(msg.org_name);
				$("#org_address").val(msg.org_street);
				$("#org_city").val(msg.org_city);
				$("#org_state").val(msg.org_state);
				$("#org_zip").val(msg.org_zip);
				$("#org_country").val(msg.org_country);
				$("#org_phone").val(msg.org_phone);
				$("#org_key").val(msg.org_key);
				//$("#org_phone").mask("(999) 999-9999? +99");
				$("#token_key").html(msg.token_key);

				$("#user_code").val(msg.user_code);
				$('#user_code').mask('aaa-999');
				
				$("#formLat").val(msg.lat);
				$("#formLng").val(msg.lng);	
				$zone.val(msg.zone).trigger("change");
					
				unblockUI(el1);
			});
		}	
	});
	
	$('#refresh-org').click(function(){
		grid_reload();
	})
	
	function grid_reload(){
		divs_hide();
		grid.clearAll();
		grid.load("plus/php/load_org.php",function(){});
	}
	
	
	/* Create Organization */
	$('#org_create').click(function(){
		$('#frm_org').submit();
	})
	
	val = $("#frm_org").validate({
		rules: {
			new_org_name: {
				required: true,
				minlength: 2
			}
		},
		submitHandler: function(form) {
			org_name = $('#new_org_name').val();
			
			$.ajax({
				type: "POST",
				url: "plus/php/common.php",
				data: {mode:"org_new",name:org_name},
				success: function(msg){
					grid_reload();
				},
				error: function(){}
			});
		
			$('#myModal').modal('hide');
			$('#org_cancel').click();
			$('#new_org_name').resetForm();
			divs_hide();
		}
	});
	
	$("#org_cancel").click(function () {
		$("#frm_org").validate().resetForm();
	});
	
	/* Update Organization */
	val1 = $("#form_address").validate({
		rules: {
			
		},
		errorPlacement: function(error, element) {
			
		},
		submitHandler: function(form) {
			blockUI(el1);
			
			var loc_lat;
			var loc_lng;
					
			var geocoder = new google.maps.Geocoder();
			var address = $("#org_address").val()+","+$("#org_city").val()+","+$("#org_state").val()+", United States";
			
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status === 'OK') {	
			
					loc_lat  = results[0].geometry.location.lat();
					loc_lng = results[0].geometry.location.lng();
					$("#formLat").val(loc_lat);
					$("#formLng").val(loc_lng);
									
					$.ajax({
						type: "POST",
						url: "plus/php/common.php",
						data: {
							mode:'org_updateprofile',
							org_id: org_id,
							org_name:$('#org_name').val(),
							org_address:$('#org_address').val(),
							org_city:$('#org_city').val(),
							org_state:$('#org_state').val(),
							org_zip:$('#org_zip').val(),
							org_country:$('#org_country').val(),
							org_phone:$('#org_phone').val(),
							zone:$("#formTzone").select2("data").id,
							lat: loc_lat,
							lng: loc_lng
						},
						success: function(msg){
							
							unblockUI(el1);
							msg = JSON.parse(msg);
							if(msg.status=="error"){msg_error(msg.message);}
							else{
								msg_success(msg.message);
								grid.cellById(org_id,0).setValue($('#org_name').val());
							}
						},
						error: function(){
						}
					});		
					
				}
				else{
					loc_lat = 0.0;
					loc_lng = 0.0;
			
				}
			});	
					
		}
	});	

	val2 = $("#code_generate").validate({
		rules: {
		
		},
		errorPlacement: function(error, element) {
			
		},
		submitHandler: function(form) {
			el2 = $('#code_generate');		
			blockUI(el2);
			user_code = $('#user_code').val();
			orgkey =  $('#org_key').val();
			if (orgkey=="") {
				alert("Please select Organization");
				unblockUI(el1);
				return false;
			}	
			$.ajax({
				type: "POST",
				url: "plus/php/common.php",
				data: {
					mode:"code_generate",
					user_code:user_code,
					orgkey: orgkey				
				},
				success: function(msg){
					unblockUI(el2);
					msg = JSON.parse(msg);
					if(msg.status=="error"){msg_error(msg.message);}
					else{
						msg_success(msg.message);
					}
				},				
				error: function(){
					unblockUI(el2);
				}
			});
			
			
		}
	});	

	/* Delete Organization */
	$('#delete_confirm').click(function(){
		if($('#org_to_delete').val() == grid.cellById(org_id,0).getValue())
		{
			divs_hide();
			$('#myModal2').modal('hide');
			grid.deleteSelectedItem();
		}
		else{
			msg_error("Organization name entered does not match !");
		}
		$("#org_to_delete").val('');
	})
	
	function divs_hide(){
		$('#div-address').hide();
		$('#div-code').hide();
	}
	
	function divs_show(){
		$('#div-address').show();
		$('#div-code').show();
	}
	
	$(function() {
	  	$('#user_code').mask('aaa-999');    
	});
	
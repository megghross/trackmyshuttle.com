divs_hide();
el1 = $('#div-assign');

//$("#new_formID").mask("99a999999999");
grid = new dhtmlXGridObject('grid-box');
grid.setImagePath("../dhtmlx/imgs/");	
grid.setHeader(",Serial,ICCID,Org,,,");
grid.setColSorting("str,str,str,str,str,str,str"); 
grid.setInitWidths("40,100,*,100,50,75,150");
grid.setColAlign("left,left,left,left,center,left,left");     
grid.setColTypes("img,ro,ro,ro,img,img,ro");

grid.enablePaging(true,20,10,"recinfoArea",true);
grid.setPagingSkin("bricks");
		
grid.enableAlterCss("","");
grid.enableAutoHeight(true);
grid.setColumnMinWidth(100,1);
grid.init();
grid.load("plus/php/load_inven.php",function(){});
grid.objBox.style.width="103%";
	
var dp = new dataProcessor("plus/php/load_inven.php");
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
	rowid = rid;
	if(index==4){
		cellval = grid.cells(rid,index).getValue();
		cellval = cellval.split("^");
		if(cellval[1]=="erase"){
			$('#myModal3').modal('show');
		}
	}
	else if(index==5){
		cellval = grid.cells(rid,index).getValue();
		cellval = cellval.split("^");
		if(cellval[1]=="delete"){
			$('#myModal2').modal('show');
		}
	}
	else
	{
		divs_show();
		blockUI(el1); 
		$.getJSON('plus/php/common.php',{mode:'device_config',device_id:rowid}, function(msg) {
			if(msg.status=="success"){
				
				org_key = msg.org_key;
				serial  =  msg.serial_id;
				$("#formID").val(serial);
				
				$.getJSON('plus/php/common.php',{mode:'device_orglist'}, function(data) {
					$("#selO").select2({
						placeholder: "Select Organization",
						allowClear: true,
						data: data
					}).select2('val',[org_key]);
					
					unblockUI(el1); 
										
				});
			}
			else{
				location.href = "404.php";
			}
		});
	}
});

/* Module Assignment Update */
$('#btn-save').on('click', function(){  
    $('#form1').valid();  
	$('#form1').submit(); 
});

$("#form1").validate({
	rules: {
	},
	errorPlacement: function(error, element) {
	},
	submitHandler: function (form) { 	
		if (!$('#selO').val()){
			orgid='0'; 
			msg_error("Please Select Organization");
		}
		else{
			
			orgkey = $("#selO").select2("data").id;
			loc_key = 0;
			blockUI(el1);	
			
			$.ajax({
				type: "POST",
				url: "plus/php/common.php",                                    
				data: {
					mode:'device_assignment',
					org_key:orgkey,
					loc_key:loc_key,
					device_id:rowid
				},
				success: function(msg){
					unblockUI(el1);	
					msg = JSON.parse(msg);
					
					if(msg.status=="error"){msg_error(msg.message);}
					else{msg_success(msg.message);}
					grid.cellById(rowid,2).setValue($("#selO").select2("data").text);

				},
				error: function(){
				}
			});
		}
	}		
});

/* Refresh List */
$('#refresh-list').click(function(){
	grid_reload();
})

function grid_reload(){
	divs_hide();
	//grid.clearAll();
	//grid.load("plus/php/load_inven.php",function(){});
	grid.clearAndLoad("plus/php/load_inven.php?search="+$("#module-search").val().toLowerCase());
}

/* Add New Module */
$('#add-mod').on('click', function(){  
   $.getJSON('plus/php/common.php',{mode:'device_orglist'}, function(data) {					
		$("#new_selO").select2({
			placeholder: "Select Organization",
			allowClear: true,
			data: data
		});
   })
});

$('#mod_cancel').on('click', function(){  
	$("#new_formID").val('');
	$("#new_iccID").val('');
	$("#formType").val(null).trigger("change");
	$("#new_selO").val(null).trigger("change");
});


$('#mod_create').on('click', function(){  
    $('#frm_mod').valid();  
	$('#frm_mod').submit(); 
});

$("#frm_mod").validate({
	rules: {
		new_formID: {required: true},
		new_iccID: {required: true}
	},
	submitHandler: function (form) { 	
		if (!$('#new_selO').val()){
			new_orgid='0'; 
			msg_error("Please Select Organization");
		}
		else{
			new_serial = $("#new_formID").val().toUpperCase();
			new_iccid = $("#new_iccID").val();
			new_orgkey = $("#new_selO").select2("data").id;
			
			$.ajax({
				type: "POST",
				url: "plus/php/common.php",
				data: {mode:"device_new",new_serial:new_serial, new_iccid:new_iccid, new_orgkey:new_orgkey},
				success: function(msg){
					grid_reload();
					msg = JSON.parse(msg);	
					if (msg.status=='error')					
						msg_error(msg.message);
					else
						msg_success(msg.message);
					$('#myModal').modal('hide');
					$('#mod_cancel').click();
				},
				error: function(){
					
				}
			});			
		}
	}
	
});

$("#mod_cancel").click(function () {
$("#frm_mod").validate().resetForm();
$("#frm_mod").removeClass("has-error");

});
         
/* Module Delete */
$('#delete_confirm').click(function(){
	if($('#mod_to_delete').val() == grid.cellById(rowid,1).getValue())
	{
		divs_hide();
		$('#myModal2').modal('hide');
		$.ajax({
			type: "POST",
			url: "plus/php/common.php",
			data: {
				mode:'devicedata_delete',
				device_id:rowid
			},
			success: function(msg){
				msg = JSON.parse(msg);
				if(msg.status=="error"){
					msg_error(msg.message);
				}
				else{
					msg_success(msg.message);
					grid.deleteSelectedItem();			
				}
			},
			error: function(){
			}
		});
				
	}
	else{
		msg_error("Serial entered does not match !");
	}
	$("#mod_to_delete").val('');
})

/* Module Erase */
$('#erase_confirm').click(function(){
	if($('#mod_to_erase').val() == grid.cellById(rowid,1).getValue())
	{
		divs_hide();
		$('#myModal3').modal('hide');
		$.ajax({
			type: "POST",
			url: "plus/php/common.php",
			data: {
				mode:'devicedata_clear',
				device_id:rowid
			},
			success: function(msg){
				msg = JSON.parse(msg);
				if(msg.status=="error"){msg_error(msg.message);}
				else{
					msg_success(msg.message);
				}
			},
			error: function(){
				
			}
		});	
	}
	else{
		msg_error("Serial entered does not match !");
	}
	$("#mod_to_erase").val('');
})

/* Device Search */
$("#module-search" ).keyup(function (e){
	/*
	grid.forEachRow(function(rid){
		deleteRow = true;
		grid.forEachCell(rid, function(cellObj,ind){
			if (cellObj.getValue().toLowerCase().indexOf($("#module-search").val().toLowerCase()) > -1) {
				deleteRow = false;
			}
		})
		if (deleteRow == true) {grid.setRowHidden(rid,true);}
		else{grid.setRowHidden(rid,false);}
	})*/
	grid.clearAndLoad("plus/php/load_inven.php?search="+$("#module-search").val().toLowerCase());
	
});

function divs_hide(){
	$('#div-assign').hide();
}

function divs_show(){
	$('#div-assign').show();
}



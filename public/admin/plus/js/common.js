Messenger.options = {
	extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
	theme: 'air'
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).ready(function() {
	
})

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
    }
    return "";
}
function checkCookie(cname) {
    var cookie = getCookie(cname);
    if (cookie != "") {return true;}
	else {return false;}
}
function deleteCookie(cname) {
	document.cookie = cname + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
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

function msg_success(msg){
	Messenger().hideAll();
	Messenger().post({message: msg, type: "success", showCloseButton: true});
}
function msg_error(msg){
	Messenger().hideAll();
	Messenger().post({message: msg, type: "error", showCloseButton: true});
}

function getDate() {
   var date = new Date();
   var yyyy = date.getFullYear().toString();
   var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based
   var dd  = date.getDate().toString();
   return yyyy +"-"+ (mm[1]?mm:"0"+mm[0]) +"-"+ (dd[1]?dd:"0"+dd[0]); // padding
}

function set_menuview(){
	if(checkCookie("menuview")==true){
		if (getCookie("menuview")=="mini") {
			$('body').addClass('grey');
			$('#main-menu').addClass('mini');
			$('.page-content').addClass('condensed');
			$('.scrollup').addClass('to-edge');
			$('.header-seperation').hide();
			$('.footer-widget').hide();
		}
		else{
			$('body').removeClass('grey');
			$('#main-menu').removeClass('mini');
			$('.page-content').removeClass('condensed');
			$('.scrollup').removeClass('to-edge');
			$('.header-seperation').show();
			//Bug fix - In high resolution screen it leaves a white margin
			$('.header-seperation').css('height', '61px');
			$('.footer-widget').show();
		}
	}
}

function getHeight(offset){
	h=(window.innerHeight
	|| document.documentElement.clientHeight
	|| document.body.clientHeight)-offset;
}

function slider_proto(){
	$.fn.slider.Constructor.prototype.disable = function () {
		this.picker.off();
	}
	$.fn.slider.Constructor.prototype.enable = function () {
		if (this.touchCapable) {
			// Touch: Bind touch events:
			this.picker.on({
				touchstart: $.proxy(this.mousedown, this)
			});
		} else {
			this.picker.on({
				mousedown: $.proxy(this.mousedown, this)
			});
		}
	}
}

$(function() {
  // Handler for .ready() called.
  $('#btnUpdatePassword').click(function(){
		$('#frm_reset_password').submit();
  });
  
  $(".modal").on('shown.bs.modal', function () {
		$(this).find("input:visible:first").focus();
   });
	
  val = $("#frm_reset_password").validate({
		rules: {
			pass_curr:{required: true},
			pass_new: {required: true, minlength: 8},
			pass_new_again: {required: true, equalTo: "#pass_new"}
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "pass_new"){error.insertAfter('#div_pass_new')}
			else if (element.attr("name") == "pass_new_again"){error.insertAfter('#div_pass_new2')}
		},
		submitHandler: function(form) {
			el1 = $('#frm_reset_password');
			blockUI(el1);
			$.ajax({
				type: "POST",
				url: "plus/php/ajax.php",
				data: {mode:"reset",pass_curr:$('#pass_curr').val(),pass_new:$('#pass_new').val()},
				success: function(msg){
					unblockUI(el1);
					msg = JSON.parse(msg);
					if(msg.status=="error"){
						Messenger().post({message: msg.message, type: 'error'});
					}
					else{
						Messenger().post({message: msg.message, type: 'success'});
						$('#btn_cancel_reset').click();			
					}	
				},
				error: function(){
					$('#btn_cancel_reset').click();
				}
			});
		}
	});
	
  
});

set_menuview();


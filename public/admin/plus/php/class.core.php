<?php
/**

 */
class core {
	/**
	* Core constructor
	*/
	function __construct() {
		//_set_error_handler( array(&$this, 'throwError'));
	}

	/**
	* Create a new DB connection, or continues an existing
	* one if present
	*/
	function &db(){
		$dbConnect = new dbconnector;
		return $dbConnect;
	}
	
	/* Severity is 1 or 2 (fatal or non-fatal) */
	function errormsg($message, $severity = 1){
		$this->throwError($severity, $message, basename($_SERVER['PHP_SELF']), null, null);
		if ($severity == 1){
			die();
		}
	}
	
	/**
	* For internal system errors
	*/
	function throwError ($error_type, $error_msg, $error_file, $error_line = null, $error_context = null) {
		// Display messages
		if (error_reporting() AND $error_type != E_NOTICE){
			echo '<table cellpadding="2" cellspacing="0" class="alert_red"><tr><td>';
			echo '<p>Sorry, an error occurred.</p>';
			if ($error_file != null){
				echo "<p>FILE: $error_file, LINE: $error_line</p>";
			}
			echo '<p>ERROR: '.$error_msg.'</p>';
			//print_r($error_context);
			echo '</td></tr></table>';
		}
	}
}

/**
* set_error_handler that works with all versions of php 
* Source: http://mojavi.org/forum/viewtopic.php?t=57&sid=896a45f2b500927c634d3f8d4fec67d4
*/
function _set_error_handler($arg) 
{ 
    if (is_array($arg)) { 
        if (phpversion() >= '4.3.0') { 
            set_error_handler($arg); 
        } else { 
            $GLOBALS['_error_handler_hook_obj'] =& $arg[0]; 
            $GLOBALS['_error_handler_hook_method'] = $arg[1]; 
            set_error_handler('error_handler_callback'); 
        } 
    } else if (is_string($arg)) { 
        set_error_handler($arg); 
    } else { 
        trigger_error("Wrong argument type for _set_error_handler", E_USER_ERROR); 
    } 
} 

?>
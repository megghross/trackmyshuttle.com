<?php
/**
 * Script Loader
 *
 * @package Megghross
 */

$GLOBALS['uni_styles']  = array();
$GLOBALS['uni_scripts'] = array();
$GLOBALS['uni_styles_output']  = array();
$GLOBALS['uni_scripts_output'] = array();

// Styles - Register and Enqueue ----------------------
function register_style( $handle, $src = '', $priority = 0 ) {
	$GLOBALS['uni_styles'][$priority][$handle] = $src;
}

function enqueue_style( $handle, $src = '', $priority = 0 ) {
	if( isset($GLOBALS['uni_styles'][$priority][$handle]) ) {
		$GLOBALS['uni_styles_output'][$priority][$handle] = $GLOBALS['uni_styles'][$priority][$handle];
	}
	else {
		$GLOBALS['uni_styles_output'][$priority][$handle] = $src;
	}
}

// Scripts - Register and Enqueue ----------------------
function register_script( $handle, $src = '', $priority = 0 ) {
	$GLOBALS['uni_scripts'][$priority][$handle] = $src;
}

function enqueue_script( $handle, $src = '', $priority = 0 ) {
	if( isset($GLOBALS['uni_scripts'][$priority][$handle]) ) {
		$GLOBALS['uni_scripts_output'][$priority][$handle] = $GLOBALS['uni_scripts'][$priority][$handle];
	}
	else {
		$GLOBALS['uni_scripts_output'][$priority][$handle] = $src;
	}
}

// Output Styles & Scripts ----------------------
function print_sources( $type ) {
	$template = '';
	switch($type) {
		case 'styles':
			$template = '<link href="%s" rel="stylesheet" type="text/css"/>';
			break;
		case 'scripts':
			$template = '<script src="%s"></script>';
			break;
	}

	$tabs = '';
  ksort( $GLOBALS["uni_{$type}_output"] );

	foreach( $GLOBALS["uni_{$type}_output"] as $priority ) {
    foreach( $priority as $src => $src_val ) {
      echo $tabs . sprintf( $template, $src_val ) . "\n";
      $tabs = "\t";
    }
	}
}
function print_styles() { print_sources( 'styles' ); }
function print_scripts() { print_sources( 'scripts' ); }

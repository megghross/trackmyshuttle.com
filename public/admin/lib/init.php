<?php
/**
 * Init the engine
 *
 * @package Megghross
 */

define('ABSPATH', dirname(dirname(__FILE__)) . '/');

// Globals -------------------------------------------------

$demos = array(
  'default' => array(
    'body_class' => 'header-dark sidebar-light sidebar-expand',
    'sidebar_file' => 'partials/sidebar',
    'nav_file' => 'partials/nav'
  ),
  'real-estate' => array(
    'body_class' => 'header-dark sidebar-light sidebar-collapse',
    'sidebar_file' => 'partials/sidebar',
    'nav_file' => 'partials/nav'
  ),
  'job-board' => array(
    'body_class' => 'header-dark sidebar-light sidebar-expand',
    'sidebar_file' => 'partials/sidebar',
    'nav_file' => 'partials/nav-jobs'
  ),
  'default-rtl' => array(
    'body_class' => 'header-dark sidebar-light sidebar-expand rtl',
    'sidebar_file' => 'partials/sidebar',
    'nav_file' => 'partials/nav-rtl'
  ),
  'lms' => array(
    'body_class' => 'header-dark sidebar-light sidebar-collapse',
    'sidebar_file' => 'partials/sidebar',
    'nav_file' => 'partials/nav'
  ),
);

function activate_demo($name, $demo_array) {
  $GLOBALS['body_class'] = $demo_array[$name]['body_class'];;
  $GLOBALS['sidebar_file'] = $demo_array[$name]['sidebar_file'];
  $GLOBALS['nav_file'] = $demo_array[$name]['nav_file'];
}

$GLOBALS['body_classes'] = array();
$GLOBALS['enable_livereload'] = true;
$GLOBALS['enable_relative_nav'] = false;
$GLOBALS['disable_footer'] = false;
activate_demo( 'default', $demos );


// Includes -------------------------------------------------
include_once 'functions.php';
include_once 'script-loader.php';
include_once 'template-tags.php';

// Vendors
$js = 'assets/js';
$css = 'assets/css';
$vendors = 'assets/vendors';
$node = 'node_modules';

// CSS

enqueue_google_font_files( array(
  'Montserrat' => array(200,300,400,500,600),
  'Roboto' => array(400)
));

enqueue_style( 'material-icons', $vendors . '/material-icons/material-icons.css');
enqueue_style( 'linea-icons', $vendors . '/linea-icons/styles.css');
enqueue_style( 'social-icons', $vendors . '/mono-social-icons/monosocialiconsfont.css');
enqueue_style( 'feather-icons', $vendors . '/feather-icons/feather.css');
register_style( 'fontawesome-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
register_style( 'sweet-alert', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css');
register_style( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );
register_style( 'datatables', 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css' );
register_style( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css' );
register_style( 'toastr', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.1/jquery.toast.min.css' );
register_style( 'bootstrap-table', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css' );
register_style( 'tablesaw', $node . '/tablesaw/dist/tablesaw.css' );
register_style( 'footable', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.4/footable.bootstrap.min.css' );
register_style( 'rangeslider', 'https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.7/css/ion.rangeSlider.min.css' );
register_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css' );
register_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css' );
register_style( 'dropzone', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.0.1/min/dropzone.min.css' );
register_style( 'dropzone-basic', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.0.1/min/basic.min.css' );
register_style( 'clockpicker', 'https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css');
register_style( 'colorpicker', 'https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css');
register_style( 'daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css');
register_style( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css');
register_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
register_style( 'selectpicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css');
register_style( 'switchery', 'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css');
register_style( 'tagsinput', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css');
register_style( 'touchspin', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.css');
register_style( 'multiselect', 'https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css');
register_style( 'bootstrap-wysiwyg', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.min.css');
register_style( 'morris-css', 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css');
register_style( 'weather-icons', $vendors . '/weather-icons-master/weather-icons.min.css');
register_style( 'weather-icons-wind', $vendors . '/weather-icons-master/weather-icons-wind.min.css');
register_style( 'nestable', $node . '/nestable2/jquery.nestable.css');
register_style( 'mediaelement', 'https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.1.3/mediaelementplayer.min.css' );
enqueue_style( 'scrollbar-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css' );
enqueue_style( 'base', $css . '/style.css', 99 );

// JS
enqueue_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', 0 );
register_script( 'jqueryui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', 0 );
enqueue_script( 'popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js' );
enqueue_script( 'bootstrap', $js . '/bootstrap.min.js' );
register_script( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js' );
register_script( 'validator', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.77/jquery.form-validator.min.js' );
register_script( 'moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js' );
register_script( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js' );
register_script( 'underscore', 'https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js' );
register_script( 'clndr', 'https://cdnjs.cloudflare.com/ajax/libs/clndr/1.4.7/clndr.min.js' );
register_script( 'toastr', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.1/jquery.toast.min.js' );
register_script( 'toastrs', $js . '/toastrs.js' );
register_script( 'datatables', 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js' );
register_script( 'bootstrap-table', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js' );
register_script( 'tablesaw', $node . '/tablesaw/dist/tablesaw.jquery.js' );
register_script( 'tablesaw-init', $node . '/tablesaw/dist/tablesaw-init.js' );
register_script( 'footable', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.4/footable.min.js' );
register_script( 'tabledit', $node . '/jquery-tabledit/jquery.tabledit.min.js');
register_script( 'rangeslider', 'https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.7/js/ion.rangeSlider.min.js');
register_script( 'nestable', $node . '/nestable2/jquery.nestable.js');
register_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js');
register_script( 'charts-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js' );
register_script( 'chart-js-bundle', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js' );
register_script( 'chart-js-utility', $vendors . '/charts/utils.js' );
register_script( 'knob', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js' );
register_script( 'knob-excanvas', $vendors . '/charts/excanvas.js' );
register_script( 'flot-charts', 'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js' );
register_script( 'flot-time-plugin', 'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.min.js' );
register_script( 'morris-charts', 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js' );
register_script( 'morris-raphael', 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js' );
register_script( 'sparkline', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js' );
register_script( 'countup', 'https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js' );
register_script( 'waypoints', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js' );
register_script( 'maskedinput', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js' );
register_script( 'dropzone', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.0.1/min/dropzone.min.js' );
register_script( 'jquery-circle-progress', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js' );
register_script( 'clockpicker', 'https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js' );
register_script( 'colorpicker', 'https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js' );
register_script( 'daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.js' );
register_script( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js' );
register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js' );
register_script( 'selectpicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js' );
register_script( 'switchery', 'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js' );
register_script( 'tagsinput', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js' );
register_script( 'touchspin', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.js' );
register_script( 'multiselect', 'https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js' );
register_script( 'tinymce', 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/tinymce.min.js' );
register_script( 'tinymce.jquery', 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/jquery.tinymce.min.js' );
register_script( 'tinymce.theme', 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/themes/inlite/theme.min.js' );
register_script( 'bootstrap-wysiwyg', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.all.min.js' );
register_script( 'mithril', 'https://cdnjs.cloudflare.com/ajax/libs/mithril/1.1.1/mithril.js' );
register_script( 'dragula', 'https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js' );
register_script( 'marked', 'https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.6/marked.min.js' );
register_script( 'theme-widgets', $vendors . '/theme-widgets/widgets.js' );
register_script( 'mediaelement', 'https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.1.3/mediaelementplayer.min.js');
enqueue_script( 'metismenu', 'https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js' );
enqueue_script( 'scrollbar-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js' );
register_script( 'sweet-alert', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js' );
enqueue_script( 'theme', $js . '/theme.js', 99);
enqueue_script( 'custom', $js . '/custom.js', 99 );
enqueue_script( 'svg', $js . '/svg.js', 99 );
if( $GLOBALS['enable_livereload'] )
enqueue_script( 'livereload', 'http://localhost:35729/livereload.js', 99);

// GMap Includes
register_script( 'gmaps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCO9v3UBJDfOTFC_wcIK7UzhLRjBWQIJ9M');
register_script( 'gmaps', 'https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js' );

// JQVMap Includes
register_style( 'jqvmaps-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css' );
register_script( 'jqvmaps-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js' );
register_script( 'jqvmaps-algeria', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js' );
register_script( 'jqvmaps-argentina', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.argentina.js' );
register_script( 'jqvmaps-brazil', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.brazil.js' );
register_script( 'jqvmaps-canada', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.canada.js' );
register_script( 'jqvmaps-europe', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.europe.js' );
register_script( 'jqvmaps-france', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.france.js' );
register_script( 'jqvmaps-germany', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.germany.js' );
register_script( 'jqvmaps-greece', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.greece.js' );
register_script( 'jqvmaps-iran', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.iran.js' );
register_script( 'jqvmaps-iraq', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.iraq.js' );
register_script( 'jqvmaps-russia', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.russia.js' );
register_script( 'jqvmaps-tunisia', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.tunisia.js' );
register_script( 'jqvmaps-turkey', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.turkey.js' );
register_script( 'jqvmaps-usa', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js' );
register_script( 'jqvmaps-world', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.world.js' );
register_script( 'jqvmaps-africa', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/continents/jquery.vmap.africa.js' );
register_script( 'jqvmaps-asia', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/continents/jquery.vmap.asia.js' );
register_script( 'jqvmaps-australia', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/continents/jquery.vmap.australia.js' );
register_script( 'jqvmaps-north-america', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/continents/jquery.vmap.north-america.js' );
register_script( 'jqvmaps-south-america', 'https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/continents/jquery.vmap.south-america.js' );


// RoutePlanner Includes
register_style( 'rp-bootstrap', $vendors . '/route-planner/lib/bootstrap-3.0.0/css/bootstrap.min.css');
register_style( 'rp-whhg', $vendors . '/route-planner/lib/whhg-font/css/whhg.css');
register_style( 'rp-s', $vendors . '/route-planner/css/s.css');
register_script( 'rp-require', $vendors . '/route-planner/lib/require-2.1.8.min.js');
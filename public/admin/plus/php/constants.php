<?php
/**
 * @see
 */
namespace trackmyshuttle;
// Database Connection Constants

define('DBHOST', '159.65.228.181');
define('DBUSER', 'dbuclients');
define('DBPASS', 'Vclients20755!v');
define('DBNAME', 'dbclients_trackmyshuttle');
/*
define('DBHOST', 'server');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'tms_new');*/


define("KEY","AKIAI7UGADZN6KTU5DZA");
define("SECRETE","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");

//AWS CREDENTIAL

define("SITEPATH", "/var/www/vhosts/brainyloft.com/client.brainyloft.com/staging/trackmyshuttle/public");
//define("SITEPATH", "C:/xampp/htdocs/trackmyshuttle/public");

// Turn ON and OFF debug mode
define('DEBUG', 0);

// Set timezone
date_default_timezone_set('America/Chicago');

// MAINTENANCE MODE
// Turn ON and OFF maintenance mode
define('MAINTENANCE_MODE', 0);

// MISC
// Application Version
define('VERSION', '1.1');
?>
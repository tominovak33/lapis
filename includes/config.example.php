<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 20:17
 */


/*---------------------------
/ Development settings
/---------------------------*/
error_reporting(E_ALL);
ini_set('display_errors', 'On'); //Set in php ini file when I have time
define('LOG_DB_QUERIES', true); //Log sql queries to file?

/*---------------------------
/ Database constants
/---------------------------*/
define('DB_HOST', 'localhost');
define('DB_USER', '<username>');
define('DB_PASSWORD', '<password>');
define('DB_NAME', '<db_name>');
define('FRONTEND_DOMAIN', '<frontend_domain>');


/*---------------------------
/ Other Settings
/---------------------------*/
//Allow C.O.R.S. between API and frontend application
header("Access-Control-Allow-Origin:" .FRONTEND_DOMAIN );

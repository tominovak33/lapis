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
define('DB_HOST', ' ');
define('DB_USER', ' ');
define('DB_PASSWORD', ' ');
define('DB_NAME', ' ');
define('FRONTEND_DOMAIN', '*');

/*
/ Other Database constants
/---------------------------*/
define('DB_API_USER_TABLE', ' ');
define('DB_API_TOKEN_TABLE', ' ');
define('PASSWORD_EXTRA_SALT', ' ');
/*---------------------------
/ Content Settings
/---------------------------*/
define('CONTENT_TABLE_NAME' , ' ');
// Sadly with PHP < 5.6 constants cannot be arrays.
// So to get around this, serialise the array and put _ARRAY at the end of the constants name name (just for ease of use)
// Then always unserialise any constants that have _ARRAY at the end of their name before using them.
define ("CONTENT_STRICT_PROPERTIES_ARRAY", serialize (array('id', 'author_id')));

define ("QUERY_OPTIONS_ARRAY", serialize (array('LIMIT', 'ORDER', 'ORDER_BY', 'CONTENT')) ); // Array of words that cannot be prameters of the content as they are for query options

define('API_USERS_TABLE' , ' ');

define('RESTRICTED_TABLES' , serialize(array(' '))); // A list of tables that the end users cannot simply query data from or add to

define('USE_DEFAULT_QUERY_RESTRICTIONS' , true);

define('DEFAULT_CONTENT_FIELDS', serialize(array('owner_id', 'public')));

/*---------------------------
/ Other Settings
/---------------------------*/
//Allow C.O.R.S. between API and frontend application
header("Access-Control-Allow-Origin:" .FRONTEND_DOMAIN );
header("Access-Control-Allow-Headers:" .'x-auth-password, x-auth-username, x-auth-token');


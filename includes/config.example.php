<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 20:17
 */

test
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
/ Content Settings
/---------------------------*/
define('CONTENT_TABLE_NAME' , 'patterns');
// Sadly with PHP < 5.6 constants cannot be arrays.
// So to get around this, serialise the array and put _ARRAY at the end of the constants name name (just for ease of use)
// Then always unserialise any constants that have _ARRAY at the end of their name before using them.
define ("CONTENT_STRICT_PROPERTIES_ARRAY", serialize (array('id', 'author_id')));

define ("QUERY_OPTIONS_ARRAY", serialize (array('LIMIT', 'ORDER', 'ORDER_BY', 'CONTENT')) ); // Array of words that cannot be prameters of the content as they are for query options

define('API_USERS_TABLE' , 'api_users');

define('RESTRICTED_TABLES' , serialize(array('api_users'))); // A list of tables that the end users cannot simply query data from or add to

define('DEFAULT_CONTENT_FIELDS', serialize(array('owner_id', 'public')));

/*---------------------------
/ Other Settings
/---------------------------*/
//Allow C.O.R.S. between API and frontend application
header("Access-Control-Allow-Origin:" .FRONTEND_DOMAIN );

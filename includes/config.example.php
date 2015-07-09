<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 20:17
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'username-here');
define('DB_PASSWORD', 'password-here');
define('DB_NAME', 'pattern_db');

define('FRONTEND_DOMAIN', 'http://frontend-lapis.local.dev');

//Allow C.O.R.S. between API and frontend application
header("Access-Control-Allow-Origin: FRONTEND_DOMAIN");

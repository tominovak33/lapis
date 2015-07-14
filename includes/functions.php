<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 14/07/15
 * Time: 22:07
 */

/*-------------------------------------------------------------
/ Shared functions - Put non specific utility functions here
/-------------------------------------------------------------*/

/*
 * Returns the request type of the request (based on the first query param as the .htaccess file takes requests
 * such as http://lapis.local.dev/POST/?foo=bar and passes it to  http://lapis.local.dev/index.php with GET['request'] set to "POST"
 */
function get_request_type() {
    $request_url = rtrim($_GET['request'], '/');
    array_shift($_GET); // remove the request type so we can loop through all actual params
    $exploded_url = explode('/', $request_url);
    $request_type = $exploded_url[0];
    return strtoupper($request_type);
}

<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 14/07/15
 * Time: 22:07
 */


/*-------------------------------------------------------------
/ Development functions - Put useful development functions here
/-------------------------------------------------------------*/

function die_dump($data) {
    echo '<pre>';
    var_dump($data);
    die;
}

/*-------------------------------------------------------------
/ Shared functions - Put non specific utility functions here
/-------------------------------------------------------------*/

/*
 * Returns the request type of the request (based on the first query param as the .htaccess file takes requests
 * such as http://lapis.local.dev/POST/?foo=bar and passes it to  http://lapis.local.dev/index.php with GET['request'] set to "POST"
 */
function get_request_type() {
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        return 'OPTIONS';
    }
    $request_url = rtrim($_GET['request'], '/');
    array_shift($_GET); // remove the request type so we can loop through all actual params
    $exploded_url = explode('/', $request_url);
    $request_type = $exploded_url[0];
    return strtoupper($request_type);
}

function get_request_user() {
    $userID = checkToken();
    if ($userID != false) {
        $user = new User($userID);
        $user->get_user();
        return $user;
    }
    return false;
}


/*
 * Sets up the response array with standard things such as the timestamp, url etc
 */
function response_header_setup($startTime) {
    header("lapis-request-start-time: " .$_SERVER['REQUEST_TIME']);
    header("lapis-process-start-time: " .$startTime);
    header("lapis-request-address: " . $_SERVER['SERVER_ADDR']);

    header("lapis-software: " . $_SERVER['SERVER_SOFTWARE']);
    header("lapis-request-method: " . $_SERVER['REQUEST_METHOD']);
}

/*
 * Adds the time when server was finished processing the request and the time taken to deal with the response
 */
function response_stats_headers($startTime) {
    $time = microtime(true);
    header("lapis-finish-time: " . $time);
    header("lapis-process-time: " . ($time - $startTime));
    header("lapis-database-queries: " . $GLOBALS['db_query_count']);
}

function query_log($query) {
    if (LOG_DB_QUERIES == true) {
        $query = str_replace("\n", ' ' , $query);

        for ($counter = 1; $counter <= 50; $counter++) {
            $query = str_replace("  ", ' ' , $query); //Replace the massive spaces that my sprintf functions insert 
        }

        if (!file_exists('logs/query_log.txt')) {
            $tmp = fopen("logs/query_log.txt", "w");
            chmod('logs/query_log.txt', 0755);
            fclose($tmp);
        }
        $query_log_file = fopen("logs/query_log.txt", "a");
        fwrite($query_log_file,date("Y-m-d H:i:s") . ' - ');
        fwrite($query_log_file, $query . "\n");
        fclose($query_log_file);
    }
}

function get_query_options() {
    $available_options = array('ORDER_BY', 'ORDER', 'LIMIT');
    $options = [];

    foreach ($available_options as $option_name) {
        if (isset($_GET[$option_name])) {
            $option['name'] = $option_name;
            $option['value'] = $_GET[$option_name];

            unset($_GET[$option_name]); //Get rid of the option so it doesn't interfere later

            $options[] = $option;
        }
    }

    return $options;
}

/*
Use this if an error needs to be returned immediately
*/
function return_error_response() {

    response_stats_headers(); // Create the headers that refer to statistics of the request

    $response['error'] = $GLOBALS["error"];
    $response['error_message'] = $GLOBALS["error"];
    
    echo json_encode($response, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed to make the output more readable
    die();
}


function unauthorised() {
    header('HTTP/1.0 401 Unauthorized');
    exit();
    die;
}
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
    $request_url = rtrim($_GET['request'], '/');
    array_shift($_GET); // remove the request type so we can loop through all actual params
    $exploded_url = explode('/', $request_url);
    $request_type = $exploded_url[0];
    return strtoupper($request_type);
}

/*
 * Sets up the response array with standard things such as the timestamp, url etc
 */
function response_setup() {
    $response = array();

    $response['request_time'] = $_SERVER['REQUEST_TIME'];
    $response['address'] = $_SERVER['SERVER_ADDR'];
    $response['finish_time'] = $_SERVER['REQUEST_METHOD'];
    $response['process_time'] = $_SERVER['REQUEST_METHOD'];
    $response['database_queries'] = 0;

    $response['software'] = $_SERVER['SERVER_SOFTWARE'];
    $response['request_method'] = $_SERVER['REQUEST_METHOD'];

    return $response;
}

/*
 * Adds the time when server was finished processing the request and the time taken to deal with the response
 */
function response_time($response) {
    $response['finish_time'] = time();
    $response['process_time'] = $response['finish_time'] - $response['request_time'];
    $response['database_queries'] = $GLOBALS['db_query_count'];

    return $response;
}

function query_log($query) {
    if (LOG_DB_QUERIES == true) {
        $query = str_replace("\n", ' ' , $query);
        $query = str_replace("             ", ' ' , $query); //Replace the massive spaces that my sprintf functions insert

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

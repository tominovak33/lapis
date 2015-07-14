<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 20:10
 */

header("Content-Type: application/json");

require 'includes/config.php';
require 'includes/database.php';
require 'includes/functions.php';
require 'includes/content/content.php';

$request_type = get_request_type();
$response = response_setup();

$content = new Content();
$keys = [];

foreach ($_GET as $key => $value) {
    $keys [] = $key;
    $content->set_parameter($key, $value);
}

if ($request_type == 'GET') {
    $response['data'] = $content->search_by($keys);
}
elseif ($request_type == 'POST') {
    $response = array();
    $response['error'] = "Unknown method";
    $response['error_message'] = "POST methods will be available soon";
}
else {
    $response = array();
    $response['error'] = "Unknown method";
    $response['error_message'] = "Please use a valid API method";
}

$response = response_time($response);

echo json_encode($response, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed

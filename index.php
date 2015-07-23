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

response_header_setup();

$request_type = get_request_type();
$response = array();

$content = new Content();
$keys = [];

switch ($request_type) {
    case 'GET' :
        $options = get_query_options();

        foreach ($options as $option) {
            $content->set_query_options($option['name'], $option['value']);
        }

        foreach ($_GET as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value);
        }
        $response['data'] = $content->search_by($keys);
        break;

    case 'POST':
        foreach ($_POST as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value);
        }
        $response['data'] = $content->insert($keys);
        break;

    case 'OPTIONS':
        $response['data'] = $content->options();
        break;

    default:
        http_response_code(405);
        header("Access-Control-Allow-Methods: GET, POST");
        $response['error'] = "Unknown method";
        $response['error_message'] = "Please use a valid API method";
}

response_stats_headers();

echo json_encode($response, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed to make the output more readable

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

response_header_setup(); // Set the initial headers for the response
$response = []; // Set up the response array which will be converted to json at the end of the request

$content = new Content(); // Initialise the new 'content' object
$keys = [];

$request_type = get_request_type();
// Check to see what type of request came in (every request (/GET or /POST or /OPTIONS ) comes to this file ) - Based on the request type, process the request accordingly
switch ($request_type) {
    case 'GET' :
        $options = get_query_options(); // Get the query options of the request (such as ORDER, LIMIT etc which are not referring to the content itself)

        foreach ($options as $option) {
            $content->set_query_options($option['name'], $option['value']); // Set theese options as the query options for the current content object so that they can be used later
        }

        foreach ($_GET as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value); // Set all the query params referring to the content itelf as parameters of the current content object
        }

        $strict = $content->get_strict_columns($options);

        $response['data'] = $content->search_by($keys); // Perform a search and return all the matching content
        break;

    case 'POST':
        foreach ($_POST as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value); // Set all the query properties referring to the content itelf as parameters of the current content object
        }
        $response['data'] = $content->insert($keys); // Insert the current content object into the DB (this may actually perform an update if the content already exists)
        break;

    case 'OPTIONS':
        $response['data'] = $content->options(); // List the column names of the current content objects databse table
        break;

    default:
        http_response_code(405);
        header("Access-Control-Allow-Methods: GET, POST");
        $response['error'] = "Unknown method";
        $response['error_message'] = "Please use a valid API method";
}

response_stats_headers(); // Create the headers that refer to statistics of the request

echo json_encode($response, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed to make the output more readable

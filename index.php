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
require 'includes/user.php';
require 'includes/content/content.php';

response_header_setup(); // Set the initial headers for the response
$response = []; // Set up the response array which will be converted to json at the end of the request

$user = get_request_user_id();
$content = new Content(); // Initialise the new 'content' object
$keys = [];

$request_type = get_request_type();
// Check to see what type of request came in (every request (/GET or /POST or /OPTIONS ) comes to this file ) - Based on the request type, process the request accordingly
switch ($request_type) {
    case 'GET' :
        $options = get_query_options(); // Get the query options of the request (such as ORDER, LIMIT etc which are not referring to the content itself)

        $count_only = false;
        if (isset($_GET['COUNT'])) {
          $count_only = true;
          unset($_GET['COUNT']);
        }

        foreach ($options as $option) {
            $content->set_query_options($option['name'], $option['value']); // Set theese options as the query options for the current content object so that they can be used later
        }

        $strict = $content->get_strict_columns();
        unset($_GET['STRICT']);

        foreach ($_GET as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value); // Set all the query params referring to the content itelf as parameters of the current content object
        }

        if ($count_only == true ){
          $response['data'] = $content->count_matching_items($keys); // Perform a search and return all the matching content
        }
        else {
          $response['data'] = $content->search_by($keys); // Perform a search and return all the matching content
        }
        break;


    case 'POST':
        $owner_id = ($user ? $user->user_id : 0);

        $content->set_parameter('owner_id', $owner_id);

        foreach ($_POST as $key => $value) {
            $keys [] = $key;
            $content->set_parameter($key, $value); // Set all the query properties referring to the content itelf as parameters of the current content object
        }
        
        $response['data'] = $content->insert(); // Insert the current content object into the DB (this may actually perform an update if the content already exists)
        break;

    case 'OPTIONS':
        $response['data'] = $content->options(); // List the column names of the current content objects databse table
        break;

    default:
        http_response_code(405);
        header("Access-Control-Allow-Methods: GET, POST");
        $GLOBALS['error'] = "Unknown method";
        $GLOBALS['error_message'] = "Please use a valid API method";
        return_error_response();
}

$response['error'] = $GLOBALS["error"];
$response['error_message'] = $GLOBALS["error"];

if ($response['error'] == null ){
    unset($response['error']);
    unset($response['error_message']);
}
response_stats_headers(); // Create the headers that refer to statistics of the request

echo json_encode($response, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed to make the output more readable

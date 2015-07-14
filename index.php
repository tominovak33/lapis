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
require 'includes/content/content.php';

$content = new Content();
$keys = [];

foreach ($_GET as $key => $value) {
    $keys [] = $key;
    $content->set_parameter($key, $value);
}

$search = $content->search_by($keys);

echo json_encode($search, JSON_PRETTY_PRINT); //Add JSON_PRETTY_PRINT as second param if needed

<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require "configuration.php";

$response_code = 400;
$final_output = [];
$final_output["max_clouds"] = $max_clouding;

http_response_code(200);
print json_encode($final_output);

?>
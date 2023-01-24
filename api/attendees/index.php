<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require "../../configuration.php";
require "attendees.class.php";

$final_output = [];
$response_code = 400;

try {
    $sql = new mysqli($dbhost, $dbuser, $dbpass, $db);
}
catch(Exception $e) {
    $final_output["errors"] = ["Database connection error."];
    returnHttpResponse(500, $final_output);
}

$attendees = new Attendees($sql);

$response_code = $attendees->response_code;
    
if ( $response_code != 200 ) {
    $final_output["errors"] = $attendees->errors;
} else {
    if ( is_array($attendees->response) ) {
        $final_output["list"] = $attendees->response;
    } else {
        $final_output["list"] = [];
    }
}



returnHttpResponse($response_code, $final_output);

function returnHttpResponse($response_code, $output) { 
    http_response_code($response_code);
    print json_encode($output);
    exit();
}




?>
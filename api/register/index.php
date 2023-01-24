<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require "../../configuration.php";
require "register.class.php";

$final_output = [];
$response_code = 400;
$input = json_decode(file_get_contents('php://input'));
$decoded_without_errors = json_last_error() == JSON_ERROR_NONE;

if ($decoded_without_errors) {

    $name       = $input->name;
    $email      = $input->email;
    $city       = $input->city;
    $timestamp  = $input->timestamp;
    $comment    = $input->comment;

    try {
        $sql = new mysqli($dbhost, $dbuser, $dbpass, $db);
    }
    catch(Exception $e) {
        $final_output["errors"] = ["Database connection error."];
        returnHttpResponse(500, $final_output);
    }

    $register = new Register($name, $email, $city, $timestamp, $comment, $sql);
    $response_code = $register->response_code;

    
    if ( $response_code != 200 ) {
        $final_output["errors"] = $register->errors;
    } else {
        $final_output["status"] = ["ok"];
    }
    
} else {
    $final_output["errors"] = ["No data.", json_last_error_msg()];
}

returnHttpResponse($response_code, $final_output);

function returnHttpResponse($response_code, $output) { 
    http_response_code($response_code);
    print json_encode($output);
    exit();
}

?>
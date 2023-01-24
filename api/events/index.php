<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require "../../configuration.php";
require "city.class.php";
require "events.class.php";

date_default_timezone_set($default_time_zone);

$response_code = 400;
$final_output = [];
$final_output["max_clouds"] = $max_clouding;
foreach ($cities as $key => $country) {

    $country_code = $key;
    $country_name = $country["country_name"];

    $final_output["list"][$country_code]["country_name"] = $country_name;

    foreach ($country["cities"] as $city_name) {

        $city = new City( $city_name, $country_code, $api_key, $new_api );
        $city_events = new Events(
            $city_name,
            $country_code,
            $city->getLatitude(),
            $city->getLongitude(),
            $starting_times,
            $max_clouding,
            $api_key);

        $response_code = $city_events->response_code;
        if ($response_code == 200) {
            $final_output["list"][$country_code][] = $city_events->get();
        }
    }
}

returnJsonHttpResponse($response_code, $final_output);

function returnJsonHttpResponse($response_code, $data) {
    http_response_code($response_code);
    print json_encode($data);
    exit();
}

?>
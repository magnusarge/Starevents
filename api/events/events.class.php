<?php
/**
 * Events.
 *
 * Available events for one location.
 * If latitude and longitude are not provided,
 * use old api, which works and is faster.
 * 
 * Returned object contains name of the city and list of 
 * the events, matching criteria (max clouding).
 * 
 * Use config file to set api version and max clouding.
 *
 */
class Events {

    private $final_event_list = [];
    private $city_name = "";
    private $starting_times = [];
    private $max_clouding = 0;
    private $url = "";
    private $errors = [];
    public $response_code = 400;

    public function __construct(
            $city_name, $country_code,
            $lat, $lon,
            $starting_times,
            $max_clouding,
            $api_key) {

        $valid_location  = $this->constructAndValidateUrl($city_name, $country_code, $lat, $lon, $api_key);
        $valid_times     = $this->validateTimes($starting_times, "H:i");
        $valid_clouding  = is_int($max_clouding) || $this->setError("Max clouding format is wrong.");

        $params_are_ok = $valid_location && $valid_times && $valid_clouding;

        if ($params_are_ok) {
            $this->city_name        = $city_name;
            $this->starting_times   = $starting_times;
            $this->max_clouding     = $max_clouding;

            $this->populateFinalEventList();

        } else {
            $this->setError("Check parameters.");
        }
    }

    public function get() {
        return ["city_name"=>$this->city_name, "events"=>$this->final_event_list];
    }

    public function getErrors() {
        if ( count($this->errors) ) {
            array_push($this->errors, $this->url);
            return $this->errors;
        } else {
            return false;
        }
    }

    private function setError($error, $response_code = 400) {
        array_push($this->errors, $error);
        $this->response_code = $response_code;
        return false;
    }

    private function constructAndValidateUrl($city_name, $country_code, $lat, $lon, $api_key) {
        $valid_api = is_string($api_key) && strlen($api_key);

        if ($valid_api) {

            $url_prefix = "http://api.openweathermap.org/data/2.5/forecast?";

            if ( strlen($lat) && strlen($lon) ) {
                $this->url = $url_prefix."lat={$lat}&lon={$lon}&appid={$api_key}";
                return true;

            } elseif (strlen($city_name) && strlen($country_code)) {
                $encoded_city_name = urlencode($city_name);
                $this->url = $url_prefix."q={$encoded_city_name},{$country_code}&appid={$api_key}";
                return true;

            } else {
                $this->setError("Provide correct location. ".
                "lat={$lat}&lon={$lon}&city_name={$city_name}&country_code={$country_code}");
            }

        } else {
            $this->setError("No API key provided.");
        }

        return false;
    }

    private function validateTimes($starting_times, $format) {

        if (is_array($starting_times)) {

            foreach ($starting_times as $time) {
                $time_obj = DateTime::createFromFormat($format, $time);
                $is_valid = $time_obj && $time_obj->format($format) == $time;

                if ($is_valid == false) {
                    return $this->setError("Time format is not correct.");
                }
            }

        } else {
            return $this->setError("Starting times are missing.");
        }

        return true;

    }

    private function populateFinalEventList() {
        $context = stream_context_create(['http' => ['ignore_errors' => true]]);
        $response = file_get_contents($this->url, false, $context);

        // Obtain response code from response header
        $status_line = $http_response_header[0];
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        $response_code = $match[1];

        if ($response_code !== "200") {
            $this->setError("Unexpected response status: {$status_line}", $response_code);
        } else {
            $this->final_event_list = $this->filterEvents($response);
        }
    }

    private function filterEvents($response) {
        $forecast_data = json_decode($response, true);

        $final_events = [];

        if ( $forecast_data ) {

            $filtered_array = array_filter( $forecast_data['list'], function($item) {
                return in_array( date("H:i", $item["dt"]), $this->starting_times );
            });
            
            foreach ($filtered_array as $event) {
                $clouds = $event["clouds"]["all"];

                if ($clouds <= $this->max_clouding) {
                    $timestamp = $event["dt"];
                    $time_text = date("Y-m-d H:i", $event["dt"]);
                    array_push($final_events, ["timestamp"=>$timestamp, "dt_text"=>$time_text, "clouds"=>$clouds]); 
                }
            }

            $this->response_code = 200;

        } else {
            $this->setError("No data.");
        }

        return $final_events;
    }

    


}


?>
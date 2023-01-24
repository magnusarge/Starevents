<?php

class City
{
    private $city_name;
    private $country_code;
    private $lat = "";
    private $lon = "";
    
    public function __construct($city_name, $country_code, $api_key, $new_api) {

        $this->city_name = $city_name;
        $this->country_code = $country_code;
        
        if ($new_api) {
            $encoded_city_name = urlencode($city_name);
            $url = "http://api.openweathermap.org/geo/1.0/direct?q={$encoded_city_name},{$country_code}&limit=1&appid=".$api_key;

            $forecast_data = file_get_contents($url);
            $data = json_decode($forecast_data);
                
            $this->lat = $data[0]->lat;
            $this->lon = $data[0]->lon;
        }   
    }
    
    public function getLatitude() {
        return $this->lat;
    }
    
    public function getLongitude() {
        return $this->lon;
    }
}

?>
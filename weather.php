<?php
header("Content-Type: application/json");

$api_key = "bef9599f3eab7a0320fee50002bb6de4";//f63a322a0db5dbdc7af8118b77ad7797";
require "city.php";

$cities = [
    "EE"=>[
        "country_name"=>"Estonia",
        "cities"=>["Tallinn", "Tartu", "Narva", "Pärnu", "Jõhvi", "Jõgeva", "Põlva", "Valga"]
    ]
];
$citiesx = [
    "EE"=>[
        "country_name"=>"Estonia",
        "cities"=>["Tallinn"]
    ]
];

date_default_timezone_set("Europe/Tallinn");
$start_date = strtotime("today");

$days_count = 2;
$times = ["20:00", "23:00", "02:00", "05:00"];
$times = ["20:00"];
sort($times);
$event_times = [];
for ( $i=0; $i<=$days_count; $i++ ) {
    
    $day = $i + 1;
    foreach( $times as $time ) {
        $event_time = strtotime("+{$day} days {$time}", $start_date);
        array_push($event_times, $event_time);
        //print date("d.mm.Y H:i", $event_time)."<br>\n";
    }

}

$output = [];

foreach( $cities as $country=>$data ) {
  
    $cities_list = $data["cities"];

    foreach( $cities_list as $city ) {  

        $forecast_data = getForecast($city, $country);
        
        if ( $forecast_data ) {
            foreach( $forecast_data->list as $forecast ) {

                foreach ( $event_times as $event_time ) {
                    if ( date('Y-m-d H:i', $forecast->dt) == date('Y-m-d H:i', $event_time) ) { 
                        
                        $clouds = $forecast->clouds->all;
                        if ( $clouds < 80 ) {
                            $start_time = date('d.m H:i', $forecast->dt);
                            
                            $event = ["start_time"=>$start_time,"clouds"=>$clouds];
                            $output["list"][$country]["events"][]["city_name"] = $city;
                            $output["list"][$country]["events"][]["city_name"][] = $event;

                            //$time = date('d.m H:i', $forecast->dt);
                            //echo "{$city} at {$time}: clouds: " . $forecast->clouds->all . "%<br>\n"
                        }
                    }
                }
            }
        }
    }

    $output["list"][$country]["country_name"] = $data["country_name"];

}


function getForecast($city, $country) {
    global $api_key;
    $city = new City($city,$country,$api_key);

    if ( $city->status ) {
        $lat = $city->getLatitude();
        $lon = $city->getLongitude();

        $forecast_url = "http://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$api_key}";
        $forecast_data = file_get_contents($forecast_url);
        $forecast_data = json_decode($forecast_data);

        return $forecast_data; 
    }
  
    return false;

}


$data = json_encode($output);
echo "<pre>";
print_r($output);
//print $data;
echo "</pre>";

/*
 * returnJsonHttpResponse
 * @param $success: Boolean
 * @param $data: Object or Array
 */
function returnJsonHttpResponse($success, $data)
{
    // Remove any string that could create an invalid JSON 
    ob_clean();

    // Clean up any previously added headers
    header_remove(); 

    // Set the content type to JSON
    header("Content-type: application/json");

    // Response code
    if ($success) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
    
    // encode PHP Object or Array
    echo json_encode($data);


    exit();
}

// foreach($forecast_data->list as $forecast) {
//     if(date('Y-m-d H:i', $forecast->dt) == date('Y-m-d H:i', $tomorrow_10pm)) {
//         echo "Clouds: " . $forecast->clouds->all . "%<br>\n";
//         break;
//     }
// }

?>
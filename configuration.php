<?php
/** Openweathermap settings */
$api_key = "bef9599f3eab7a0320fee50002bb6de4";
$new_api = false; // Old api skips query for coordinates and uses city name directly. See Events class.
$max_clouding = 10; // This indicates condition when visibility of sky becomes zero. For testing, set it higher.

/** Event settings */
$default_time_zone = "Europe/Tallinn";
$starting_times = ["20:00","23:00","02:00","05:00"];
// You can make querys for any country, but current user interface is built for Estonia.
$cities = [
    "EE"=>[
        "country_name"=>"Estonia",
        "cities"=>["Tallinn", "Tartu", "Narva", "Pärnu", "Jõhvi", "Jõgeva", "Põlva", "Valga"]
    ]
];

/** Database settings */
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "starevents";

// $dbuser = "d110670sa446225";
// $dbpass = "djdK8v7WE8hQb96NC2";
// $dbhost = "d110670.mysql.zonevs.eu";
// $db = "d110670_starevent";


?>
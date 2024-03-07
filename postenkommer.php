<?php
# Script to get info about post delivery.
function mailDeliveryDay(){

    $url = 'https://portal.postnord.com/api/sendoutarrival/closest?postalCode=46791'; 

    $opts = ["http" => ["method" => "GET", "header" => "Content-Type: application/json\r\n" ]];
    $ctx = stream_context_create($opts);
    $val = @file_get_contents($url, false, $ctx);
    #echo $val;
    $data = json_decode($val);
    #echo $data->delivery."<br>";

    $deldata = explode(" ", $data->delivery);
    $day = $deldata[0];
    $monl = $deldata[1];
    $mon = str_replace(',', '', $monl);
    $yr = $deldata[2];
    #echo $mon;

    # Data is something like delivery":"6 mars, 2024". Get number of month:
    $array = array(0 => 'januari', 1 => 'februari', 2 => 'mars', 3 => 'april', 4 => 'maj', 5 => 'juni', 6 => 'juli', 7 => 'augusti', 8 => 'september', 9 => 'oktober', 10 => 'november', 11 => 'december');
    $monnr = array_search($mon, $array); 
    #echo $key;
    # Get day of the year
    $deldate  = mktime(0, 0, 0, $monnr+1, $day, $yr);
    #echo $deldate."<br>";
    #echo date(Ymd, $deldate);
    $dday = date(z, $deldate);
    #echo "Delievery day: ".$dday."<br>";
    $tday = date('z');
    #echo "Today: ".$tday."<br>";
    $dist = $dday-$tday;
    $daysarray = array(0 => 'idag', 1 => 'imorgon', 2 => 'i övermorgon', 3 => 'om tre dagar');
    #echo "Mail in ".$daysarray[$dist];
    return $daysarray[$dist];
}
?>
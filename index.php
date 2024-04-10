<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HAViewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link  rel="stylesheet" href="main.css?v=1.25">
    <script type="text/javascript"> 
        
        // Refresh page and sensor values
        window.setInterval(reloadFunction, 15*60000);
        function reloadFunction(){
            location.reload();
        }

        function display_c(){
            var refresh=1000; // Refresh rate in milli seconds
            mytime=setTimeout('display_ct()',refresh)
            }

            function display_ct() {
            var date = new Date()
            var hours = date.getHours().toString().padStart(2, '0');
            var minutes = date.getMinutes().toString().padStart(2, '0');
            var seconds = date.getSeconds().toString().padStart(2, '0');
            var year = date.getFullYear().toString().padStart(2, '0');
            var month = date.getMonth()+1;
            month=month.toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');

            document.getElementById('ct').innerHTML = hours + ":" + minutes;
            document.getElementById('ctdate').innerHTML = year + "-" + month + "-" + day;
            display_c();
        }
        function updated(){
            var date = new Date()
            var hnow = date.toLocaleTimeString();
            var txt = "Uppdaterad: "+hnow;
            document.getElementById('updated').innerHTML = txt;
        }

    </script>

</head>
    <?php

    include 'postenkommer.php';
    include 'weathericon.php';

    function getHA($id) { //ID to read - for example sensor.foo
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiM2IxYTU3YzJmYmM0MGRmODNjNGQ1ZGIzNTA1NDg1NCIsImlhdCI6MTcwNjI3Nzc1OSwiZXhwIjoyMDIxNjM3NzU5fQ.y7UdYrCBziTyleaCb5WVrB1up_BcpgvRTXVKqItlJL0'; //Long-lived access token, can be obtained in HomeAssistant UI→Profile
        $url = 'http://192.168.1.10:8123/'; //URL to your HA including trailing /

        $opts = [
            "http" => [
                "method" => "GET",
                "header" =>
                    "Content-Type: application/json\r\n" .
                    "Authorization: Bearer ".$key."\r\n"
            ]
        ];
        $ctx = stream_context_create($opts);
        $data = @file_get_contents($url.'api/states/'.$id, false, $ctx);

        if(!$data) return false;
        return $data;
    }
    function getValue($sensor) {
        $val = getHA($sensor);
        if($val) {
            $data = json_decode($val);
            return $data;
        }else{
            return 'Sensor not found';
        }
    }
    function random_pic() {
        $dirname   = 'BackgroundImages/';
        $images = glob($dirname."*.{jpg,gif,png,JPG}",GLOB_BRACE);
        $file = array_rand($images);
        return $images[$file];
        
    }
    $bpic = random_pic();
?>
<body onload=display_ct();>
    <div class="topdiv bg-info p-2 text-dark bg-opacity-75">
        <table>
            <tr>
                <td>&nbsp;</td>
            <tr>
            <tr>
                <td width="200px"><h1 class="display-1"><div id='ct' ></div></h1></td>
                <td>&nbsp;</td>
                <td width="250px"><h1 class="display-4"><div id='ctdate' ></div></h1></td>
            </tr>
        </table>
    </div>
    <div class="updated" id="updated">
    </div>
    <div class="post bg-info bg-opacity-75">
            <?php
                echo '<h2>Posten kommer: ';
                $dday = mailDeliveryDay();
                echo $dday;
                echo '</h2>';
            ?>
            <div class="el bg-info bg-opacity-75">
                <?php
                    $Y = date("Y");
                    $m = date("m");
                    $d = date("d");
                    $h = date("H");

                    $content = file_get_contents("https://www.elprisetjustnu.se/api/v1/prices/".$Y."/".$m."-".$d."_SE3.json");
                    $result  = json_decode($content);
                    $timenow = date(DATE_ATOM, mktime($h, 0, 0, $m, $d, $Y));
                    foreach ($result as $row){
                        if($row->time_start == $timenow)
                        {
                            echo '<h2>Elpris: '.round($row->SEK_per_kWh*1.25, 2).'</h2>'.PHP_EOL;
                        }
                    }
                ?>  
            </div>
    </div>
    <div>
        <img alt="" width="500" height="500" src="<?=$bpic?>">
    </div>

    <div class="innerdiv bg-light p-2 text-dark bg-opacity-75">
        <?php
            //Såtenäs (82260)
 
            $content = file_get_contents("https://opendata-download-metfcst.smhi.se/api/category/pmp3g/version/2/geotype/point/lon/12.565/lat/58.427/data.json");
            $result  = json_decode($content);
            $refTime = $result->referenceTime;
            //echo $refTime;
            //echo "<br>";

            $timeToTomorrow = (strtotime('tomorrow') - strtotime('now')) / 3600;
            $timeToTomorrowThirteen = round($timeToTomorrow) + 13;
            //echo $timeToTomorrowThirteen;
            $e = $result->timeSeries[$timeToTomorrowThirteen];
            foreach ($e as $tag){
                foreach ($tag as $subtag){
                    if($subtag->name == "Wsymb2"){
                      $weatherTomorrow = $subtag->values[0]; 
                      $w_symbol_text_tomorrow = getWS($weatherTomorrow);
                    }
                }
            }

            $e = $result->timeSeries[0];
            //var_dump($e);
            foreach ($e as $tag){
                //var_dump($tag);
                foreach ($tag as $subtag){
                     if($subtag->name == "wd"){
                        $vd = $subtag->values[0]; 
                     }
                     if($subtag->name == "ws"){
                        $wSpeed = $subtag->values[0]; 
                     }
                     if($subtag->name == "t"){
                        $temp = $subtag->values[0]; 
                     }
                     if($subtag->name == "msl"){
                        $pressure = $subtag->values[0]; 
                     }
                     if($subtag->name == "r"){
                        $moist = $subtag->values[0]; 
                     }
                     if($subtag->name == "msl"){
                        $pressure = $subtag->values[0]; 
                     }
                     if($subtag->name == "Wsymb2"){
                        $wsNr = $subtag->values[0]; 
                        $w_symbol_text = getWS($wsNr);
                     }
                }
            }
            echo "<h2>Temp: ".$temp."C</h2>";
            echo "<h2>Fukt: ".$moist."%</h2>";
            echo "<h2>Lufttryck: ".$pressure."hPa</h2>";

            echo "<h2>Vindstyrka: ";
            if ($wSpeed <= 0.2) {
                echo "Lugnt($wSpeed)";
            }
            elseif ($wSpeed > 0.2 && $wSpeed < 3.4) {
                echo "Svag vind($wSpeed)";
            }
            elseif ($wSpeed >= 3.4 && $wSpeed < 8.0) {
                echo "Måttlig vind($wSpeed)";
            }
            elseif ($wSpeed >= 8.0 && $wSpeed < 13.9) {
                echo "Frisk vind($wSpeed)";
            }
            elseif ($wSpeed >= 13.9 && $wSpeed < 24.5) {
                echo "Hård vind($wSpeed)";
            }
            elseif ($wSpeed >= 24.5) {
                echo "Storm($wSpeed)";
            }
            echo "</h2>";

            $index = ($vd+11.25)/22.5;
            $array = array(0 => 'Nord', 1 => 'Nordnordöst', 2 => 'Nordöst', 3 => 'Östnordöst', 4 => 'Öst', 5 => 'Östsydöst', 6 => 'Sydöst', 7 => 'Sydsydöst', 8 => 'Syd', 9 => 'Sydsydväst', 10 => 'Sydväst', 11 => 'Västsydväst', 12 => 'Väst', 13 => 'Västnordväst', 14 => 'Nordväst', 15 => 'Nordnordväst');
            echo "<h2>Vindriktning: ".$array[$index]."</h2>";

            $w_string = explode(",",$w_symbol_text);
            $w_string_tomorrow = explode(",",$w_symbol_text_tomorrow);
        echo "
            <h2>Väder idag: ".$w_string[1]."</h2>
            <h2>Väder imorgon:  ".$w_string_tomorrow[1]."</h2>
            ".PHP_EOL;
        echo"
           </div>
            <div class='wImage'>";
        echo "<h2><img width='50' height='40' src='WeatherImages/" . $w_string[0] .".png' alt='Weather symbol image'/></h2>";
    ?>
   </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script>
            updated();
        </script>    
    </body>
</html>
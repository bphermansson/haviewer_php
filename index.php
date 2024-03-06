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
            var month = date.getMonth().toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');

            document.getElementById('ct').innerHTML = hours + ":" + minutes + ":" + seconds;
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
<body onload=display_ct();>"
    <div class="topdiv bg-info p-2 text-dark bg-opacity-75">
        <table>
            <tr>
                <td>&nbsp</td>
            <tr>
            <tr>
                <td width="250px"><h1 class="display-1"><div id='ct' ></div></h1></td>
                <td>&nbsp</p>
                <td width="250px"><h1 class="display-4"><div id='ctdate' ></div></h1></td>
                <td width="50px"><p class="fs-3"><div id='updated'></div></p></td>
            </tr>
        </table>
    </div>
    <div class="post bg-info bg-opacity-75">
            <?php
                $val = getValue('sensor.postnord_delivery_46791');
                echo '<h2>Posten kommer: ';
                switch ($val->state) {
                    case 0:
                        echo "Idag";
                        break;
                    case 1:
                        echo "Imorgon";
                        break;
                    case 2:
                        echo "I övermorgon";
                        break;
                    case 3:
                        echo "Om tre dagar";
                        break;
                }
                echo '</h2>';
            ?>
            <div class="el bg-info bg-opacity-75">
                <?php
                    $val = getValue('sensor.nordpool_kwh_se3_sek_3_10_025');
                    echo '<h2>Elpris: '.$val->state.'</h2>'.PHP_EOL;
                ?>  
            </div>
        </div>
    </div>
    <div>
        <img alt="" width="500" height="500" src="<?=$bpic?>">
    </div>

    <div class="innerdiv bg-info p-2 text-dark bg-opacity-75">
        <?php
            $val = getValue('sensor.outdoortemp_xiron');
            $valhum = getValue('sensor.outdoorhum_xiron');
            $valwind = getValue('sensor.satenas_vind');
            $valwinddir = getValue('sensor.wind_sensor_human');
            $valprednow = getValue('sensor.prediction');
            $valpredtomorrow = getValue('sensor.prediction_tomorrow');

            echo "
            <h2>Temp: ".$val->state."C</h2>
            <h2>Fukt: ".$valhum->state."%</h2>";

            echo "<h2>Vindstyrka: ";
            if ($valwind->state <= 0.2) {
                echo "Lugnt($valwind->state)";
            }
            elseif ($valwind->state > 0.2 && $valwind->state < 3.4) {
                echo "Svag vind($valwind->state)";
            }
            elseif ($valwind->state >= 3.4 && $valwind->state < 8.0) {
                echo "Måttlig vind($valwind->state)";
            }
            elseif ($valwind->state >= 8.0 && $valwind->state < 13.9) {
                echo "Frisk vind($valwind->state)";
            }
            elseif ($valwind->state >= 13.9 && $valwind->state < 24.5) {
                echo "Hård vind($valwind->state)";
            }
            elseif ($valwind->state >= 24.5) {
                echo "Storm($valwind->state)";
            }
            echo "</h2>";
            echo "
            <h2>Vindriktning: ".$valwinddir->state."</h2>
            <h2>Väder idag: ".$valprednow->state."</h2>
            <h2>Väder imorgon: ".$valpredtomorrow->state."</h2>
            ".PHP_EOL;
        ?>
   </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script>
            updated();
        </script>    
    </body>
</html>
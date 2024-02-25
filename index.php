<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
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
        #$data = json_decode($data);
        #if(!$data) return false;

       #echo $data->attributes->pressure;

        #if(!isset($data->state)) return false;
        #echo $data;
        #if(isset($data->attributes)) echo ("$data->attributes");

        #return [$data->state, $data->last_updated, $data->attributes->pressure];
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
    function random_pic($dir = 'home/patrik/') {
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        return $files[$file];
    }
?>
<div class="container text-center">

    <div class="row align-items-start">
        <div class="col">
        <?php
            $val = getValue('sensor.outdoortemp_xiron');
            echo '<h2>Temp: '.$val->state.'</h2>'.PHP_EOL;
        ?>
        </div>
        <div class="col">
            <?php
                $val = getValue('sensor.nordpool_kwh_se3_sek_3_10_025');
                echo '<h2>Elpris: '.$val->state.'</h2>'.PHP_EOL;
            ?>        
        </div>
        <div class="col">
            <?php
                $val = getValue('sensor.satenas_vind');
                echo '<h2>Vindstyrka: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('sensor.wind_sensor_human');
                echo '<h2>Vindriktning: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('sensor.prediction');
                echo '<h2>Väder idag: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('sensor.prediction_tomorrow');
                echo '<h2>Väder imorgon: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('sensor.date_time');
                echo '<h2>Tid&datum: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('sensor.postnord_delivery_46791');
                echo '<h2>Posten kommer: '.$val->state.'</h2>'.PHP_EOL;
            ?>
            <?php
                $val = getValue('weather.smhi_home');
                echo '<h2>Prognos idag: '.$val->state.'</h2>'.PHP_EOL;

                echo $val->attributes->forecast;
                $values = $val->attributes->forecast;
                #echo $values[1]->condition;
                echo '<h2>Prognos imorgon: '.$values[1]->condition.'</h2>'.PHP_EOL;


                echo '<h2>Lufttryck: '.$val->attributes->pressure.'</h2>';

            ?>          
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
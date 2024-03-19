<?php
//$r = getWS(3);
//echo $r;
 //function getWS($id) {
    echo "id:".$id;
    switch ($id) {
        case 1: return "wsymb2_clear_sky";
        break;
        case 2: return "wsymb2_nearly_clear_sky";
        break;
        case 3: return "wsymb2_variable_cloudiness";
        break;
        case 4: return "wsymb2_halfclear_sky";
        break;
        case 5: return "wsymb2_cloudy_sky";
        break;
        case 6: return "wsymb2_overcast";
        break;
        case 7: return "wsymb2_fog";
        break;
        case 8: return "wsymb2_light_rain_showers";
        break;
        case 9: return "wsymb2_moderate_rain_showers";
        break;
        case 10: return "wsymb2_heavy_rain_showers";
        break;
        case 11: return "wsymb2_thunderstorm";
        break;
        case 12: return "wsymb2_light_sleet_showers";
        break;
        case 13: return "wsymb2_moderate_sleet_showers";
        break;
        case 14: return "wsymb2_heavy_sleet_showers";
        break;
        case 15: return "wsymb2_light_snow_showers";
        break;
        case 16: return "wsymb2_moderate_snow_showers";
        break;
        case 17: return "wsymb2_heavy_snow_showers";
        break;
        case 18: return "wsymb2_light_rain";
        break;
        case 19: return "wsymb2_moderate_rain";
        break;
        case 20: return "wsymb2_heavy_rain";
        break;
        case 21: return "wsymb2_thunder";
        break;
        case 22: return "wsymb2_light_sleet";
        break;
        case 23: return "wsymb2_moderate_sleet";
        break;
        case 24: return "wsymb2_heavy_sleet";
        break;
        case 25: return "wsymb2_light_snowfall";
        break;
        case 26: return "wsymb2_moderate_snowfall";
        break;
        case 27: return "wsymb2_heavy_snowfall";
        break;
    }
}



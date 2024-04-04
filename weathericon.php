<?php
 function getWS($id) {
    switch ($id) {
        case 1: return "wsymb2_clear_sky, klart";
        case 2: return "wsymb2_nearly_clear_sky, nästan klart";
        case 3: return "wsymb2_variable_cloudiness, växlande molnighet";
        case 4: return "wsymb2_halfclear_sky, halvklart";
        case 5: return "wsymb2_cloudy_sky, molnigt";
        case 6: return "wsymb2_overcast, disigt";
        case 7: return "wsymb2_fog, dimma";
        case 8: return "wsymb2_light_rain_showers, regnskurar lätta";
        case 9: return "wsymb2_moderate_rain_showers, regnskurar medel";
        case 10: return "wsymb2_heavy_rain_showers, kraftiga regnskurar";
        case 11: return "wsymb2_thunderstorm, åska, storm";
        case 12: return "wsymb2_light_sleet_showers, skurar snöblandat regn, lätt";
        case 13: return "wsymb2_moderate_sleet_showers, skurar snöblandat regn medel";
        case 14: return "wsymb2_heavy_sleet_showers, skurar snöblandat regn, kraftiga";
        case 15: return "wsymb2_light_snow_showers, snöbyar lätta";
        case 16: return "wsymb2_moderate_snow_showers, snöbyar medel";
        case 17: return "wsymb2_heavy_snow_showers, snöbyar kraftiga";
        case 18: return "wsymb2_light_rain, lätt regn";
        case 19: return "wsymb2_moderate_rain, regn medel";
        case 20: return "wsymb2_heavy_rain, kraftigt regn";
        case 21: return "wsymb2_thunder, åska";
        case 22: return "wsymb2_light_sleet, lätt dis";
        case 23: return "wsymb2_moderate_sleet, dis medel";
        case 24: return "wsymb2_heavy_sleet, kraftigt dis";
        case 25: return "wsymb2_light_snowfall, lätt snöfall";
        case 26: return "wsymb2_moderate_snowfall, snöfall medel";
        case 27: return "wsymb2_heavy_snowfall, snöfall kraftigt";
    }
 }
?>



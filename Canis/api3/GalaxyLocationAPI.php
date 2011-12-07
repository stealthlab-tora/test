<?php

/**
* The API around Location
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


if (!isset($_POST["action"])) {
	WarnLogger::write("Request without action has come.");

} else if ($_POST["action"] == "GET") {
    
    DebugLogger::write("Get address operaion starts.");

    $result = null;
    
    if ($_POST["locationtype"] == "ZIPCODE") {
         if (isset($_POST["zipcode"])) {
             $result = LocationUtil::getLocationDataFromZipcode($_POST["zipcode"]);

         } else {
             $result = OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));

         }
    
    } else if ($_POST["locationtype"] == "ADDRESS") {
    	if (isset($_POST["state"]) && isset($_POST["city"])) {
    		$result = LocationUtil::getLocationDataFromAddress($_POST["state"], $_POST["city"]);
    	
    	} else {
    		$result = OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));

    	}
    
    } else if ($_POST["locationtype"] == "GEOLOCATION") {
    	if (isset($_POST["latitude"]) && isset($_POST["longtitude"])) {
    		$result = LocationUtil::getLocationDataFromGeolocation($_POST["latitude"], $_POST["longtitude"]);
    	
    	} else {
    		$result = OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));

    	}

    } else {
    	$result = OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));

    }
    
    echo(json_encode($result));
    
    DebugLogger::write("Get address operation ends.");


} else {
    WarnLogger::write("Request without proper action has come.");
}

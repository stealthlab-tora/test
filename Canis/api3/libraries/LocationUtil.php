<?php

/**
* The class to get location data.
*
* [method]
* + getLocationDataFromZipcode : The method to get location data from zipcode
* + getLocationDataFromAddress : The method to get location data from address
* + getLocationDataFromGeolocation : The method to get location data from geo location
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class LocationUtil
{

    public static function getLocationDataFromZipcode($zipcode)
    {
    	$error = array();
    	
        // Validate zipcode
        if (is_null($zipcode)) {
            WarnLogger::write("Zipcode is not requested.");
            $error[] = APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($zipcode)) {
                InfoLogger::write("Zipcode is empty.");
                $error[] = USER_EMPTY_ZIPCODE;
        
            } else {
            }
        }
        
        if (count($error) > 0) {
	        return OutputUtil::getErrorOutput($error);
        
        } else {
        }
        
        $result = array();
        
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }
    
        // Make prepared statement
        $stmt = $db_connection->prepare("select Y(`latlng`) latitude, X(`latlng`) longtitude, state, city from geolocations where zipcode = :ZIPCODE");
        
        // Bind currency
        $stmt->bindValue(":ZIPCODE", $zipcode, PDO::PARAM_STR);
        
        // execute SQL
        try {
            $stmt->execute();
        
        } catch(Exception $e) {
            ErrorLogger::write("Geolocation selection failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        $geolocations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // close prepared statement
        $stmt = null;
        
        if (count($geolocations) == 1) {
            $result["status"]     = "true";
            $result["latitude"]   = $geolocations[0]["latitude"];
            $result["longtitude"] = $geolocations[0]["longtitude"];
            $result["state"]      = $geolocations[0]["state"];
            $result["city"]       = $geolocations[0]["city"];
        
        } else {
            return OutputUtil::getErrorOutput(array(USER_LOCATION_NOT_FOUND_NONE));
        }
            
        return $result;
    }
    
    
    public static function getLocationDataFromAddress($state, $city)
    {
    	$error = array();
    	
        // Validate state
        if (is_null($state)) {
            WarnLogger::write("State is not requested.");
            $error[] = APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($state)) {
                InfoLogger::write("State is empty.");
                $error[] = APP_SYSTEM_ERROR_NONE;
        
            } else {
            }
        }

        // Validate city
        if (is_null($city)) {
        	WarnLogger::write("City is not requested.");
        	$error[] = APP_SYSTEM_ERROR_NONE;
        
        } else {
        	if (!CheckUtil::checkNotEmpty($city)) {
        		InfoLogger::write("City is empty.");
        		$error[] = USER_EMPTY_CITY;
        
        	} else {
        	}
        }

        
        if (count($error) > 0) {
	        return OutputUtil::getErrorOutput($error);
        
        } else {
        }


        $result = array();
    
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));        

        } else {
        }
    
        // Make prepared statement
        $stmt = $db_connection->prepare("select AVG(Y(`latlng`)) latitude, AVG(X(`latlng`)) longtitude, zipcode from geolocations where state = :STATE and city like :CITY group by city");
    
        // Bind currency
        $stmt->bindValue(":STATE", $state, PDO::PARAM_STR);
        $stmt->bindValue(":CITY",  $city . "%", PDO::PARAM_STR);
        
        // execute SQL
        try {
            $stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Geolocation selection failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        $geolocations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // close prepared statement
        $stmt = null;
        
        if (count($geolocations) != 0) {
            $result["status"]     = "true";
            $result["latitude"]   = $geolocations[0]["latitude"];
            $result["longtitude"] = $geolocations[0]["longtitude"];
            $result["zipcode"]    = $geolocations[0]["zipcode"];
    
        } else {
            return OutputUtil::getErrorOutput(array(USER_LOCATION_NOT_FOUND_NONE));
        }
    
        return $result;
    }
    
    
    public static function getLocationDataFromGeolocation($latitude, $longtitude)
    {

        // Validate latitude
        if (is_null($latitude)) {
            WarnLogger::write("Latitude is not requested.");
            $error[] = APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($latitude)) {
                InfoLogger::write("Latitude is empty.");
                $error[] = APP_SYSTEM_ERROR_NONE;
        
            } else {
            }
        }

        // Validate longtitude
        if (is_null($longtitude)) {
        	WarnLogger::write("Longtitude is not requested.");
        	$error[] = APP_SYSTEM_ERROR_NONE;
        
        } else {
        	if (!CheckUtil::checkNotEmpty($longtitude)) {
        		InfoLogger::write("Longtitude is empty.");
        		$error[] = APP_SYSTEM_ERROR_NONE;
        
        	} else {
        	}
        }

        
        $result = array();
    
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE)); 
            
        } else {
        }
    
        // Make prepared statement
        $stmt = $db_connection->prepare("select zipcode, state, city from geolocations where MBRContains(GeomFromText(Concat('LineString(' , :LONGTITUDE_PLUS_DELTA1, ' ', :LATITUDE_PLUS_DELTA1, ', ' , :LONGTITUDE_PLUS_DELTA2, ' ', :LATITUDE_PLUS_DELTA2, ' ' , ')')), latlng) order by GLength(GeomFromText(Concat('LineString(' , :LONGTITUDE, ' ', :LATITUDE, ', ', X(`latlng`), ' ', Y(`latlng`), ')'))) limit 1");
    
        // Bind currency
        $stmt->bindValue(":LATITUDE"  ,             $latitude,   PDO::PARAM_STR);
        $stmt->bindValue(":LONGTITUDE",             $longtitude, PDO::PARAM_STR);
        $stmt->bindValue(":LATITUDE_PLUS_DELTA1"  , (float)$latitude + 0.2,   PDO::PARAM_STR);
        $stmt->bindValue(":LONGTITUDE_PLUS_DELTA1", (float)$longtitude + 0.2, PDO::PARAM_STR);
        $stmt->bindValue(":LATITUDE_PLUS_DELTA2"  , (float)$latitude - 0.2,   PDO::PARAM_STR);
        $stmt->bindValue(":LONGTITUDE_PLUS_DELTA2", (float)$longtitude - 0.2, PDO::PARAM_STR);
        
        // execute SQL
        try {
            $stmt->execute();
    
        } catch(Exception $e) {
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        }
    
        $geolocations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // close prepared statement
        $stmt = null;
        
        if (count($geolocations) == 1) {
            $result["status"]     = "true";
            $result["zipcode"]    = $geolocations[0]["zipcode"];
            $result["state"]      = $geolocations[0]["state"];
            $result["city"]       = $geolocations[0]["city"];
    
        } else {
            return OutputUtil::getErrorOutput(array(USER_LOCATION_NOT_FOUND_NONE));
        }
    
        return $result;
    }
}

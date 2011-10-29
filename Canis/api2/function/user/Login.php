<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to login to galaxy
function login($email, $password) {

	Logger::write("User information will be searched from DB from now.");

	$result = array();
	
	// encript password
	$password = md5($password . SALT_WORD);
	
    // DB connect
    $db_connection = galaxyDbConnector::getConnection();
    
    if ($db_connection == null) {
    	Logger::write("DB connect failed.");
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }
    
    // prepare SQL statement
    $stmt = $db_connection->prepare(LOGIN_QUERY);
    $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
    $stmt->bindValue(":PASSWORD", $password, PDO::PARAM_STR);
    
    // execute SQL
    try {
        $stmt->execute();
        
    } catch(Exception $e) {
    	Logger::write("User select operation failed.", $e);
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }
    
    // get result number
    $queryResult = $stmt->fetchAll();
    $queryResultNum = count($queryResult);
    
    // close prepared statement
    $stmt = null;
    
    // login judgement
    if ($queryResultNum == 1) {
        Logger::write("User information found."); 
        
        if ($queryResult[0]["temppwflag"] == "TRUE") {
        	
        	if (strtotime("now") - strtotime($queryResult[0]["lastmodifieddate"]) > 24 * 60 * 60) {
            	Logger::write("Temp password is expired.");
            	return returnError(USER_TMP_PW_EXPIRED_NONE);
        	
        	} else {
        		$result["changepasswordflag"] = "true";
        	}
        } else {
        	$result["changepasswordflag"] = "false";
        }
        
        $result["status"]       = "true";
        $result["galaxyuserid"] = $queryResult[0]["galaxyuserid"];
        $result["firstname"]    = $queryResult[0]["firstname"];
        $result["lastname"]     = $queryResult[0]["lastname"];
        $result["email"]        = $queryResult[0]["email"];
        $result["zipcode"]      = $queryResult[0]["zipcode"];
        $result["country"]      = $queryResult[0]["country"];
        $result["state"]        = $queryResult[0]["state"];
        $result["city"]         = $queryResult[0]["city"];
        $result["street"]       = $queryResult[0]["street"];
        $result["phonenumber"]  = $queryResult[0]["phonenumber"];
        
    } else {
        Logger::write("User information not found.");
        $result["status"] = "false";
        $result["error"]  = array(USER_LOGIN_FAILURE_NONE); 
    }
    
    return $result;
}

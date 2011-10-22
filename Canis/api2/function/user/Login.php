<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";

// function to login to galaxy
function login($email, $password) {

	// encript password
	$password = md5($password . SALT_WORD);
	
    // DB connect
    $db_connection = galaxyDbConnector::getConnection();
    
    // prepare SQL statement
    $stmt = $db_connection->prepare(LOGIN_QUERY);
    $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
    $stmt->bindValue(":PASSWORD", $password, PDO::PARAM_STR);
    
    // execute SQL
    try {
        $stmt->execute();
        
    } catch(Exception $e) {
    	Logger::write("user select operation failed.");
    	return false;
    }
    
    // get result number
    $result = $stmt->fetchAll();
    $result_num = count($result);
    
    // close prepared statement
    $stmt = null;
    
    // login judgement
    if ($result_num == 1) {
        Logger::write("Login succeeded"); 
        return true;    
    } else {
        Logger::write("Login failed"); 
        return false;
    }
}

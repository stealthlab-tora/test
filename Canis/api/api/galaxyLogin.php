<?php
// TODO : investigate whether PHP is multithread or not

// include php files
require_once("../lib/Logger/logger.php");
require_once("../lib/DB/galaxyDbConnector.php");

// function to login to galaxy
function galaxyLogin($email, $password) {
    $log = Logger::getLogger('galaxyLogin');

	// encript password
	// TODO : use salt(password + cool or something) 
	$password = md5($password);
	
    // DB connect
    $db_connection = galaxyDbConnector::getConnection();

    // create query
    // TODO : constant ni suru
    $query_array   = array();
    $query_array[] = "select";
    $query_array[] = "    galaxyuserid";
    $query_array[] = "from";
    $query_array[] = "    users";
    $query_array[] = "where";
    $query_array[] = "    email = :EMAIL and";
    $query_array[] = "    password = :PASSWORD";
    
    $query = implode(" ", array_map("trim", $query_array));
    
    // prepare SQL statement
    $stmt = $db_connection->prepare($query);
    $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
    $stmt->bindValue(":PASSWORD", $password, PDO::PARAM_STR);
    
    // execute SQL
    try {
        $stmt->execute();
        
    } catch(Exception $e) {
    	$log->error("user select operation failed.");
    	return false;
    }
    
    // get result number
    $result = $stmt->fetchAll();
    $result_num = count($result);
    
    // close prepared statement
    $stmt = null;
    
    // login judgement
    if ($result_num == 1) {
        $log->info("Login succeeded"); 
        return true;    
    } else {
        $log->info("Login failed"); 
        return false;
    }
}

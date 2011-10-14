<?php
// include php files
require_once("../lib/Logger/logger.php");
require_once("../lib/DB/galaxyDbConnector.php");

// function to login to galaxy
function galaxyLogin($email, $password) {
    $log = Logger::getLogger('galaxyLogin');

	// encript password
	$password = md5($password);
	
    // DB connect
    $db_connection = galaxyDbConnector::getConnection();
    // TODO : use prepared statement
    $result = $db_connection->query("select galaxyuserid from users where email = '". $email . "' and password = '" . $password . "'");

    // login judgement
    $result_num = mysqli_num_rows($result);
    if ($result_num == 1) {
        $log->info("Login succeeded"); 
        return true;    
    } else {
        $log->info("Login failed"); 
        return false;
    }
}

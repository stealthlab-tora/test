<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to change password
function changePassword($galaxyuserid, $password) {

    Logger::write("Password will be changed from now.");

    $result = array();
    
    // encode password using MD5
    $modifiedPassword = md5($password . SALT_WORD);

    // DB connect
    $db_connection = galaxyDbConnector::getConnection();

    if ($db_connection == null) {
        Logger::write("DB connect failed.");
        $result["status"] = "false";
        $result["error"]  = array(SRV_SYSTEMERROR_NONE);
        return $result;
    }

    // prepare SQL statement
    $stmt = $db_connection->prepare(CHANGE_PASSWORD_QUERY);
    $stmt->bindValue(":GALAXYUSERID",     $galaxyuserid,       PDO::PARAM_STR);
    $stmt->bindValue(":PASSWORD",         $modifiedPassword,   PDO::PARAM_STR);
    $stmt->bindValue(":LASTMODIFIEDDATE", date("Y-m-d H:i:s"), PDO::PARAM_STR);

    // execute SQL
    try {
        $stmt->execute();
    
    } catch(Exception $e) {
        Logger::write("Exception has thrown DB insertion.", $e);
        $result["status"] = "false";
        $result["error"]  = array(SRV_SYSTEMERROR_NONE);
        return $result;
    }
    
    Logger::write("DB insertion succeeded.");
    
    // close prepared statement
    $stmt = null;
    
    $result["status"] = "true";

    return $result;
}

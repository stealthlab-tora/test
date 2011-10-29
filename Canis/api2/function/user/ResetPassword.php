<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to reset password
function resetPassword($email) {

    Logger::write("Password will be reset from now.");

    $result = array();
    
    // Make temp password
    $tempPassword = getRandomString();
    
    // encode password using MD5
    $modifiedPassword = md5($tempPassword . SALT_WORD);

    // DB connect
    $db_connection = galaxyDbConnector::getConnection();

    if ($db_connection == null) {
        Logger::write("DB connect failed.");
        $result["status"] = "false";
        $result["error"]  = array(SRV_SYSTEMERROR_NONE);
        return $result;
    }

    // prepare SQL statement
    $stmt = $db_connection->prepare(RESET_PASSWORD_QUERY);
    $stmt->bindValue(":PASSWORD",         $modifiedPassword,   PDO::PARAM_STR);
    $stmt->bindValue(":EMAIL",            $email,              PDO::PARAM_STR);
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
    $result["temppassword"]  = $tempPassword;
    return $result;
}


function getRandomString() {
    $sCharList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
    
    mt_srand();
    
    $randomString = "";
    $length = rand(6, 8);
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= substr($sCharList, mt_rand(0, strlen($sCharList) - 1), 1);
    }
    
    return $randomString;
}

<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to regist user to galaxy
function registUser($user) {

	Logger::write("User information will be inserted to DB from now.");
	
	$result = array();
	
    // encode password using MD5
	$user["password"] = md5($user["password"] . SALT_WORD);

	// DB connect
	$db_connection = galaxyDbConnector::getConnection();

	if ($db_connection == null) {
		Logger::write("DB connect failed.");
        $result["status"] = "false";
        $result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}

	// prepare SQL statement
	$stmt = $db_connection->prepare(REGIST_USER_QUERY);
	$stmt->bindValue(":FIRSTNAME",        $user["firstname"],   PDO::PARAM_STR);
	$stmt->bindValue(":LASTNAME",         $user["lastname"],    PDO::PARAM_STR);
	$stmt->bindValue(":EMAIL",            $user["email"],       PDO::PARAM_STR);
	$stmt->bindValue(":PASSWORD",         $user["password"],    PDO::PARAM_STR);
	$stmt->bindValue(":ZIPCODE",          $user["zipcode"],     PDO::PARAM_STR);
	$stmt->bindValue(":COUNTRY",          $user["country"],     PDO::PARAM_STR);	
	$stmt->bindValue(":STATE",            $user["state"],       PDO::PARAM_STR);
	$stmt->bindValue(":CITY",             $user["city"],        PDO::PARAM_STR);
	$stmt->bindValue(":STREET",           $user["street"],      PDO::PARAM_STR);
	$stmt->bindValue(":PHONENUMBER",      $user["phonenumber"], PDO::PARAM_STR);
	$stmt->bindValue(":LASTMODIFIEDDATE", date("Y-m-d H:i:s"),  PDO::PARAM_STR);

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
	$result["galaxyuserid"]  = $db_connection->lastInsertId();
	return $result;
}
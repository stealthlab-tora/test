<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to send temp password to user
function sendTempPassword($email, $tempPassword) {

	Logger::write("Temporary password will be sent to user from now.");
	
	$result = array();

	// DB connect
	$db_connection = galaxyDbConnector::getConnection();

	if ($db_connection == null) {
		Logger::write("DB connect failed.");
        $result["status"] = "false";
        $result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}

	// prepare SQL statement
	$stmt = $db_connection->prepare(GET_USER_INFO_QUERY);
    $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);

	// execute SQL
	try {
		$stmt->execute();
	
	} catch(Exception $e) {
		Logger::write("Exception has thrown DB insertion.", $e);
		$result["status"] = "false";
		$result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}


	$users   = $stmt->fetchAll();
    $country = $users[0]["country"];
    $firstname = $users[0]["firstname"];
    $lastname = $users[0]["lastname"];
	
	// close prepared statement
	$stmt = null;

	if ($country == "USA") {
		$subject = PW_RQ_EMAIL_SUBJECT_EN;
		$body    = "Dear " . $firstname . " " . $lastname . "," . PW_RQ_EMAIL_BODY_TOP_EN . $tempPassword . PW_RQ_EMAIL_BODY_BTM_EN;

	} else if ($country == "JAPAN") {
		$subject = PW_RQ_EMAIL_SUBJECT_JP;
		$body    = $lastname . " " . $firstname . "様" . PW_RQ_EMAIL_BODY_TOP_JP . $tempPassword . PW_RQ_EMAIL_BODY_BTM_JP;
		
	}
	 
	$sendMailResult = mail ($email, $subject, $body, PW_RQ_EMAIL_HEADER);
	
	if ($sendMailResult) {
		Logger::write("Send mail succeeded.");
		$result["status"] = "true";

	} else {
		Logger::write("Send mail failed.");
		return returnError(SRV_SYSTEMERROR_NONE);
	}
	
	return $result;
}
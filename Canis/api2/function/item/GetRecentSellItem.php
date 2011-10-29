<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to get recent sell item from DB
function getRecentSellItem($galaxyuserid) {
	
	Logger::write("Item will be got from now.");
	$result = array();
		
	// DB connect
	$db_connection = galaxyDbConnector::getConnection();
	if ($db_connection == null) {
		Logger::write("DB connect failed.");
		$result["status"] = "false";
		$result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}

	
	// Make prepared statement
	$stmt = $db_connection->prepare(GET_RECENT_SELL_ITEM_QUERY);

	// Bind galaxyuserid
	$stmt->bindValue(":GALAXYUSERID", $galaxyuserid, PDO::PARAM_STR);
	
	// execute SQL
	try {
		$stmt->execute();
	
	} catch(Exception $e) {
		Logger::write("Item selection failed.", $e);
		$result["status"] = "false";
		$result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}
	
	Logger::write("Item selection succeeded.");
	
	$result["status"] = "true";
	$result["item"]  = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// close prepared statement
	$stmt = null;
	
	return $result;	
}
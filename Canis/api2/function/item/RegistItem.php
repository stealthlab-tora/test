<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to regist item to DB
function registItem($item) {
	
	Logger::write("item will be inserted to DB from now.");
	
	$result = array();
		
	// DB connect
	$db_connection = galaxyDbConnector::getConnection();
	if ($db_connection == null) {
		Logger::write("DB connect failed.");
		$result["status"] = "false";
		$result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}

	if ($item["locationtype"] == "ZIPCODE") {
		$item["state"] = "";
		$item["city"] = "";

	} else if ($item["locationtype"] == "ADDRESS") {
		$item["zipcode"] = "";
	}	
	
	// prepare SQL statement
	$stmt = $db_connection->prepare(REGIST_ITEM_QUERY);
	$stmt->bindValue(":GALAXYUSERID", $item["galaxyuserid"], PDO::PARAM_STR);
	$stmt->bindValue(":ITEMNAME",     $item["itemname"],     PDO::PARAM_STR);
	$stmt->bindValue(":DESCRIPTION",  $item["description"],  PDO::PARAM_STR);
	$stmt->bindValue(":PRICE",        $item["price"],        PDO::PARAM_STR);
	$stmt->bindValue(":CURRENCY",     $item["currency"],     PDO::PARAM_STR);
	$stmt->bindValue(":LOCATIONTYPE", $item["locationtype"], PDO::PARAM_STR);
	$stmt->bindValue(":ZIPCODE",      $item["zipcode"],      PDO::PARAM_STR);
	$stmt->bindValue(":STATE",        $item["state"],        PDO::PARAM_STR);
	$stmt->bindValue(":CITY",         $item["city"],         PDO::PARAM_STR);
	$stmt->bindValue(":LATITUDE",     $item["latitude"],     PDO::PARAM_STR);
	$stmt->bindValue(":LONGTITUDE",   $item["longtitude"],   PDO::PARAM_STR);
		
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
	$result["itemid"]  = $db_connection->lastInsertId();
	return $result;	
}
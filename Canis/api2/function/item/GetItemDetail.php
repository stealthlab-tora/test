<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to get item detail
function getItemDetail($itemid) {
	
	Logger::write("Item detail will be got from now.");

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
	$stmt = $db_connection->prepare(GET_ITEM_DETAIL_QUERY);

	// Bind currency
	$stmt->bindValue(":ITEMID", $itemid, PDO::PARAM_STR);
	
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
	$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$result["itemid"]       = $items[0]["itemid"];
	$result["galaxyuserid"] = $items[0]["galaxyuserid"];
	$result["itemname"]     = $items[0]["itemname"];
	$result["description"]  = $items[0]["description"];
	$result["price"]        = $items[0]["price"];
	$result["currency"]     = $items[0]["currency"];
	$result["locationtype"] = $items[0]["locationtype"];
	$result["zipcode"]      = $items[0]["zipcode"];
	$result["state"]        = $items[0]["state"];
	$result["city"]         = $items[0]["city"];
	$result["latitude"]     = $items[0]["latitude"];
	$result["longtitude"]   = $items[0]["longtitude"];
	$result["imageurl"]     = $items[0]["imageurl"];
	
	// close prepared statement
	$stmt = null;
	
	return $result;	
}
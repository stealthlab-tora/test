<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/spyc/spyc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to search item from DB
function searchItem($galaxyuserid, $value, $type, $order) {
	
	Logger::write("Item will be searched from now.");
	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$itemconstraints = $constraints["items"];
	$result = array();
		
	// DB connect
	$db_connection = galaxyDbConnector::getConnection();
	if ($db_connection == null) {
		Logger::write("DB connect failed.");
		$result["status"] = "false";
		$result["error"]  = array(SRV_SYSTEMERROR_NONE);
		return $result;
	}

	
	// Get buyer location
	// TODO : get from user input
	
	// prepare SQL statement
	$stmt = $db_connection->prepare(GET_USER_COUNTRY_QUERY);
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

	$galaxyusers = $stmt->fetchAll();
	$buyerCountry = $galaxyusers[0]["country"];
	
	$stmt = null;
	
	if ($buyerCountry == "USA") {
		$currency = "USD";
	} else if ($buyerCountry == "JAPAN") {
		$currency = "JPY";
	}

	
	// Make prepared statement
	if ($type == $itemconstraints["search_type"]["value"]["keyword"]) {
	
		// prepare SQL statement
		if ($order == null || $order == $itemconstraints["search_order"]["value"]["lastmodifieddate_desc"]) {
			$stmt = $db_connection->prepare(SEARCH_ITEM_BY_KEYWORD_ORDER_BY_LMDDESC_QUERY);
		
		} else if ($order == $itemconstraints["search_order"]["value"]["lastmodifieddate_asc"]) {
			$stmt = $db_connection->prepare(SEARCH_ITEM_BY_KEYWORD_ORDER_BY_LMDASC_QUERY);
		
		} else if ($order == $itemconstraints["search_order"]["value"]["price_desc"]) {
			$stmt = $db_connection->prepare(SEARCH_ITEM_BY_KEYWORD_ORDER_BY_PRICEDESC_QUERY);
		
		} else if ($order == $itemconstraints["search_order"]["value"]["price_asc"]) {
			$stmt = $db_connection->prepare(SEARCH_ITEM_BY_KEYWORD_ORDER_BY_PRICEASC_QUERY);
		
		}
		
		$stmt->bindValue(":KEYWORD", "%" . $value . "%", PDO::PARAM_STR);
	
	} else if ($type == $itemconstraints["search_type"]["value"]["barcode"]) {
		
	}

	// Bind currency
	$stmt->bindValue(":CURRENCY", $currency, PDO::PARAM_STR);
	
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
<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/spyc/spyc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/error/Error.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to validate search information
function validateSearchInfo($searchInfo) {

	Logger::write("Search information will be validated from now.");

	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$itemconstraints = $constraints["items"];
	$error = array();
	$valueErrorFlag = false;

	// Validate galaxy user id
	if (!isset($searchInfo["galaxyuserid"])) {
		Logger::write("Galaxyuserid is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($searchInfo["galaxyuserid"])) {
			Logger::write("Galaxyuserid is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
	
		} else {
			Logger::write("User existing check will be done from now.");
				
			// DB connect
			$db_connection = galaxyDbConnector::getConnection();
				
			// prepare SQL statement
			$stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
			$stmt->bindValue(":GALAXYUSERID", $searchInfo["galaxyuserid"], PDO::PARAM_STR);
				
			// execute SQL
			try {
				$stmt->execute();
					
				// get result number
				$result = $stmt->fetchAll();
				$result_num = count($result);
					
				// close prepared statement
				$stmt = null;
					
				// user id existence judgement
				if ($result_num == 1) {
					Logger::write("There is a user which has the requested galaxyuserid.");
	
				} else {
					Logger::write("There is no user which has the requested galaxyuserid.");
					return returnError(APP_SYSTEMERROR_NONE);
				}
					
			} catch(Exception $e) {
				Logger::write("User select operation failed.", $e);
				return returnError(SRV_SYSTEMERROR_NONE);
			}
		}
	}

	
	// Validate value1
	// (Only request/empty/length check will be done here and the other checks will be done after type check.)
	if (!isset($searchInfo["value"])) {
		Logger::write("Search value is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	    $valueErrorFlag = true;
		
	} else {
		if (!checkNotEmpty($searchInfo["value"])) {
			Logger::write("Search value is empty.");
			$error[] = USER_EMPTY_SEARCH_VALUE;
			$valueErrorFlag = true;
			
		} else if (!checkMaxLength($searchInfo["value"], $itemconstraints["search_value"]["max_length"])) {
			Logger::write("Search value is too long.");
			$error[] = USER_INVALID_SEARCH_VALUE;
	
		}
	}
	
	
	// Validate type
	if (!isset($searchInfo["type"])) {
		Logger::write("Search type is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($searchInfo["type"])) {
			Logger::write("Search type is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
	
		} else if (!in_array($searchInfo["type"], $itemconstraints["search_type"]["value"])) {
			Logger::write("Search type is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);
			
		} else if ($valueErrorFlag) {
			// Validate value2
			// (Semantic check will be done here.)
			if ($searchInfo["type"] == $itemconstraints["search_type"]["value"]["keyword"]) {

				if (!checkSpecialCharacter($searchInfo["value"])) {
					Logger::write("Search value is invalid.");
					$error[] = USER_INVALID_SEARCH_VALUE;
			
				}
			} else if ($searchInfo["type"] == $itemconstraints["search_type"]["value"]["barcode"]) {
				
				if (!hasOnlyNumber($searchInfo["value"])) {
					Logger::write("Search value is invalid.");
					$error[] = USER_INVALID_SEARCH_VALUE; 					
				}
			}
		}
	}

	
	// Validate order
	if (isset($searchInfo["order"]) && checkNotEmpty($searchInfo["order"])) {
		if (!in_array($searchInfo["order"], $itemconstraints["search_order"]["value"])) {
			Logger::write("Search order is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);
	
		}
	}


	$result = array();
	
	if (count($error) == 0) {
		Logger::write("Search information is valid.");
		$result["status"] = "true";
	
	} else {
		Logger::write("Search information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}
	
	return $result;
}
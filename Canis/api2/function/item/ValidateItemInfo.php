<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/spyc/spyc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/error/Error.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to validate item information to regist
function validateItemInfo($item, $files) {

	Logger::write("Item information will be validated from now.");
	
	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$itemconstraints = $constraints["items"];
	$error = array();

	// Validate galaxy user id
	if (!isset($item["galaxyuserid"])) {
		Logger::write("Galaxyuserid is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($item["galaxyuserid"])) {
			Logger::write("Galaxyuserid is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
	
		} else {
			Logger::write("User existing check will be done from now.");
			
			// DB connect
			$db_connection = galaxyDbConnector::getConnection();
			
			// prepare SQL statement
			$stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
			$stmt->bindValue(":GALAXYUSERID", $item["galaxyuserid"], PDO::PARAM_STR);
			
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

	
	// Validate item name
	if (!isset($item["itemname"])) {
		Logger::write("Itemname is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($item["itemname"])) {
			Logger::write("Itemname is empty.");
			$error[] = USER_EMPTY_ITEMNAME;
				
		} else if (!checkMaxLength($item["itemname"], $itemconstraints["itemname"]["max_length"])) {
			Logger::write("Itemname is too long.");
			$error[] = USER_INVALID_ITEMNAME;
	
		} else if (!checkSpecialCharacter($item["itemname"])) {
			Logger::write("Itemname is invalid.");
			$error[] = USER_INVALID_ITEMNAME;
	
		}
	}


	// Validate description
	if (!isset($item["description"])) {
		Logger::write("Description is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($item["description"])) {
			Logger::write("Description is empty.");
			$error[] = USER_EMPTY_ITEMDESCRIPTION;
	
		} else if (!checkMaxLength($item["description"], $itemconstraints["description"]["max_length"])) {
			Logger::write("Description is too long.");
			$error[] = USER_INVALID_ITEMDESCRIPTION;
	
		} else if (!checkSpecialCharacter($item["description"])) {
			Logger::write("Description is invalid.");
			$error[] = USER_INVALID_ITEMDESCRIPTION;
	
		}
	}

	
	// Validate price
	if (!isset($item["price"])) {
		Logger::write("Price is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($item["price"])) {
			Logger::write("Price is empty.");
			$error[] = USER_EMPTY_ITEMPRICE;
	
		} else if (!checkMaxLength($item["price"], $itemconstraints["price"]["max_length"])) {
			Logger::write("Price is too long.");
			$error[] = USER_INVALID_ITEMPRICE;
	
		} else if (!checkIsFloat($item["price"], true)) {
			Logger::write("Price is invalid.");
			$error[] = USER_INVALID_ITEMPRICE;
	
		}
	}

	
	// Validate currency
	if (!isset($item["currency"])) {
		Logger::write("Currency is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($item["currency"])) {
			Logger::write("Currency is empty.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else if (!in_array($item["currency"], $itemconstraints["currency"]["value"])) {
			Logger::write("Currency is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		}
	}

	
	// Validate locationtype
	if (!isset($item["locationtype"])) {
		Logger::write("Locationtype is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		if (!checkNotEmpty($item["locationtype"])) {
			Logger::write("Locationtype is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
	
		} else if (!in_array($item["locationtype"], $itemconstraints["locationtype"]["value"])) {
			Logger::write("Locationtype is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else {
			// When locationtype is zipcode
			if ($item["locationtype"] == $itemconstraints["locationtype"]["value"]["zipcode"]) {

				// Validate zipcode
				if (!isset($item["zipcode"])) {
					Logger::write("Zipcode is not requested.");
					return returnError(APP_SYSTEMERROR_NONE);
				
				} else {
					if (!checkNotEmpty($item["zipcode"])) {
						Logger::write("Zipcode is empty.");
						$error[] = USER_EMPTY_ZIPCODE;
				
					} else if (!checkMaxLength($item["zipcode"], $itemconstraints["zipcode"]["max_length"])) {
						Logger::write("Zipcode is too long.");
						$error[] = USER_INVALID_ZIPCODE;
				
					} else if (!hasOnlyNumber($item["zipcode"])) {
						Logger::write("Zipcode is invalid.");
						$error[] = USER_INVALID_ZIPCODE;
				
					}
				}
			}		

			
			// When locationtype is address
			if ($item["locationtype"] == $itemconstraints["locationtype"]["value"]["address"]) {
	
				// Validate state
				if (!isset($item["state"])) {
					Logger::write("State is not requested.");
					return returnError(APP_SYSTEMERROR_NONE);
				
				} else {
					if (!checkNotEmpty($item["state"])) {
						Logger::write("State is empty.");
						$error[] = USER_EMPTY_LOCATION_STATE;
							
					} else if (!checkMaxLength($item["state"], $itemconstraints["state"]["max_length"])) {
						Logger::write("State is too long.");
						$error[] = USER_INVALID_LOCATION_STATE;
				
					} else if (!checkSpecialCharacter($item["state"]) || hasNumber($item["state"]) || hasMark($item["state"])) {
						Logger::write("State is invalid.");
						$error[] = USER_INVALID_LOCATION_STATE;
				
					}
				}
				
				
				// Validate city
				if (!isset($item["city"])) {
					Logger::write("City is not requested.");
					return returnError(APP_SYSTEMERROR_NONE);
				
				} else {
					if (!checkNotEmpty($item["city"])) {
						Logger::write("City is empty.");
						$error[] = USER_EMPTY_LOCATION_CITY;
							
					} else if (!checkMaxLength($item["city"], $itemconstraints["city"]["max_length"])) {
						Logger::write("City is too long.");
						$error[] = USER_INVALID_LOCATION_CITY;
				
					} else if (!checkSpecialCharacter($item["city"]) || hasNumber($item["city"]) || hasMark($item["city"])) {
						Logger::write("City is invalid.");
						$error[] = USER_INVALID_LOCATION_CITY;
				
					}
				}
			}
		}

		// When locationtype is address
		if ($item["locationtype"] == $itemconstraints["locationtype"]["value"]["geolocation"]) {
		
			// Validate latitude
			if (!isset($item["latitude"])) {
				Logger::write("Latitude is not requested.");
				return returnError(APP_SYSTEMERROR_NONE);
		
			} else {
				if (!checkNotEmpty($item["latitude"])) {
					Logger::write("Latitude is empty.");
					return returnError(APP_SYSTEMERROR_NONE);
		
				} else if (!checkIsFloat($item["latitude"])) {
					Logger::write("Latitude is invalid.");
					return returnError(APP_SYSTEMERROR_NONE);
		
				}
			}
		
		
			// Validate longtitude
			if (!isset($item["longtitude"])) {
				Logger::write("Longtitude is not requested.");
				return returnError(APP_SYSTEMERROR_NONE);
		
			} else {
				if (!checkNotEmpty($item["longtitude"])) {
					Logger::write("Longtitude is empty.");
					return returnError(APP_SYSTEMERROR_NONE);
		
				} else if (!checkIsFloat($item["longtitude"])) {
					Logger::write("Longtitude is invalid.");
					return returnError(APP_SYSTEMERROR_NONE);
		
				}
			}
		}
	}


	// Validate thumbnail file
	if (isset($files["imageThumbnail"]) && $files["imageThumbnail"]["size"] != 0) {
			
		if ($files["imageThumbnail"]["error"] != 0) {
			Logger::write("ImageThumbnail upload failed.");
			return returnError(SRV_SYSTEMERROR_NONE);
	
		}
	}


	// Validate image file
	if (isset($files["image"]) && $files["image"]["size"] != 0) {

		if ($files["image"]["error"] != 0) {
			Logger::write("Image upload failed.");
			return returnError(SRV_SYSTEMERROR_NONE);
	
		}
	}

	
	$result = array();
	
	if (count($error) == 0) {
		Logger::write("item information is valid.");
		$result["status"] = "true";
	
	} else {
		Logger::write("item information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}
	
	return $result;
}


// function to validate item id
function validateItemId($item) {

	Logger::write("Itemid will be validated from now.");

	$error = array();

	// Validate galaxy user id
	if (!isset($item["itemid"])) {
		Logger::write("Itemid is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($item["itemid"])) {
			Logger::write("Itemid is empty.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else {
			Logger::write("Item existing check will be done from now.");
				
			// DB connect
			$db_connection = galaxyDbConnector::getConnection();
				
			// prepare SQL statement
			$stmt = $db_connection->prepare(ITEM_EXIST_CHECK_QUERY);
			$stmt->bindValue(":ITEMID", $item["itemid"], PDO::PARAM_STR);
				
			// execute SQL
			try {
				$stmt->execute();
					
				// get result number
				$result = $stmt->fetchAll();
				$result_num = count($result);
					
				// close prepared statement
				$stmt = null;
					
				// item existence judgement
				if ($result_num == 1) {
					Logger::write("There is an item which has the requested itemid.");

				} else {
					Logger::write("There is no item which has the requested itemid.");
					return returnError(APP_SYSTEMERROR_NONE);
				}
					
			} catch(Exception $e) {
				Logger::write("Item select operation failed.", $e);
				return returnError(SRV_SYSTEMERROR_NONE);
			}
		}
	}
	

	$result = array();

	if (count($error) == 0) {
		Logger::write("Item information is valid.");
		$result["status"] = "true";

	} else {
		Logger::write("Item information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}

	return $result;
}
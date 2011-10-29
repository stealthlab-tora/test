<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/spyc/spyc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/error/Error.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/UserConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to validate user information to regist
function validateUserInfoToRegist($user) {

	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$userconstraints = $constraints["users"];
	$error = array();
	
	// Validate first name
	if (!isset($user["firstname"])) {
		Logger::write("Firstname is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["firstname"])) {
			Logger::write("Firstname is empty.");
			$error[] = USER_EMPTY_FIRSTNAME;
			
		} else if (!checkMaxLength($user["firstname"], $userconstraints["firstname"]["max_length"])) {
			Logger::write("Firstname is too long.");
			$error[] = USER_INVALID_FIRSTNAME;

		} else if (!checkSpecialCharacter($user["firstname"]) || hasNumber($user["firstname"]) || hasMark($user["firstname"])) {
			Logger::write("Firstname is invalid.");
			$error[] = USER_INVALID_FIRSTNAME;

		}
	}

	
	// Validate last name
	if (!isset($user["lastname"])) {
		Logger::write("Lasttname is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["lastname"])) {
			Logger::write("Lastname is empty.");
			$error[] = USER_EMPTY_LASTNAME;
			
		} else if (!checkMaxLength($user["lastname"], $userconstraints["lastname"]["max_length"])) {
			Logger::write("Lastname is too long.");
			$error[] = USER_INVALID_LASTNAME;

		} else if (!checkSpecialCharacter($user["lastname"]) || hasNumber($user["lastname"]) || hasMark($user["lastname"])) {
			Logger::write("Lastname is invalid.");
			$error[] = USER_INVALID_LASTNAME;

		}
	}

	
	// Validate email
	if (!isset($user["email"])) {
		Logger::write("Email is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {	
    	if (!checkEmail($user["email"])) {
            Logger::write("Email is invalid.");
		    $error[] = USER_INVALID_EMAIL;

    	} else {
    		Logger::write("Duplicate user check will be done from now.");
    		
    		// DB connect
    		$db_connection = galaxyDbConnector::getConnection();
    		
    		// prepare SQL statement
    		$stmt = $db_connection->prepare(USER_CHECK_QUERY);
    		$stmt->bindValue(":EMAIL", $user["email"], PDO::PARAM_STR);
    		
    		// execute SQL
    		try {
    			$stmt->execute();

    			// get result number
    			$result = $stmt->fetchAll();
    			$result_num = count($result);
    			
    			// close prepared statement
    			$stmt = null;
    			
    			// registration judgement
    			if ($result_num != 0) {
    				Logger::write("Email has been already registered.");
    				$error[] = USER_INVALID_EMAIL;
    			
    			} else {
    				Logger::write("There is no user which has same email address.");
    			}

    		} catch(Exception $e) {
    			Logger::write("User select operation failed.", $e);
    			return returnError(SRV_SYSTEMERROR_NONE);
    		}
    	}
	}

	
	// Validate password
	if (!isset($user["password"])) {
		Logger::write("Password is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
		
	} else {
		if (!checkNotEmpty($user["password"])) {
			Logger::write("Password is empty.");
			$error[] = USER_EMPTY_PASSWORD;
		
		} else if (!checkMinLength($user["password"], $userconstraints["password"]["min_length"])) {
            Logger::write("Password is too short.");
			$error[] = USER_TOOSHORT_PASSWORD;
			
		} else if (!checkMaxLength($user["password"], $userconstraints["password"]["max_length"])) {
			Logger::write("Password is too long.");
			$error[] = USER_INVALID_PASSWORD;		
			
		} else if (!checkPassword($user["password"])) {
    		Logger::write("Password is invalid.");
	    	$error[] = USER_INVALID_PASSWORD;
	    	
		} else {
			if (!isset($user["password2"])) {
				Logger::write("Password2 is not requested.");
				return returnError(APP_SYSTEMERROR_NONE);
				
			} else if ($user["password"] != $user["password2"]) {
				Logger::write("Passwords are not matched.");
				$error[] = USER_UNMATCH_PASSWORD;			

			}	
		}
	}

	
	// Validate zipcode
	if (!isset($user["zipcode"])) {
		Logger::write("Zipcode is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
		
	} else {
		if (!checkNotEmpty($user["zipcode"])) {
			Logger::write("Zipcode is empty.");
			$error[] = USER_EMPTY_ZIPCODE;
			
		} else if (!checkMaxLength($user["zipcode"], $userconstraints["zipcode"]["max_length"])) {
			Logger::write("Zipcode is too long.");
			$error[] = USER_INVALID_ZIPCODE;

		} else if (!hasOnlyNumber($user["zipcode"])) {
			Logger::write("Zipcode is invalid.");
			$error[] = USER_INVALID_ZIPCODE;

		}
	}
	
	
	// Validate country
	if (!isset($user["country"])) {
		Logger::write("Country is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
		
	} else {
	    if (!checkNotEmpty($user["country"])) {
			Logger::write("Country is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
			
		}  else if (!in_array($user["country"], $userconstraints["country"]["value"])) {
			Logger::write("Country is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		}
	}

	
	// Validate state
	if (!isset($user["state"])) {
		Logger::write("State is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["state"])) {
			Logger::write("State is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
			
		} else if (!checkMaxLength($user["state"], $userconstraints["state"]["max_length"])) {
			Logger::write("State is too long.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else if (!checkSpecialCharacter($user["state"]) || hasNumber($user["state"]) || hasMark($user["state"])) {
			Logger::write("State is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		}
	}


	// Validate city
	if (!isset($user["city"])) {
		Logger::write("City is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["city"])) {
			Logger::write("City is empty.");
			$error[] = USER_EMPTY_CITY;
			
		} else if (!checkMaxLength($user["city"], $userconstraints["city"]["max_length"])) {
			Logger::write("City is too long.");
			$error[] = USER_INVALID_CITY;

		} else if (!checkSpecialCharacter($user["city"]) || hasNumber($user["city"]) || hasMark($user["city"], " ")) {
			Logger::write("City is invalid.");
			$error[] = USER_INVALID_CITY;

		}
	}
		
	// Validate street
	if (!isset($user["street"])) {
		Logger::write("Street is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["street"])) {
			Logger::write("Street is empty.");
			$error[] = USER_EMPTY_STREET;
			
		} else if (!checkMaxLength($user["street"], $userconstraints["street"]["max_length"])) {
			Logger::write("Street is too long.");
			$error[] = USER_INVALID_STREET;

		} else if (!checkSpecialCharacter($user["street"])) {
			Logger::write("Street is invalid.");
			$error[] = USER_INVALID_STREET;

		}
	}

	
	// Validate phonenumber
	if (!isset($user["phonenumber"])) {
		Logger::write("Phonenumber is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
		
	} else {
		if (!checkNotEmpty($user["phonenumber"])) {
			Logger::write("Phonenumber is empty.");
			$error[] = USER_EMPTY_PHONENUMBER;

		} else if (!checkPhoneNumber($user["phonenumber"])) {
			Logger::write("Phonenumber is invalid.");
			$error[] = USER_INVALID_PHONENUMBER;

		}
	}
	
	$result = array();
	
	if (count($error) == 0) {
		Logger::write("User information is valid.");
		$result["status"] = "true";
	
	} else {
		Logger::write("User information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}
	
	return $result;
}


// function to validate user information to regist
function validateHalfUserInfoToRegist($user) {
	
	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$userconstraints = $constraints["users"];
	$error = array();

	// Validate first name
	if (!isset($user["firstname"])) {
		Logger::write("Firstname is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["firstname"])) {
			Logger::write("Firstname is empty.");
			$error[] = USER_EMPTY_FIRSTNAME;
				
		} else if (!checkMaxLength($user["firstname"], $userconstraints["firstname"]["max_length"])) {
			Logger::write("Firstname is too long.");
			$error[] = USER_INVALID_FIRSTNAME;

		} else if (!checkSpecialCharacter($user["firstname"]) || hasNumber($user["firstname"]) || hasMark($user["firstname"])) {
			Logger::write("Firstname is invalid.");
			$error[] = USER_INVALID_FIRSTNAME;

		}
	}


	// Validate last name
	if (!isset($user["lastname"])) {
		Logger::write("Lasttname is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["lastname"])) {
			Logger::write("Lastname is empty.");
			$error[] = USER_EMPTY_LASTNAME;
				
		} else if (!checkMaxLength($user["lastname"], $userconstraints["lastname"]["max_length"])) {
			Logger::write("Lastname is too long.");
			$error[] = USER_INVALID_LASTNAME;

		} else if (!checkSpecialCharacter($user["lastname"]) || hasNumber($user["lastname"]) || hasMark($user["lastname"])) {
			Logger::write("Lastname is invalid.");
			$error[] = USER_INVALID_LASTNAME;

		}
	}


	// Validate zipcode
	if (!isset($user["zipcode"])) {
		Logger::write("Zipcode is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["zipcode"])) {
			Logger::write("Zipcode is empty.");
			$error[] = USER_EMPTY_ZIPCODE;
				
		} else if (!checkMaxLength($user["zipcode"], $userconstraints["zipcode"]["max_length"])) {
			Logger::write("Zipcode is too long.");
			$error[] = USER_INVALID_ZIPCODE;

		} else if (!hasOnlyNumber($user["zipcode"])) {
			Logger::write("Zipcode is invalid.");
			$error[] = USER_INVALID_ZIPCODE;

		}
	}


	// Validate country
	if (!isset($user["country"])) {
		Logger::write("Country is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["country"])) {
			Logger::write("Country is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
				
		} else if (!in_array($user["country"], $userconstraints["country"]["value"])) {
	        Logger::write("Country is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		}
	}


	// Validate state
	if (!isset($user["state"])) {
		Logger::write("State is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["state"])) {
			Logger::write("State is empty.");
			return returnError(APP_SYSTEMERROR_NONE);
				
		} else if (!checkMaxLength($user["state"], $userconstraints["state"]["max_length"])) {
			Logger::write("State is too long.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else if (!checkSpecialCharacter($user["state"]) || hasNumber($user["state"]) || hasMark($user["state"])) {
			Logger::write("State is invalid.");
			return returnError(APP_SYSTEMERROR_NONE);

		}
	}


	// Validate city
	if (!isset($user["city"])) {
		Logger::write("City is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["city"])) {
			Logger::write("City is empty.");
			$error[] = USER_EMPTY_CITY;
				
		} else if (!checkMaxLength($user["city"], $userconstraints["city"]["max_length"])) {
			Logger::write("City is too long.");
			$error[] = USER_INVALID_CITY;

		} else if (!checkSpecialCharacter($user["city"]) || hasNumber($user["city"]) || hasMark($user["city"], " ")) {
			Logger::write("City is invalid.");
			$error[] = USER_INVALID_CITY;

		}
	}

	// Validate street
	if (!isset($user["street"])) {
		Logger::write("Street is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["street"])) {
			Logger::write("Street is empty.");
			$error[] = USER_EMPTY_STREET;
				
		} else if (!checkMaxLength($user["street"], $userconstraints["street"]["max_length"])) {
			Logger::write("Street is too long.");
			$error[] = USER_INVALID_STREET;

		} else if (!checkSpecialCharacter($user["street"])) {
			Logger::write("Street is invalid.");
			$error[] = USER_INVALID_STREET;

		}
	}


	$result = array();

	if (count($error) == 0) {
		Logger::write("User information is valid.");
		$result["status"] = "true";

	} else {
		Logger::write("User information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}

	return $result;
}


// function to validate user information to login
function validateUserInfoToLogin($user) {
	
	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$userconstraints = $constraints["users"];
	
    $errorFlag = false;
	
	// Validate email
	if (!isset($user["email"])) {
		Logger::write("Email is not requested.");
		$errorFlag = true;

	} else if (!checkEmail($user["email"])) {
		Logger::write("Email is invalid.");
		$errorFlag = true;
	}


	// Validate password
	if (!isset($user["password"])) {
		Logger::write("Password is not requested.");
		$errorFlag = true;
		
	} else if (!checkMinLength($user["password"], $userconstraints["password"]["min_length"])) {
		Logger::write("Password is too short.");
		$errorFlag = true;
			
	} else if (!checkMaxLength($user["password"], $userconstraints["password"]["max_length"])) {
		Logger::write("Password is too long.");
		$errorFlag = true;
			
	} else if (!checkPassword($user["password"])) {
		Logger::write("Password is invalid.");
		$errorFlag = true;
	}

	$result = array();
	
	if (!$errorFlag) {
		Logger::write("User information is valid.");
		$result["status"] = "true";
	
	} else {
		Logger::write("User information is invalid.");
		$result["status"] = "false";
		$result["error"] = array(USER_LOGIN_FAILURE_NONE);
	}
	
	return $result;
}


// function to validate galaxyuserid
function validateUserEmail($user) {

	Logger::write("Email will be validated from now.");

	$error = array();

	// Validate email
	if (!isset($user["email"])) {
		Logger::write("Email is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {	
    	if (!checkEmail($user["email"])) {
            Logger::write("Email is invalid.");
		    $error[] = USER_EMPTY_EMAIL;

    	} else {
    		Logger::write("User existence check will be done from now.");
    		
    		// DB connect
    		$db_connection = galaxyDbConnector::getConnection();
    		
    		// prepare SQL statement
    		$stmt = $db_connection->prepare(USER_CHECK_QUERY);
    		$stmt->bindValue(":EMAIL", $user["email"], PDO::PARAM_STR);
    		
    		// execute SQL
    		try {
    			$stmt->execute();

    			// get result number
    			$result = $stmt->fetchAll();
    			$result_num = count($result);
    			
    			// close prepared statement
    			$stmt = null;
    			
    			// user existence judgement
    			if ($result_num == 1) {
    				Logger::write("There is a user which has requested email address.");
    			
    			} else {
    				Logger::write("There is no user which has requested email address.");
    				$error[] = USER_INVALID_EMAIL;
    			}

    		} catch(Exception $e) {
    			Logger::write("User select operation failed.", $e);
    			return returnError(SRV_SYSTEMERROR_NONE);
    		}
    	}
	}

	$result = array();
	
	if (count($error) == 0) {
		Logger::write("User information is valid.");
		$result["status"] = "true";
	
	} else {
		Logger::write("User information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}
	
	return $result;
}


// function to validate user information to change password
function validateUserInfoToChangePassword($user) {

	$constraints = Spyc::YAMLLoad($_SERVER["DOCUMENT_ROOT"] . "/constant/UserInputConstraint.yml");
	$userconstraints = $constraints["users"];
	$error = array();

	// Validate galaxyuserid
	if (!isset($user["galaxyuserid"])) {
		Logger::write("Galaxyuserid is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	}
	

	// Validate old password
	if (!isset($user["oldpassword"])) {
		Logger::write("Old password is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);
	
	} else {
		Logger::write("User existence check will be done from now.");
		
		// DB connect
		$db_connection = galaxyDbConnector::getConnection();
		
		// prepare SQL statement
		$stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID_AND_PW);
		$stmt->bindValue(":GALAXYUSERID", $user["galaxyuserid"], PDO::PARAM_STR);
		$stmt->bindValue(":PASSWORD",     $user["oldpassword"],  PDO::PARAM_STR);
		
		// execute SQL
		try {
			$stmt->execute();
		
			// get result number
			$result = $stmt->fetchAll();
			$result_num = count($result);
			 
			// close prepared statement
			$stmt = null;
			 
			// user existence judgement
			if ($result_num == 1) {
				Logger::write("There is a user which has requested galaxyuserid and password.");
				 
			} else {
				Logger::write("There is no user which has requested galaxyuserid and password.");
				returnError(APP_SYSTEMERROR_NONE);
			}
		
		} catch(Exception $e) {
			Logger::write("User select operation failed.", $e);
			return returnError(SRV_SYSTEMERROR_NONE);
		}
	}
	

	// Validate new password
	if (!isset($user["newpassword"])) {
		Logger::write("New password is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["newpassword"])) {
			Logger::write("New password is empty.");
			$error[] = USER_EMPTY_PASSWORD;

		} else if (!checkMinLength($user["newpassword"], $userconstraints["password"]["min_length"])) {
			Logger::write("New password is too short.");
			$error[] = USER_TOOSHORT_PASSWORD;
				
		} else if (!checkMaxLength($user["newpassword"], $userconstraints["password"]["max_length"])) {
			Logger::write("New password is too long.");
			$error[] = USER_INVALID_PASSWORD;
				
		} else if (!checkPassword($user["newpassword"])) {
			Logger::write("New password is invalid.");
			$error[] = USER_INVALID_PASSWORD;
			
		} else if ($user["newpassword"] == $user["oldpassword"]) {
			Logger::write("New password is same as Old password.");
			$error[] = USER_INVALID_PASSWORD;
				
		} else {
			if (!isset($user["newpassword2"])) {
				Logger::write("New password2 is not requested.");
				return returnError(APP_SYSTEMERROR_NONE);

			} else if ($user["newpassword"] != $user["newpassword2"]) {
				Logger::write("New passwords are not matched.");
				$error[] = USER_UNMATCH_PASSWORD;

			}
		}
	}

	
	$result = array();

	if (count($error) == 0) {
		Logger::write("User information is valid.");
		$result["status"] = "true";

	} else {
		Logger::write("User information is invalid.");
		$result["status"] = "false";
		$result["error"] = $error;
	}

	return $result;
}

// function to validate galaxyuserid
function validateGalaxyuserId($user) {

	Logger::write("Galaxyuserid will be validated from now.");

	// Validate galaxy user id
	if (!isset($user["galaxyuserid"])) {
		Logger::write("Galaxyuserid is not requested.");
		return returnError(APP_SYSTEMERROR_NONE);

	} else {
		if (!checkNotEmpty($user["galaxyuserid"])) {
			Logger::write("Galaxyuserid is empty.");
			return returnError(APP_SYSTEMERROR_NONE);

		} else {
			Logger::write("User existing check will be done from now.");

			// DB connect
			$db_connection = galaxyDbConnector::getConnection();

			// prepare SQL statement
			$stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
			$stmt->bindValue(":GALAXYUSERID", $user["galaxyuserid"], PDO::PARAM_STR);

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

	$result = array();

	Logger::write("Requested galaxyuserid is valid.");
	$result["status"] = "true";

	return $result;
}

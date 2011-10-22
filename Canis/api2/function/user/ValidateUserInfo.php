<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";

// function to validate user information
function validateUserInfo($user) {
	
	$error = array();
	
	// Validate first name
	if (!isset($_user["firstname"])) {
		Logger::write("firstname is not requested.");
		$error[] = "error_code";
	} else {
		if (!checkNotEmpty($_user["firstname"])) {
			$error[] = "error_code";
			
		} else if (!checkLength($_user["firstname"], 1, 20)) {
			$error[] = "error_code";

		} else if (!checkSpecialCharacter($_user["firstname"]) || hasNumber($_user["firstname"]) || hasMark($_user["firstname"])) {
			$error[] = "error_code";
		}
	}

	
	// Validate last name
	if (!isset($_user["lastname"])) {
		Logger::write("lasttname is not requested.");
		$error[] = "error_code";
	} else {
		if (!checkNotEmpty($_user["lastname"])) {
			$error[] = "error_code";
			
		} else if (!checkLength($_user["lastname"], 1, 20)) {
			$error[] = "error_code";

		} else if (!checkSpecialCharacter($_user["lastname"]) || hasNumber($_user["lastname"]) || hasMark($_user["lastname"])) {
			$error[] = "error_code";
		}
	}

	
	// Validate email
	if (!isset($_user["email"])) {
		Logger::write("Email is not requested.");
		$error[] = "error_code";
	} else {	
    	if (!checkEmail($_user["email"])) {
            Logger::write("Requsted email is invalid.");
		    $error[] = "error_code";
    	}
	}
	
	// Validate password
	if (!isset($_user["password"])) {
		Logger::write("password is not requested.");
		$error[] = "error_code";
	} else {
		if (!checkPassword($_user["password"])) {
    		Logger::write("Requsted password is invalid.");
	    	$error[] = "error_code";
		} else {
			if (!isset($_user["password2"])) {
				Logger::write("password2 is not requested.");
				$error[] = "error_code";
			} else if ($_user["password"] != $_user["password2"]) {
				Logger::write("Both passwords are not matched.");
				$error[] = "error_code";			
			}
			
		}
	}

	// Validate zipcode
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
	
	// Validate country
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
	
	// Validate state
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
		
	// Validate city
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
		
	// Validate street
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
		
	// Validate phonenumber
	if (!isset($_user["zipcode"])) {
		Logger::write("zipcode is not requested.");
		$error[] = "error_code";
	}
	
	
    if (count($error) != 0) {
    	return $error;
    }

    return true;
}
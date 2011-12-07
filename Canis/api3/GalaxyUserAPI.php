<?php

/**
* The API around User
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


if (!isset($_POST["action"])) {
	WarnLogger::write("Request without action has come.");

} else if ($_POST["action"] == "REQUEST_PASSWORD_CHANGE") {

	DebugLogger::write("Sending password change link operation starts.");
	
	$user          = new User($_POST);
	$userValidator = new UserValidator($user);
	$userOperator  = new UserOperator($user);
	
	$result = null;
	
	// Validation
	$validateResult = $userValidator->validateExistingEmail();
	if ($validateResult["status"] == "true") {
		// Send password change link
	    $result = $userOperator->sendPasswordChangeLink();
	
	} else {
		$result = OutputUtil::getSuccessOutput();
	}
	
	echo(json_encode($result));
		
	DebugLogger::write("Sending password change link operation ends.");


} else if ($_POST["action"] == "LOGIN") {
    
    DebugLogger::write("User login operation starts.");

    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    $userOperator  = new UserOperator($user);
    
    // validation
    $validateResult = $userValidator->validateUserInfoToLogin();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }
    
    // login
    $loginResult = $userOperator->login();
    echo(json_encode($loginResult));
    
    DebugLogger::write("User login operation ends.");
    
} else if ($_POST["action"] == "REGISTER_USER") {

    DebugLogger::write("User register operation starts.");

    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    $userOperator  = new UserOperator($user);
    
    // Validation
    $validateResult = $userValidator->validateUserInfoToRegister();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {    
    }
    
    // Regist user
    $registerResult = $userOperator->registerUser();
    if ($registerResult["status"] == "true") {
        InfoLogger::write("User register operation succeeded.");
    
    } else {
        InfoLogger::write("User register operation failed.");
    
    }
    
    echo(json_encode($registerResult));
    
    DebugLogger::write("User register operation ends.");
    


} else if ($_POST["action"] == "VALIDATE_HALF_USER_INFO") {
    
    DebugLogger::write("User info validate operation start.");
    
    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    
    $validateResult = $userValidator->validateHalfUserInfoToRegister();
    echo(json_encode($validateResult));
    
    DebugLogger::write("User info validate operation done.");
    
    
} else if ($_POST["action"] == "VALIDATE_USER_INFO") {
    
    DebugLogger::write("User info validate operation start.");

    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    
    $validateResult = $userValidator->validateUserInfoToRegister($_POST);
    echo(json_encode($validateResult));
    
    DebugLogger::write("User info validate operation done.");

    
} else if ($_POST["action"] == "GET_RECENT_SELL_ITEM") {

    DebugLogger::write("Get recent sell item operation starts.");

    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    $userOperator  = new UserOperator($user);
    
    // Validate galaxyuserid
    $validateResult = $userValidator->validateGalaxyuserid();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {    
    }
    
    // Get recent sell item
    $getRecentSellItemResult = $userOperator->getRecentSellItem();
    echo(json_encode($getRecentSellItemResult));
    
    DebugLogger::write("Get recent sell item  operation ends.");
    
    
} else if ($_POST["action"] == "GET_THREAD_LIST") {
    
    DebugLogger::write("Get thread list operation starts.");
    
    $user          = new User($_POST);
    $userValidator = new UserValidator($user);
    $userOperator  = new UserOperator($user);
    
    // Validate galaxyuserid
    $validateResult = $userValidator->validateGalaxyuserid();
    if ($validateResult["status"] != "true") {
	    echo(json_encode($validateResult));
	    return;
	    
    } else {
    }
    
    // Get thread list
    $getThreadListResult = $userOperator->getThreadList();
    echo(json_encode($getThreadListResult));
    
    DebugLogger::write("Get thread list operation ends.");
    
    
} else if ($_POST["action"] == "RATE_SELLER") {

	DebugLogger::write("Rate user operation starts.");

	$rate		   = new Rate($_POST);
	$rateValidator = new RateValidator($rate);
	$rateOperator  = new RateOperator($rate);
	$thread		     = new Thread($_POST);
	$threadValidator = new ThreadValidator($thread);
	$threadOperator  = new ThreadOperator($thread);
	
	// Validate rate information
	$validateResult = $rateValidator->validateRateInfo();
	if ($validateResult["status"] != "true") {
		echo(json_encode($validateResult));
		return;
	  
	} else {
	}

	// Validate thread id
	$validateThreadidResult = $threadValidator->validateThreadid();
	if ($validateThreadidResult["status"] != "true") {
		echo(json_encode($validateThreadidResult));
		return;
	
	} else {
	}

	// Rate user
	$rateUserResult = $rateOperator->rateUser();
	if ($rateUserResult["status"] != "true") {
		echo(json_encode($rateUserResult));
		return;
	
	} else {
	}
	
	// Change thread status
	$changeThreadstatusResult = $threadOperator->changeThreadstatus("CLOSED");
	if ($changeThreadstatusResult["status"] == "true") {
		echo(json_encode($rateUserResult));
	
	} else {
		echo(json_encode($changeThreadstatusResult));
	}
	

	DebugLogger::write("Rate user operation ends.");


} else if ($_POST["action"] == "GET_AVERAGE_RATE") {

	DebugLogger::write("Get average rate operation starts.");

	$user          = new User($_POST);
	$userValidator = new UserValidator($user);
	$userOperator  = new UserOperator($user);

	// Validate galaxyuserid
	$validateResult = $userValidator->validateGalaxyuserid();
	if ($validateResult["status"] != "true") {
		echo(json_encode($validateResult));
		return;
	  
	} else {
	}

	// Get rating
	$getAverageRateResult = $userOperator->getAverageRate();
	echo(json_encode($getAverageRateResult));

	DebugLogger::write("Get average rate operation ends.");

	
} else if ($_POST["action"] == "GET_UNREAD_MESSAGE_NUMBER") {

	DebugLogger::write("Get unread message number operation starts.");

	$user          = new User($_POST);
	$userValidator = new UserValidator($user);
	$userOperator  = new UserOperator($user);

	// Validate galaxyuserid
	$validateResult = $userValidator->validateGalaxyuserid();
	if ($validateResult["status"] != "true") {
		echo(json_encode($validateResult));
		return;
		 
	} else {
	}

	// Get unread message number
	$getUnreadMessageNumberResult = $userOperator->getUnreadMessageNumber();
	echo(json_encode($getUnreadMessageNumberResult));

	DebugLogger::write("Get unread message number operation ends.");


} else {
    WarnLogger::write("Request without proper action has come.");
}

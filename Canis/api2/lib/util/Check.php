<?php

// null and empty checker
function checkNotEmpty($string)
{
	if ($string == null || $string == "") {
		return false;
		
	} else if (trim($string, " 　") == "") {
		return false;
	}

	return true;
}


// email checker
function checkEmail($email)
{
	if (!checkMaxLength($email, 256)) {
		return false;
	}
	
	// this regular exception is from HTML_QuickForm_Rule_Email class in pear/HTML_QuickForm-3.2.13 package 
    $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/D';
    return preg_match($regex, $email);
}


// password checker
function checkPassword($password)
{
	// character check
    if (preg_match('/^[\!-\~]+$/D', $password)) {
// For string type check
//	    if (hasSmallAlphabet($password) && hasCapitalAlphabet($password) && hasNumber($password)) {
//	    	return true;
//	    	
//	    } else {
//	    	return false;
//	    }
	    return true;
    	
    } else {
    	return false;
    }
}

// phonenumber checker
function checkPhoneNumber($phonenumber)
{
	$modifiedPhonenumber = str_replace("+", "", $phonenumber);
	$modifiedPhonenumber = str_replace("-", "", $modifiedPhonenumber);
	if (strlen($modifiedPhonenumber) > 15) {
		return false;
	}
	
	$regex = '/^\+?[0-9][0-9\-]+[0-9]$/D';
	return preg_match($regex, $phonenumber);
}

// special character checker
function checkSpecialCharacter($text)
{
	return true;
	$regex = '/[\x85-\x88][\x40-\x9E]|[\xEA-\xFC][\xA5-\xFC]/';
	return !preg_match($regex, $text);
}


// float checker
function checkIsFloat($float, $unsignedFlag = false)
{
	$regex = null;
	
	if ($unsignedFlag) {
		$regex = '/^[1-9]+[0-9]*(\.[0-9]+)?$/D';
	} else {
	    $regex = '/^-?[1-9]+[0-9]*(\.[0-9]+)?$/D';
	}
	
    return preg_match($regex, $float);
}


// length checker
function checkMinLength($text, $min)
{
    $length = strlen($text);
	
    if ($min > $length) {
    	return false;
    }
    
	return true;
}


// length checker
function checkMaxLength($text, $max)
{
	$length = strlen($text);

	if ($max < $length) {
		return false;
	}

	return true;
}


// Small alphabet checker
function hasSmallAlphabet($string)
{
	return preg_match('/[a-z]/', $string);
}


// Capital alphabet checker
function hasCapitalAlphabet($string)
{
	return preg_match('/[A-Z]/', $string);
}


// number checker
function hasNumber($string)
{
	return preg_match('/[0-9]/', $string);
}


// number checker
function hasOnlyNumber($string)
{
	return preg_match('/^[0-9]+$/D', $string);
}


// mark checker
function hasMark($string, $nonTargetStrings = null)
{
	if ($nonTargetStrings != null) {
		$string = str_replace($nonTargetStrings, "", $string);
	}
	
	return preg_match('/[\!-\/\:-\@\^-\`\{-\~]/', $string);
}

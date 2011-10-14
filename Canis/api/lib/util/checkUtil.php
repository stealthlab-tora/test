<?php

// null and empty checker
function checkNotEmpty($string)
{
	if ($string == null || $string == "") {
		return false;
	}
	
	return true;
}


// email checker
function checkEmail($email)
{
	// this regular exception is from HTML_QuickForm_Rule_Email class in pear/HTML_QuickForm-3.2.13 package 
    $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/D';
    return preg_match($regex, $email);
}


// password checker
function checkPassword($password)
{
	// length check
	if (strlen($password) >= 8 && strlen($password) <= 32) {
		
		// character check
	    if (preg_match('/^[\!-\~]+$/D', $password)) {
		    if (hasSmallAlphabet($password) && hasCapitalAlphabet($password) && hasNumber($password)) {
		    	return true;
		    	
		    } else {
		    	return false;
		    }
		
	    } else {
	    	return false;
	    }
	    
	    return false;
	}
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

// mark checker
//function hasMark($string)
//{
//	return preg_match('/[\!-\/\:-\@\^-\`\{-\~]/', $string);
//}

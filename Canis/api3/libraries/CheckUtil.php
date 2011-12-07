<?php

/**
* The class which has utilities to check characters/numbers.
*
* [method]
* + checkNotEmpty : The method to check whether the value is empty/null or not
* + checkEmail : The method to check Email format
* + checkPassword : The method to check password format
* + checkPhonenumber : The method to check phonenumber format
* + checkIsFloat: The method to check decimal number format
* + checkMinLength: The method to check whether the value length is too short or not
* + checkMaxLength: The method to check whether the value length is too long or not
* + hasSmallAlphabet: The method to check whether the value has small alphabet or not
* + hasCapitalAlphabet: The method to check whether the value has capital alphabet or not
* + hasNumber: The method to check whether the value includes number or not
* + hasOnlyNumber: The method to check whether the value is consisted by only number or not
* + hasMark: The method to check whether the value includes mark or not
*
*/

class CheckUtil
{
    // null and empty checker
    public static function checkNotEmpty($string)
    {
        if ($string == null || $string == "") {
            return false;
            
        } else if (trim($string, " 　") == "") {
            return false;
            
        }
    
        return true;
    }
    
    
    // email checker
    public static function checkEmail($email)
    {
        if (!self::checkMaxLength($email, 256)) {
            return false;
            
        } else {
            
        }
        
        // this regular exception is from HTML_QuickForm_Rule_Email class in pear/HTML_QuickForm-3.2.13 package 
        $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/D';
        return preg_match($regex, $email);
    }
    
    
    // password checker
    public static function checkPassword($password)
    {
        // character check
        if (preg_match('/^[\!-\~]+$/D', $password)) {
    // For string type check
    //        if (self::hasSmallAlphabet($password) && self::hasCapitalAlphabet($password) && self::hasNumber($password)) {
    //            return true;
    //            
    //        } else {
    //            return false;
    //        }
            return true;
            
        } else {
            return false;
            
        }
    }
    
    // phonenumber checker
    public static function checkPhoneNumber($phonenumber)
    {
        $modifiedPhonenumber = str_replace("+", "", $phonenumber);
        $modifiedPhonenumber = str_replace("-", "", $modifiedPhonenumber);
        if (strlen($modifiedPhonenumber) > 15) {
            return false;
        }
        
        $regex = '/^\+?[0-9][0-9\-]+[0-9]$/D';
        return preg_match($regex, $phonenumber);
    }
    
    
    // float checker
    public static function checkIsFloat($float, $unsignedFlag = false)
    {
        $regex = null;
        
        if ($unsignedFlag) {
            $regex = '/^(0|[1-9]+[0-9]*)(\.[0-9]+)?$/D';
        
        } else {
            $regex = '/^-?(0|[1-9]+[0-9]*)(\.[0-9]+)?$/D';
        
        }
        
        return preg_match($regex, $float);
    }
    
    
    // length checker
    public static function checkMinLength($text, $min)
    {
        $length = strlen($text);
        
        if ($min > $length) {
            return false;
        
        } else {
            
        }
        
        return true;
    }
    
    
    // length checker
    public static function checkMaxLength($text, $max)
    {
        $length = strlen($text);
    
        if ($max < $length) {
            return false;
        
        } else {
            
        }
    
        return true;
    }
    
    
    // Small alphabet checker
    public static function hasSmallAlphabet($string)
    {
        return preg_match('/[a-z]/', $string);
    }
    
    
    // Capital alphabet checker
    public static function hasCapitalAlphabet($string)
    {
        return preg_match('/[A-Z]/', $string);
    }
    
    
    // number checker
    public static function hasNumber($string)
    {
        return preg_match('/[0-9]/', $string);
    }
    
    
    // number checker
    public static function hasOnlyNumber($string)
    {
        return preg_match('/^[0-9]+$/D', $string);
    }
    
    
    // mark checker
    public static function hasMark($string, $nonTargetStrings = null)
    {
        if ($nonTargetStrings != null) {
            $string = str_replace($nonTargetStrings, "", $string);
        
        } else {
            
        }
        
        return preg_match('/[\!-\/\:-\@\^-\`\{-\~]/', $string);
    }
}

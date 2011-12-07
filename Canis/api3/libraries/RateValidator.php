<?php

/**
* The class to validate rate information
*
* [method]
* + validateRateInfo : The method to validate rate information
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class RateValidator
{
    private $_rate = null;
    private $_rateconstraints = null;

    public function __construct($rate)
    {
        $this->_rate = $rate;
        $this->_rateconstraints = $GLOBALS["rateconstraints"];
    }
    

    public function validateRateInfo()
    {
        DebugLogger::write("Rate information will be validated from now.");
        
        // Validate user
        $user = new User();
        $user->setGalaxyuserid($this->_rate->getGalaxyuserid());
        $userValidator = new UserValidator($user);
        $userValidateResult = $userValidator->validateGalaxyuserid();
        if ($userValidateResult["status"] != "true") {
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
        }
        

        // Validate rate
        if (is_null($this->_rate->getRate())) {
        	WarnLogger::write("Rate is not requested.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
        	if (!CheckUtil::checkNotEmpty($this->_rate->getRate())) {
        		WarnLogger::write("Rate is empty.");
        		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        	} else if (!in_array($this->_rate->getRate(), $this->_rateconstraints["rate"]["value"])) {
        		WarnLogger::write("Rate is invalid.");
        		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	}
        }


        // Validate rater
        $rater = new User();
        $rater->setGalaxyuserid($this->_rate->getRater());
        $raterValidator = new UserValidator($rater);
        $raterValidateResult = $userValidator->validateGalaxyuserid();
        if ($raterValidateResult["status"] != "true") {
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
        }


        DebugLogger::write("Rate information is valid.");
        
        return OutputUtil::getSuccessOutput();
    }
}

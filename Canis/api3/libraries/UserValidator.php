<?php

/**
* The class to validate user information
*
* [method]
* + validateUserInfoToRegister : The method to validate user information to register
* + validateHalfUserInfoToRegister : The method to validate half user information to register
* + validateUserInfoToLogin : The method to validate user information to login
* + validateUserInfoToChangePassword : The method to validate user information to change password
* + validateExistingEmail : The method to check the requested email format and whether the requested email has already existed or not
* + validateGalaxyuserid : The method to check whether the requested galaxyuserid has already existed or not
* - _valiateFirstname : The method to validate firstname
* - _valiateLastname : The method to validate lastname
* - _valiateEmail : The method to validate email
* - _valiatePassword : The method to validate password
* - _valiateZipcode : The method to validate zipcode
* - _valiateCountry : The method to validate country
* - _valiateState : The method to validate state
* - _valiateCity : The method to validate city
* - _valiateStreet : The method to validate street
* - _valiatePhonenumber : The method to validate phonenumber
*
*/


require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";

class UserValidator
{
    private $_user = null;
    private $_userconstraints = null;
    
    public function __construct($user)
    {
        $this->_user = $user;
        $this->_userconstraints = $GLOBALS["userconstraints"];
    }
    

    // public function to validate user information to regist
    public function validateUserInfoToRegister() {
    
        $error = array();
    
        // Validate first name
        $firstnameValidateResult = $this->_validateFirstname($this->_user->getFirstname());
        if ($firstnameValidateResult !== true) {
            $error[] = $firstnameValidateResult;
        }
        
        // Validate last name
        $lastnameValidateResult = $this->_validateLastname($this->_user->getLastname());
        if ($lastnameValidateResult !== true) {
            $error[] = $lastnameValidateResult;
        }
        
        // Validate email
        $emailValidateResult = $this->_validateEmail($this->_user->getEmail(), false);
        if ($emailValidateResult !== true) {
            $error[] = $emailValidateResult;
        }
    
        // Validate password
        $passwordValidateResult = $this->_validatePassword($this->_user->getPassword(), $this->_user->getPassword2());
        if ($passwordValidateResult !== true) {
            $error[] = $passwordValidateResult;
        }    
    
        // Validate zipcode
        $zipcodeValidateResult = $this->_validateZipcode($this->_user->getZipcode());
        if ($zipcodeValidateResult !== true) {
            $error[] = $zipcodeValidateResult;
        }
        
        // Validate country
        $countryValidateResult = $this->_validateCountry($this->_user->getCountry());
        if ($countryValidateResult !== true) {
            $error[] = $countryValidateResult;
        }     
    
        // Validate state
        $stateValidateResult = $this->_validateState($this->_user->getState());
        if ($stateValidateResult !== true) {
            $error[] = $stateValidateResult;
        }
   
        // Validate city
        $cityValidateResult = $this->_validateCity($this->_user->getCity());
        if ($cityValidateResult !== true) {
            $error[] = $cityValidateResult;
        }
       
        // Validate street
        $streetValidateResult = $this->_validateStreet($this->_user->getStreet());
        if ($streetValidateResult !== true) {
            $error[] = $streetValidateResult;
        }
    
        // Validate phonenumber
        $phonenumberValidateResult = $this->_validatePhonenumber($this->_user->getPhonenumber());
        if ($phonenumberValidateResult !== true) {
            $error[] = $phonenumberValidateResult;
        }     
        
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("User information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("User information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
    
    
    // public function to validate user information to regist
    public function validateHalfUserInfoToRegister() {
    
        $error = array();
    
        // Validate first name
        $firstnameValidateResult = $this->_validateFirstname($this->_user->getFirstname());
        if ($firstnameValidateResult !== true) {
            $error[] = $firstnameValidateResult;
        }
        
        // Validate last name
        $lastnameValidateResult = $this->_validateLastname($this->_user->getLastname());
        if ($lastnameValidateResult !== true) {
            $error[] = $lastnameValidateResult;
        }
            
        // Validate zipcode
        $zipcodeValidateResult = $this->_validateZipcode($this->_user->getZipcode());
        if ($zipcodeValidateResult !== true) {
            $error[] = $zipcodeValidateResult;
        }
        
        // Validate country
        $countryValidateResult = $this->_validateCountry($this->_user->getCountry());
        if ($countryValidateResult !== true) {
            $error[] = $countryValidateResult;
        }     
    
        // Validate state
        $stateValidateResult = $this->_validateState($this->_user->getState());
        if ($stateValidateResult !== true) {
            $error[] = $stateValidateResult;
        }
   
        // Validate city
        $cityValidateResult = $this->_validateCity($this->_user->getCity());
        if ($cityValidateResult !== true) {
            $error[] = $cityValidateResult;
        }
       
        // Validate street
        $streetValidateResult = $this->_validateStreet($this->_user->getStreet());
        if ($streetValidateResult !== true) {
            $error[] = $streetValidateResult;
        }
        
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("User information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("User information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
    
    
    // public function to validate user information to login
    public function validateUserInfoToLogin() {

        $email    = $this->_user->getEmail();
        $password = $this->_user->getPassword();
        
        $errorFlag = false;
    
        // Validate email
        if (is_null($email)) {
            WarnLogger::write("Email is not requested.");
            $errorFlag = true;
    
        } else if (!CheckUtil::checkEmail($email)) {
            InfoLogger::write("Email is invalid.");
            $errorFlag = true;
    
        } else {
    
        }
    
    
        // Validate password
        if (is_null($password)) {
            WarnLogger::write("Password is not requested.");
            $errorFlag = true;
    
        } else if (!CheckUtil::checkMinLength($password, $this->_userconstraints["password"]["min_length"])) {
            InfoLogger::write("Password is too short.");
            $errorFlag = true;
             
        } else if (!CheckUtil::checkMaxLength($password, $this->_userconstraints["password"]["max_length"])) {
            InfoLogger::write("Password is too long.");
            $errorFlag = true;
             
        } else if (!CheckUtil::checkPassword($password)) {
            InfoLogger::write("Password is invalid.");
            $errorFlag = true;
    
        } else {
    
        }
    
        $result = array();
    
        if (!$errorFlag) {
            InfoLogger::write("User information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("User information is invalid.");
            $result = OutputUtil::getErrorOutput(array(USER_LOGIN_FAILURE_NONE));
        }
    
        return $result;
    }
    
    
    // public function to validate user information to change password
    public function validateUserInfoToChangePassword() {

        $password  = $this->_user->getPassword();
        $password2 = $this->_user->getPassword2();
        
        $error = array();
    
        // Validate password
        $passwordValidateResult = $this->_validatePassword($password, $password2);
        if ($passwordValidateResult !== true) {
            $error[] = $passwordValidateResult;
        }
    
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("User information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("User information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }

    
    // public function to validate email
    public function validateExistingEmail()
    {
        $result = null;
        
        // Validate email
        $emailValidateResult = $this->_validateEmail($this->_user->getEmail(), true);
        if ($emailValidateResult === true) {
            $result = OutputUtil::getSuccessOutput();
            
        } else {
            $result = OutputUtil::getErrorOutput(array($emailValidateResult));
        }
        
        return $result;
    }

    // public function to validate galaxyuserid
    public function validateGalaxyuserid()
    {
        $galaxyuserid = $this->_user->getGalaxyuserid();
        
        DebugLogger::write("Galaxyuserid will be validated from now.");
    
        // Validate galaxy user id
        if (is_null($galaxyuserid)) {
            WarnLogger::write("Galaxyuserid is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($galaxyuserid)) {
                WarnLogger::write("Galaxyuserid is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
                DebugLogger::write("User existing check will be done from now.");
    
                // DB connect
                $db_connection = GalaxyDbConnector::getConnection();
    
                // prepare SQL statement
                $stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
                $stmt->bindValue(":GALAXYUSERID", $galaxyuserid, PDO::PARAM_STR);
    
                // execute SQL
                try {
                    $stmt->execute();
                        
                    // get result number
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result_num = count($result);
                        
                    // close prepared statement
                    $stmt = null;
                        
                    // user id existence judgement
                    if ($result_num == 1) {
                        InfoLogger::write("There is a user which has the requested galaxyuserid.");
    
                    } else {
                        WarnLogger::write("There is no user which has the requested galaxyuserid.");
                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
                    }
                        
                } catch(Exception $e) {
                    ErrorLogger::write("User select operation failed.", $e);
                    return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
                }
            }
        }
    
        DebugLogger::write("Requested galaxyuserid is valid.");

        return OutputUtil::getSuccessOutput();
    }


    // public function to validate pcrequestcode
    public function validatePcrequestcode()
    {
    	$pcrequestcode = $this->_user->getPcrequestcode();
    
    	DebugLogger::write("Galaxyuserid will be validated from now.");
    
    	// Validate pcrequestcode
    	if (is_null($pcrequestcode)) {
    		WarnLogger::write("Pcrequestcode is not requested.");
    		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
    	} else {
    		if (!CheckUtil::checkNotEmpty($pcrequestcode)) {
    			WarnLogger::write("Pcrequestcode is empty.");
    			return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
    		} else {
    			DebugLogger::write("Pcrequestcde check will be done from now.");
    
    			// DB connect
    			$db_connection = GalaxyDbConnector::getConnection();
    
    			// prepare SQL statement
    			$stmt = $db_connection->prepare(PCREQUESTCODE_CHECK);
    			$stmt->bindValue(":PCREQUESTCODE", $pcrequestcode, PDO::PARAM_STR);
    
    			// execute SQL
    			try {
    				$stmt->execute();
    
    				// get result number
    				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    				$result_num = count($result);
    
    				// close prepared statement
    				$stmt = null;
    
    				// user id existence judgement
    				if ($result_num == 1) {
    					InfoLogger::write("There is a user which has the requested pcrequestcode.");
    					
    					$pcrequesttime = $result[0]["pcrequesttime"];
    					if (strtotime($pcrequesttime) < strtotime("-1 day")) {
    						WarnLogger::write("Pcrequestcode is expired.");
    						return OutputUtil::getErrorOutput(array(USER_PWCH_URL_EXPIRED_NONE));
    						
    					} else {
    					}
    
    				} else {
    					WarnLogger::write("There is no user which has the requested pcrequestcode.");
    					return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    				}
    
    			} catch(Exception $e) {
    				ErrorLogger::write("User select operation failed.", $e);
    				return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    			}
    		}
    	}
    
    	DebugLogger::write("Requested pcrequestcode is valid.");
    
    	return OutputUtil::getSuccessOutput();
    }
    


    
    private function _validateFirstname($firstname)
    {
        // Validate first name
        if (is_null($firstname)) {
            WarnLogger::write("Firstname is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($firstname)) {
                InfoLogger::write("Firstname is empty.");
                return USER_EMPTY_FIRSTNAME;
        
            } else if (!CheckUtil::checkMaxLength($firstname, $this->_userconstraints["firstname"]["max_length"])) {
                InfoLogger::write("Firstname is too long.");
                return USER_INVALID_FIRSTNAME;
        
            } else if (CheckUtil::hasNumber($firstname) || CheckUtil::hasMark($firstname)) {
                InfoLogger::write("Firstname is invalid.");
                return USER_INVALID_FIRSTNAME;
        
            } else {
                    
            }
        }
        
        return true;
    }
    
    
    private function _validateLastname($lastname)
    {
        // Validate last name
        if (is_null($lastname)) {
            WarnLogger::write("Lasttname is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($lastname)) {
                InfoLogger::write("Lastname is empty.");
                return USER_EMPTY_LASTNAME;
        
            } else if (!CheckUtil::checkMaxLength($lastname, $this->_userconstraints["lastname"]["max_length"])) {
                InfoLogger::write("Lastname is too long.");
                return USER_INVALID_LASTNAME;
        
            } else if (CheckUtil::hasNumber($lastname) || CheckUtil::hasMark($lastname)) {
                InfoLogger::write("Lastname is invalid.");
                return USER_INVALID_LASTNAME;
        
            } else {
                    
            }
        }
        
        return true;
    }

    
    // public function to validate galaxyuserid
    private function _validateEmail($email, $emailShouldExist)
    {
        DebugLogger::write("Email will be validated from now.");
    
        $error = array();
    
        // Validate email
        if (is_null($email)) {
            WarnLogger::write("Email is not requested.");
            return APP_SYSTEM_ERROR_NONE;
    
        } else {
            if (!CheckUtil::checkEmail($email)) {
                InfoLogger::write("Email is invalid.");
                return USER_EMPTY_EMAIL;
    
            } else {
                if ($emailShouldExist) {
                    DebugLogger::write("User existence check will be done from now.");
                
                } else {
                    DebugLogger::write("Duplicate user check will be done from now.");

                }
                    
                // DB connect
                $db_connection = GalaxyDbConnector::getConnection();
    
                // prepare SQL statement
                $stmt = $db_connection->prepare(USER_CHECK_QUERY);
                $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
    
                // execute SQL
                try {
                    $stmt->execute();
    
                    // get result number
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result_num = count($result);
    
                    // close prepared statement
                    $stmt = null;
    
                    if ($emailShouldExist) {
                        // user existence judgement
                        if ($result_num == 1) {
                            InfoLogger::write("There is a user which has requested email address.");
        
                        } else {
                            InfoLogger::write("There is no user which has requested email address.");
                            return USER_INVALID_EMAIL;
                        }
                    
                    } else {
                        // user duplicate judgement
                        if ($result_num == 0) {
                            InfoLogger::write("There is no user which has same email address.");
                        
                        } else {
                            InfoLogger::write("Email has been already registered.");
                            return USER_REGISTRATED_USER_NONE;

                        }
                        
                    }
    
                } catch(Exception $e) {
                    ErrorLogger::write("User select operation failed.", $e);
                    return SRV_SYSTEM_ERROR_NONE;
                }
            }
        }
        
        return true;
    }
    
    
    private function _validatePassword($password, $password2)
    {
        // Validate password
        if (is_null($password)) {
            WarnLogger::write("Password is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($password)) {
                InfoLogger::write("Password is empty.");
                return USER_EMPTY_PASSWORD;
        
            } else if (!CheckUtil::checkMinLength($password, $this->_userconstraints["password"]["min_length"])) {
                InfoLogger::write("Password is too short.");
                return USER_TOOSHORT_PASSWORD;
        
            } else if (!CheckUtil::checkMaxLength($password, $this->_userconstraints["password"]["max_length"])) {
                InfoLogger::write("Password is too long.");
                return USER_INVALID_PASSWORD;
        
            } else if (!CheckUtil::checkPassword($password)) {
                InfoLogger::write("Password is invalid.");
                return USER_INVALID_PASSWORD;
        
            } else {
                if (is_null($password2)) {
                    WarnLogger::write("Password2 is not requested.");
                    return APP_SYSTEM_ERROR_NONE;
        
                } else if ($password != $password2) {
                    InfoLogger::write("Passwords are not matched.");
                    return USER_UNMATCH_PASSWORD;
        
                } else {
        
                }
        
            }
        
        }
        
        return true;
    }
    
    
    private function _validateZipcode($zipcode)
    {
        // Validate zipcode
        if (is_null($zipcode)) {
            WarnLogger::write("Zipcode is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($zipcode)) {
                InfoLogger::write("Zipcode is empty.");
                return USER_EMPTY_ZIPCODE;
        
            } else if (!CheckUtil::checkMaxLength($zipcode, $this->_userconstraints["zipcode"]["max_length"])) {
                InfoLogger::write("Zipcode is too long.");
                return USER_INVALID_ZIPCODE;
        
            } else if (!CheckUtil::hasOnlyNumber($zipcode)) {
                InfoLogger::write("Zipcode is invalid.");
                return USER_INVALID_ZIPCODE;
        
            } else {
                    
            }
        
        }
        
        return true;
    }


    private function _validateCountry($country)
    {
        // Validate country
        if (is_null($country)) {
            WarnLogger::write("Country is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($country)) {
                WarnLogger::write("Country is empty.");
                return APP_SYSTEM_ERROR_NONE;
        
            }  else if (!in_array($country, $this->_userconstraints["country"]["value"])) {
                WarnLogger::write("Country is invalid.");
                return APP_SYSTEM_ERROR_NONE;
        
            } else {
                    
            }
        }
        
        return true;
    }
    
    
    private function _validateState($state)
    {
        // Validate state
        if (is_null($state)) {
            WarnLogger::write("State is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($state)) {
                WarnLogger::write("State is empty.");
                return APP_SYSTEM_ERROR_NONE;
        
            } else if (!CheckUtil::checkMaxLength($state, $this->_userconstraints["state"]["max_length"])) {
                WarnLogger::write("State is too long.");
                return APP_SYSTEM_ERROR_NONE;
        
            } else if (CheckUtil::hasNumber($state) || CheckUtil::hasMark($state)) {
                WarnLogger::write("State is invalid.");
                return APP_SYSTEM_ERROR_NONE;
        
            } else {
                    
            }
        }
        
        return true;
    }

    
    private function _validateCity($city)
    {
        // Validate city
        if (is_null($city)) {
            WarnLogger::write("City is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($city)) {
                InfoLogger::write("City is empty.");
                return USER_EMPTY_CITY;
        
            } else if (!CheckUtil::checkMaxLength($city, $this->_userconstraints["city"]["max_length"])) {
                InfoLogger::write("City is too long.");
                return USER_INVALID_CITY;
        
            } else if (CheckUtil::hasNumber($city) || CheckUtil::hasMark($city, " ")) {
                InfoLogger::write("City is invalid.");
                return USER_INVALID_CITY;
        
            } else {
                    
            }
        }
        
        return true;
    }
    

    private function _validateStreet($street)
    {
        // Validate street
        if (is_null($street)) {
            WarnLogger::write("Street is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($street)) {
                InfoLogger::write("Street is empty.");
                return USER_EMPTY_STREET;
        
            } else if (!CheckUtil::checkMaxLength($street, $this->_userconstraints["street"]["max_length"])) {
                InfoLogger::write("Street is too long.");
                return USER_INVALID_STREET;
        
            } else {
                    
            }
        }
        
        return true;
    }

    
    private function _validatePhonenumber($phonenumber)
    {
        // Validate phonenumber
        if (is_null($phonenumber)) {
            WarnLogger::write("Phonenumber is not requested.");
            return APP_SYSTEM_ERROR_NONE;
        
        } else {
            if (!CheckUtil::checkNotEmpty($phonenumber)) {
                InfoLogger::write("Phonenumber is empty.");
                return USER_EMPTY_PHONENUMBER;
        
            } else if (!CheckUtil::checkPhoneNumber($phonenumber)) {
                InfoLogger::write("Phonenumber is invalid.");
                return USER_INVALID_PHONENUMBER;
        
            } else {
                    
            }
        }
        
        return true;
    }
}
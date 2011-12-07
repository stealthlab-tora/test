<?php

/**
* The class to validate item information
*
* [method]
* + validateItemInfo : The method to validate all item information
* + validateItemId : The method to validate item id
* + validateItemStatus : The method to validate item status
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";

class ItemValidator
{
    private $_item = null;
    private $_itemconstraints = null;
    
    public function __construct($item)
    {
        $this->_item = $item;
        $this->_itemconstraints = $GLOBALS["itemconstraints"];
    }
    
    // function to validate item information to regist
    public function validateItemInfo($validateLocationFlag = true) {
    
        DebugLogger::write("Item information will be validated from now.");
    
        $error = array();
    
        // Validate galaxy user id
        if (is_null($this->_item->getGalaxyuserid())) {
            WarnLogger::write("Galaxyuserid is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getGalaxyuserid())) {
                WarnLogger::write("Galaxyuserid is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
                DebugLogger::write("User existing check will be done from now.");
                    
                // DB connect
                $db_connection = GalaxyDbConnector::getConnection();
                    
                // prepare SQL statement
                $stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
                $stmt->bindValue(":GALAXYUSERID", $this->_item->getGalaxyuserid(), PDO::PARAM_STR);
                    
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
    
    
        // Validate item name
        if (is_null($this->_item->getItemname())) {
            WarnLogger::write("Itemname is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getItemname())) {
                InfoLogger::write("Itemname is empty.");
                $error[] = USER_EMPTY_ITEMNAME;
    
            } else if (!CheckUtil::checkMaxLength($this->_item->getItemname(), $this->_itemconstraints["itemname"]["max_length"])) {
                InfoLogger::write("Itemname is too long.");
                $error[] = USER_INVALID_ITEMNAME;
    
            } else {
    
            }
        }
    
    
        // Validate description
        if (is_null($this->_item->getDescription())) {
            WarnLogger::write("Description is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getDescription())) {
                InfoLogger::write("Description is empty.");
                $error[] = USER_EMPTY_ITEMDESCRIPTION;
    
            } else if (!CheckUtil::checkMaxLength($this->_item->getDescription(), $this->_itemconstraints["description"]["max_length"])) {
                InfoLogger::write("Description is too long.");
                $error[] = USER_INVALID_ITEMDESCRIPTION;
    
            } else {
    
            }
        }
    
    
        // Validate price
        if (is_null($this->_item->getPrice())) {
            WarnLogger::write("Price is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getPrice())) {
                InfoLogger::write("Price is empty.");
                $error[] = USER_EMPTY_ITEMPRICE;
    
            } else if (!CheckUtil::checkMaxLength($this->_item->getPrice(), $this->_itemconstraints["price"]["max_length"])) {
                InfoLogger::write("Price is too long.");
                $error[] = USER_INVALID_ITEMPRICE;
    
            } else if (!CheckUtil::checkIsFloat($this->_item->getPrice(), true)) {
                InfoLogger::write("Price is invalid.");
                $error[] = USER_INVALID_ITEMPRICE;
    
            } else {
    
            }
    
        }
    
    
        // Validate currency
        if (is_null($this->_item->getCurrency())) {
            WarnLogger::write("Currency is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getCurrency())) {
                WarnLogger::write("Currency is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else if (!in_array($this->_item->getCurrency(), $this->_itemconstraints["currency"]["value"])) {
                WarnLogger::write("Currency is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
    
            }
    
        }
    
    
        // Validate locationtype
        if (is_null($this->_item->getLocationtype())) {
            WarnLogger::write("Locationtype is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getLocationtype())) {
                WarnLogger::write("Locationtype is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else if (!in_array($this->_item->getLocationtype(), $this->_itemconstraints["locationtype"]["value"])) {
                WarnLogger::write("Locationtype is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
                // When locationtype is zipcode
                if ($this->_item->getLocationtype() == $this->_itemconstraints["locationtype"]["value"]["zipcode"]) {
    
                    // Validate zipcode
                    if (is_null($this->_item->getZipcode())) {
                        WarnLogger::write("Zipcode is not requested.");
                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
                    } else {
                        if (!CheckUtil::checkNotEmpty($this->_item->getZipcode())) {
                            InfoLogger::write("Zipcode is empty.");
                            $error[] = USER_EMPTY_ZIPCODE;
    
                        } else if (!CheckUtil::checkMaxLength($this->_item->getZipcode(), $this->_itemconstraints["zipcode"]["max_length"])) {
                            InfoLogger::write("Zipcode is too long.");
                            $error[] = USER_INVALID_ZIPCODE;
    
                        } else if (!CheckUtil::hasOnlyNumber($this->_item->getZipcode())) {
                            InfoLogger::write("Zipcode is invalid.");
                            $error[] = USER_INVALID_ZIPCODE;
    
                        } else if ($validateLocationFlag) {
				            $getLocationDataResult = LocationUtil::getLocationDataFromZipcode($this->_item->getZipcode());
				            if ($getLocationDataResult["status"] != "true") {
				                InfoLogger::write("There was no location data for requested zipcode.");
				                $error = array_merge($error, $getLocationDataResult["error"]);
				                    
				            } else {
				            }
				            
                        } else {
                        }
    
                    }

                // When locationtype is address
                } else if ($this->_item->getLocationtype() == $this->_itemconstraints["locationtype"]["value"]["address"]) {
    
                	$addressErrorFlag = false;
                	
                    // Validate state
                    if (is_null($this->_item->getState())) {
                        WarnLogger::write("State is not requested.");
                        $addressErrorFlag = true;
                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
                    } else {
                        if (!CheckUtil::checkNotEmpty($this->_item->getState())) {
                            InfoLogger::write("State is empty.");
                            $addressErrorFlag = true;
                            $error[] = USER_EMPTY_LOCATION_STATE;
    
                        } else if (!CheckUtil::checkMaxLength($this->_item->getState(), $this->_itemconstraints["state"]["max_length"])) {
                            InfoLogger::write("State is too long.");
                            $addressErrorFlag = true;
                            $error[] = USER_INVALID_LOCATION_STATE;
    
                        } else if (CheckUtil::hasNumber($this->_item->getState()) || CheckUtil::hasMark($this->_item->getState())) {
                            InfoLogger::write("State is invalid.");
                            $addressErrorFlag = true;
                            $error[] = USER_INVALID_LOCATION_STATE;
    
                        } else {
                        }
    
                    }
    
    
                    // Validate city
                    if (is_null($this->_item->getCity())) {
                        WarnLogger::write("City is not requested.");
                        $addressErrorFlag = true;
                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
                    } else {
                        if (!CheckUtil::checkNotEmpty($this->_item->getCity())) {
                            InfoLogger::write("City is empty.");
                            $addressErrorFlag = true;
                            $error[] = USER_EMPTY_LOCATION_CITY;
    
                        } else if (!CheckUtil::checkMaxLength($this->_item->getCity(), $this->_itemconstraints["city"]["max_length"])) {
                            InfoLogger::write("City is too long.");
                            $addressErrorFlag = true;
                            $error[] = USER_INVALID_LOCATION_CITY;
    
                        } else if (CheckUtil::hasNumber($this->_item->getCity()) || CheckUtil::hasMark($this->_item->getCity())) {
                            InfoLogger::write("City is invalid.");
                            $addressErrorFlag = true;
                            $error[] = USER_INVALID_LOCATION_CITY;
    
                        } else {
                        }
                        
                    }

                    if (!$addressErrorFlag && $validateLocationFlag) {
			            $getLocationDataResult = LocationUtil::getLocationDataFromAddress($this->_item->getState(), $this->_item->getCity());
			            if ($getLocationDataResult["status"] != "true") {
			                InfoLogger::write("There was no location data for requested address.");
			                $error = array_merge($error, $getLocationDataResult["error"]);
			    
			            } else {
			            }
            
                    } else {
                    }
                    
                // When locationtype is address
                } else if ($this->_item->getLocationtype() == $this->_itemconstraints["locationtype"]["value"]["geolocation"]) {
                	
                	$geolocationErrorFlag = false;
                	
	                // Validate latitude
	                if (is_null($this->_item->getLatitude())) {
	                    WarnLogger::write("Latitude is not requested.");
	                    $geolocationErrorFlag = true;
	                    return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                } else {
	                    if (!CheckUtil::checkNotEmpty($this->_item->getLatitude())) {
	                        WarnLogger::write("Latitude is empty.");
	                        $geolocationErrorFlag = true;
	                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                    } else if (!CheckUtil::checkIsFloat($this->_item->getLatitude())) {
	                        WarnLogger::write("Latitude is invalid.");
	                        $geolocationErrorFlag = true;
	                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                    }
	                }
	    
	    
	                // Validate longtitude
	                if (is_null($this->_item->getLongtitude())) {
	                    WarnLogger::write("Longtitude is not requested.");
	                    $geolocationErrorFlag = true;
	                    return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                } else {
	                    if (!CheckUtil::checkNotEmpty($this->_item->getLongtitude())) {
	                        WarnLogger::write("Longtitude is empty.");
	                        $geolocationErrorFlag = true;
	                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                    } else if (!CheckUtil::checkIsFloat($this->_item->getLongtitude())) {
	                        WarnLogger::write("Longtitude is invalid.");
	                        $geolocationErrorFlag = true;
	                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	                    }
	                }
	                
	                
	                if (!$geolocationErrorFlag && $validateLocationFlag) {
			            $getLocationDataResult = LocationUtil::getLocationDataFromGeolocation($this->_item->getLatitude(), $this->_item->getLongtitude());
			            if ($getLocationDataResult["status"] != "true") {
			                InfoLogger::write("There was no location data for requested address.");
	                        $error = array_merge($error, $getLocationDataResult["error"]);
			    
			            } else {
			            }
            
                    } else {
                    }
	                
	            } else {
	            }
            }
        }
    
    
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("item information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("item information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
    
    
    // function to validate item id
    public function validateItemId($itemstatusType = null, $limitedBySeller = false) {
    
        DebugLogger::write("Itemid will be validated from now.");
    
        $error = array();
    
        // Validate itemid
        if (is_null($this->_item->getItemid())) {
            WarnLogger::write("Itemid is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getItemid())) {
                WarnLogger::write("Itemid is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
                DebugLogger::write("Item existing check will be done from now.");
    
                // DB connect
                $db_connection = GalaxyDbConnector::getConnection();
    
                $itemstatusCond = "";
                if ($itemstatusType == "ON_SALE") {
                    $itemstatusCond = " and itemstatus = 'ON_SALE'";
    
                } else if ($itemstatusType == "SOLD") {
                    $itemstatusCond = " and itemstatus = 'SOLD'";
    
                } else if ($itemstatusType == "ONSALE_OR_SOLD") {
                    $itemstatusCond = " and (itemstatus = 'ON_SALE' or itemstatus = 'SOLD')";
    
                } else {
                        
                }
                    
                $galaxyuserCond = "";
                if ($limitedBySeller) {
                    $galaxyuserCond = " and galaxyuserid = " . $this->_item->getGalaxyuserid();
                } else {
                        
                }
                    
                // prepare SQL statement
                $stmt = $db_connection->prepare(ITEM_EXIST_CHECK_QUERY . $itemstatusCond . $galaxyuserCond);
                $stmt->bindValue(":ITEMID", $this->_item->getItemid(), PDO::PARAM_STR);
    
                // execute SQL
                try {
                    $stmt->execute();
    
                    // get result number
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result_num = count($result);
    
                    // close prepared statement
                    $stmt = null;
    
                    // item existence judgement
                    if ($result_num == 1) {
                        InfoLogger::write("There is an item which has the requested itemid.");
    
                    } else {
                        WarnLogger::write("There is no item which has the requested itemid.");
                        return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
                    }
    
                } catch(Exception $e) {
                    ErrorLogger::write("Item select operation failed.", $e);
                    return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
                }
            }
        }
    
    
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("Item information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("Item information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
    
    
    // function to validate item information to regist
    public function validateItemStatus() {
    
        DebugLogger::write("Item status will be validated from now.");
    
        // Validate item status
        if (is_null($this->_item->getItemstatus())) {
            WarnLogger::write("Itemstatus is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
        } else {
            if (!CheckUtil::checkNotEmpty($this->_item->getItemstatus())) {
                WarnLogger::write("Itemstatus is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else if (!in_array($this->_item->getItemstatus(), $this->_itemconstraints["itemstatus"]["value"])) {
                WarnLogger::write("Itemstatus is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
            } else {
    
            }
    
        }
    
        DebugLogger::write("Item status is valid.");

        return OutputUtil::getSuccessOutput();
    }
}
<?php

/**
* The class to validate item search information
*
* [method]
* + validateSearchInfo : The method to validate item search information
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ItemSearchInfoValidator
{
    private $_itemSearchInfo = null;
    private $_itemconstraints = null;
    
    public function __construct($itemSearchInfo)
    {
        $this->_itemSearchInfo = $itemSearchInfo;
        $this->_itemconstraints = $GLOBALS["itemconstraints"];
    }
    
    // function to validate search information
    public function validateSearchInfo() {
        
        DebugLogger::write("Search information will be validated from now.");
            
        $error = array();
        
        // Validate galaxyuserid
        if (is_null($this->_itemSearchInfo->getGalaxyuserid())) {
            WarnLogger::write("Galaxyuserid is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
            if (!CheckUtil::checkNotEmpty($this->_itemSearchInfo->getGalaxyuserid())) {
                WarnLogger::write("Galaxyuserid is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
            
            } else {

                DebugLogger::write("User existing check will be done from now.");
            
                // DB connect
                $db_connection = GalaxyDbConnector::getConnection();
        
                // prepare SQL statement
                $stmt = $db_connection->prepare(USER_EXIST_CHECK_QUERY_BY_ID);
                $stmt->bindValue(":GALAXYUSERID", $this->_itemSearchInfo->getGalaxyuserid(), PDO::PARAM_STR);
        
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
            
            
        // Validate value1
        // (Only request check will be done here and the other checks will be done after type check.)
        if (is_null($this->_itemSearchInfo->getValue())) {
            WarnLogger::write("Search value is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));

        } else {
        }
            
        
        // Validate type
        if (is_null($this->_itemSearchInfo->getType())) {
            WarnLogger::write("Search type is not requested.");
            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
            if (!CheckUtil::checkNotEmpty($this->_itemSearchInfo->getType())) {
                WarnLogger::write("Search type is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
            } else if (!in_array($this->_itemSearchInfo->getType(), $this->_itemconstraints["search_type"]["value"])) {
                WarnLogger::write("Search type is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
                    
            } else {
                // Validate value2
                // (Semantic check will be done here.)
                if ($this->_itemSearchInfo->getType() == $this->_itemconstraints["search_type"]["value"]["keyword"]) {
                    if (!CheckUtil::checkMaxLength($this->_itemSearchInfo->getValue(), $this->_itemconstraints["search_value"]["max_length"])) {
                        InfoLogger::write("Search value is too long.");
                        $error[] = USER_INVALID_SEARCH_VALUE;
                
                    } else {
                    }
        
                } else if ($this->_itemSearchInfo->getType() == $this->_itemconstraints["search_type"]["value"]["barcode"]) {
                    if (!CheckUtil::hasOnlyNumber($this->_itemSearchInfo->getValue())) {
                        InfoLogger::write("Search value is invalid.");
                        $error[] = USER_INVALID_SEARCH_VALUE;
                    
                    } else {
                    }
            
                } else {
                }
            }
        }
        
        
        // Validate order
        if (CheckUtil::checkNotEmpty($this->_itemSearchInfo->getOrder())) {
            if (!in_array($this->_itemSearchInfo->getOrder(), $this->_itemconstraints["search_order"]["value"])) {
                WarnLogger::write("Search order is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
            
            } else {            
            }
        
        } else {    
        }

        
        // Validate latitude & longtitude
        if (!is_null($this->_itemSearchInfo->getLatitude()) && !is_null($this->_itemSearchInfo->getLongtitude())) {
            if (!CheckUtil::checkNotEmpty($this->_itemSearchInfo->getLatitude())) {
                WarnLogger::write("Latitude is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
            
            } else if (!CheckUtil::checkIsFloat($this->_itemSearchInfo->getLatitude())) {
                WarnLogger::write("Latitude is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
            
            } else {    
            }
        
            if (!CheckUtil::checkNotEmpty($this->_itemSearchInfo->getLongtitude())) {
                WarnLogger::write("Longtitude is empty.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
                
            } else if (!CheckUtil::checkIsFloat($this->_itemSearchInfo->getLongtitude())) {
                WarnLogger::write("Longtitude is invalid.");
                return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
            } else {
            }
            
        } else {
        }
        
        $result = null;
        
        if (count($error) == 0) {
            InfoLogger::write("Search information is valid.");
            $result = OutputUtil::getSuccessOutput();
        
        } else {
            InfoLogger::write("Search information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        
        }
        
        return $result;
    }
}
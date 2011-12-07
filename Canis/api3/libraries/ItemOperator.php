<?php

/**
* The class to operate item
*
* [method]
* + changeItemstatus : The method to change item status
* + getItemDetail : The method to get item detail
* + registerItem : The method to register item information to DB
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ItemOperator
{
    private $_item = null;
    

    public function __construct($item)
    {
        $this->_item = $item;
    }

    // function to change item status to DB
    public function changeItemstatus($newStatus) {
    
        DebugLogger::write("Item status will be changed from now.");

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
        } else {
        }
    
        // prepare SQL statement
        $stmt = $db_connection->prepare(CHANGE_ITEM_STATUS_QUERY);
        $stmt->bindValue(":ITEMID",       $this->_item->getItemid(), PDO::PARAM_STR);
        $stmt->bindValue(":ITEMSTATUS",   $newStatus,                PDO::PARAM_STR);
        $stmt->bindValue(":UPDATEDTIME",  date("Y-m-d H:i:s"),     PDO::PARAM_STR);
        
        // execute SQL
        try {
            $stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Change item status failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        DebugLogger::write("DB update succeeded.");
    
        // close prepared statement
        $stmt = null;
    
        return OutputUtil::getSuccessOutput();
    }


    // function to get item detail
    public function getItemDetail() {
    
        DebugLogger::write("Item detail will be got from now.");
    
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
        } else {            
        }
    
        // Make prepared statement
        $stmt = $db_connection->prepare(GET_ITEM_DETAIL_QUERY);
    
        // Bind currency
        $stmt->bindValue(":ITEMID", $this->_item->getItemid(), PDO::PARAM_STR);
    
        // execute SQL
        try {
            $stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Item selection failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        DebugLogger::write("Item selection succeeded.");
    
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = OutputUtil::getSuccessOutput($items[0]);
    
        // close prepared statement
        $stmt = null;
    
        return $result;
    }
    
    
    // function to regist item to DB
    public function registerItem() {
    
        DebugLogger::write("item will be inserted to DB from now.");
    
        $result = array();

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
        } else {
        }
    
        if ($this->_item->getLocationtype() == "ZIPCODE") {
            $getLocationDataResult = LocationUtil::getLocationDataFromZipcode($this->_item->getZipcode());
            if ($getLocationDataResult["status"] != "true") {
                InfoLogger::write("There was no location data for requested zipcode.");
                return $getLocationDataResult;
                    
            } else {
            }
    
            $this->_item->setState($getLocationDataResult["state"]);
            $this->_item->setCity($getLocationDataResult["city"]);
            $this->_item->setLatitude($getLocationDataResult["latitude"]);
            $this->_item->setLongtitude($getLocationDataResult["longtitude"]);
    
    
        } else if ($this->_item->getLocationtype() == "ADDRESS") {
            $getLocationDataResult = LocationUtil::getLocationDataFromAddress($this->_item->getState(), $this->_item->getCity());
            if ($getLocationDataResult["status"] != "true") {
                InfoLogger::write("There was no location data for requested address.");
                return $getLocationDataResult;
    
            } else {
            }
    
            $this->_item->setZipcode($getLocationDataResult["zipcode"]);
            $this->_item->setLatitude($getLocationDataResult["latitude"]);
            $this->_item->setLongtitude($getLocationDataResult["longtitude"]);
    
        } else if ($this->_item->getLocationtype() == "GEOLOCATION") {
            $getLocationDataResult = LocationUtil::getLocationDataFromGeolocation($this->_item->getLatitude(), $this->_item->getLongtitude());
            if ($getLocationDataResult["status"] != "true") {
                InfoLogger::write("There was no location data for requested address.");
                return $getLocationDataResult;
    
            } else {
            }
    
            $this->_item->setZipcode($getLocationDataResult["zipcode"]);
            $this->_item->setState($getLocationDataResult["state"]);
            $this->_item->setCity($getLocationDataResult["city"]);
                
        } else {
        }
    
        // prepare SQL statement
        $stmt = $db_connection->prepare(REGISTER_ITEM_DML);
        $stmt->bindValue(":GALAXYUSERID", $this->_item->getGalaxyuserid(), PDO::PARAM_STR);
        $stmt->bindValue(":ITEMNAME",     $this->_item->getItemname(),     PDO::PARAM_STR);
        $stmt->bindValue(":DESCRIPTION",  $this->_item->getDescription(),  PDO::PARAM_STR);
        $stmt->bindValue(":PRICE",        $this->_item->getPrice(),        PDO::PARAM_STR);
        $stmt->bindValue(":CURRENCY",     $this->_item->getCurrency(),     PDO::PARAM_STR);
        $stmt->bindValue(":LOCATIONTYPE", $this->_item->getLocationtype(), PDO::PARAM_STR);
        $stmt->bindValue(":ZIPCODE",      $this->_item->getZipcode(),      PDO::PARAM_STR);
        $stmt->bindValue(":STATE",        $this->_item->getState(),        PDO::PARAM_STR);
        $stmt->bindValue(":CITY",         $this->_item->getCity(),         PDO::PARAM_STR);
        $stmt->bindValue(":LATITUDE",     $this->_item->getLatitude(),     PDO::PARAM_STR);
        $stmt->bindValue(":LONGTITUDE",   $this->_item->getLongtitude(),   PDO::PARAM_STR);
        $stmt->bindValue(":UPDATEDTIME",  date("Y-m-d H:i:s"),             PDO::PARAM_STR);
        $stmt->bindValue(":EXPIRYTIME",   date("Y-m-d H:i:s", strtotime("+30 day")),   PDO::PARAM_STR);
        
        // execute SQL
        try {
            $stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Exception has thrown DB insertion.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        DebugLogger::write("DB insertion succeeded.");
    
        // close prepared statement
        $stmt = null;
    
        return OutputUtil::getSuccessOutput(array("itemid" => $db_connection->lastInsertId()));
    }
}

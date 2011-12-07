<?php

/**
* The class to search item
*
* [method]
* + search : The method to search item
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ItemSearcher
{
    private $_itemSearchInfo = null;
    private $_itemconstraints = null;
    
    public function __construct($itemSearchInfo)
    {
        $this->_itemSearchInfo = $itemSearchInfo;
        $this->_itemconstraints = $GLOBALS["itemconstraints"];
    }
    
    // function to search item from DB
    public function search() {
    
        $galaxyuserid = $this->_itemSearchInfo->getGalaxyuserid();
        $value        = $this->_itemSearchInfo->getValue();
        $type         = $this->_itemSearchInfo->getType();
        $order        = $this->_itemSearchInfo->getOrder();
        $latitude     = $this->_itemSearchInfo->getLatitude();
        $longtitude   = $this->_itemSearchInfo->getLongtitude();
        
        DebugLogger::write("Item will be searched from now.");
        $result = null;
    
        if ($value == "") {
        	$result = OutputUtil::getSuccessOutput(array("item" => array()));
        	
        } else {
	        // DB connect
	        $db_connection = GalaxyDbConnector::getConnection();
	        if ($db_connection == null) {
	            ErrorLogger::write("DB connect failed.");
	            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	    
	        } else {
	        }
	    
	    
	        // Get buyer location
	        // TODO : get from user input
	    
	        // prepare SQL statement
	        $stmt = $db_connection->prepare(GET_USER_LOCATION_QUERY);
	        $stmt->bindValue(":GALAXYUSERID", $galaxyuserid, PDO::PARAM_STR);
	    
	        // execute SQL
	        try {
	            $stmt->execute();
	    
	        } catch(Exception $e) {
	            ErrorLogger::write("Item selection failed.", $e);
	            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	        }
	    
	        $galaxyusers = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        $buyerCountry = $galaxyusers[0]["country"];
	    
	        $stmt = null;
	    
	        if ($buyerCountry == "USA") {
	            $currency = "USD";
	    
	        } else if ($buyerCountry == "JAPAN") {
	            $currency = "JPY";
	    
	        } else {
	                
	        }
	    
	    
	        // Make prepared statement
	        if ($type == $this->_itemconstraints["search_type"]["value"]["keyword"]) {
	            $orderCond = null;
	    
	            // prepare SQL statement
	    
	            // itemname condition(for AND search)
	            $value = str_replace("　", " ", $value);
	            $values = explode(" ", $value);
	            $modifiedValues = array();
	            foreach ($values as $tempValue) {
	                if ($tempValue != "") {
	                    $modifiedValues[] = preg_quote($tempValue);
	    
	                } else {
	                }
	            }
	    
	            $itemnameConds = array();
	            foreach ($modifiedValues as $tempValue) {
	                 $itemnameConds[] = "items.itemname regexp " . $db_connection->quote(".*" . $tempValue . ".*");
	            }
	            $itemnameCond = implode(" and ", $itemnameConds);
	    
	            // order by condition
	            if ($order == null || $order == $this->_itemconstraints["search_order"]["value"]["updatedtime_desc"]) {
	                $orderCond = "order by items.updatedtime desc";
	                    
	            } else if ($order == $this->_itemconstraints["search_order"]["value"]["updatedtime_asc"]) {
	                $orderCond = "order by items.updatedtime asc";
	                    
	            } else if ($order == $this->_itemconstraints["search_order"]["value"]["price_desc"]) {
	                $orderCond = "order by items.price desc";
	                    
	            } else if ($order == $this->_itemconstraints["search_order"]["value"]["price_asc"]) {
	                $orderCond = "order by items.price asc";
	    
	            } else {
	    
	            }
	    
	            $query = SEARCH_ITEM_BASE_QUERY_TOP . " and " . $itemnameCond . " " . SEARCH_ITEM_BASE_QUERY_BOTTOM . " " . $orderCond;
	            DebugLogger::write("Query is \"" . $query . "\"");
	            $stmt = $db_connection->prepare($query);
	    
	        } else if ($type == $this->_itemconstraints["search_type"]["value"]["barcode"]) {
	            // TODO : implemente barcode search feature
	            return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
	    
	        } else {        
	        }    
	    
	        // Bind locations
	        if ($latitude == 0 && $longtitude == 0) {
	            $latitude   = $galaxyusers[0]["latitude"];
	            $longtitude = $galaxyusers[0]["longtitude"];
	    
	        } else {
	        }
	    
	        
	        $stmt->bindValue(":LATITUDE1",  $latitude,   PDO::PARAM_STR);
	        $stmt->bindValue(":LONGTITUDE", $longtitude, PDO::PARAM_STR);
	        $stmt->bindValue(":LATITUDE2",  $latitude,   PDO::PARAM_STR);
	    
	        // Bind currency
	        $stmt->bindValue(":CURRENCY", $currency, PDO::PARAM_STR);
	    
	        // execute SQL
	        try {
	            $stmt->execute();
	    
	        } catch(Exception $e) {
	            ErrorLogger::write("Item selection failed.", $e);
	            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	        }
	    
	        DebugLogger::write("Item selection succeeded.");
	    
	        $result = OutputUtil::getSuccessOutput(array("item" => $stmt->fetchAll(PDO::FETCH_ASSOC)));
	    
	        // close prepared statement
	        $stmt = null;

        }
        
        return $result;
    }
}

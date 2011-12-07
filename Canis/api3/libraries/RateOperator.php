<?php

/**
* The class to operate rate
*
* [method]
* + rateUser : The method to rate
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class RateOperator
{
    private $_rate = null;
    private $_rateconstraints = null;
    
    public function __construct($rate)
    {
        $this->_rate = $rate;
        $this->_rateconstraints = $GLOBALS["rateconstraints"];
    }


    // function to rate user
    public function rateUser() {
    
        DebugLogger::write("New rate will be registered from now.");

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }

        // prepare SQL statement
        $rateUserStmt = $db_connection->prepare(RATE_USER);
        $rateUserStmt->bindValue(":GALAXYUSERID", $this->_rate->getGalaxyuserid(), PDO::PARAM_INT);
        $rateUserStmt->bindValue(":RATE",         $this->_rate->getRate(),         PDO::PARAM_STR);
        $rateUserStmt->bindValue(":RATER",        $this->_rate->getRater(),        PDO::PARAM_INT);
        
        // execute SQL
        try {
        	$rateUserStmt->execute();
        	 
        } catch(Exception $e) {
        	ErrorLogger::write("Exception has thrown DB insertion.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        $rateUserStmt = null;

        // Make prepared statement
        $getRateStmt = $db_connection->prepare(GET_RATE);
        
        // Bind galaxyuserid
        $getRateStmt->bindValue(":GALAXYUSERID", $this->_rate->getGalaxyuserid(), PDO::PARAM_INT);
        
        // execute SQL
        try {
        	$getRateStmt->execute();
        
        } catch(Exception $e) {
        	ErrorLogger::write("Rate selection failed.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        $rates = $getRateStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $getRateStmt = null;
        
        $goodCount = 0;
        $fairCount = 0;
        $badCount  = 0;
         
        foreach ($rates as $rate) {
        	if ($rate["rate"] == $this->_rateconstraints["rate"]["value"]["good"]) {
        		$goodCount++;
        		 
        	} else if ($rate["rate"] == $this->_rateconstraints["rate"]["value"]["fair"]) {
        		$fairCount++;
        
        	} else if ($rate["rate"] == $this->_rateconstraints["rate"]["value"]["bad"]) {
        		$badCount++;
        		 
        	} else {
        	}
        }
         
        $normalizedAverageRate = ($goodCount * 1 + $fairCount * 0 + $badCount * (-1)) / (count($rates));
        $averageRate = round(($normalizedAverageRate + 1) * 2.5, 1);
        
        // prepare SQL statement
        $updateAverageRateStmt = $db_connection->prepare(UPDATE_AVERAGE_RATE);
        $updateAverageRateStmt->bindValue(":GALAXYUSERID", $this->_rate->getGalaxyuserid(), PDO::PARAM_INT);
        $updateAverageRateStmt->bindValue(":AVERAGERATE",  strval($averageRate),            PDO::PARAM_STR);
         
        // execute SQL
        try {
        	$updateAverageRateStmt->execute();
        
        } catch(Exception $e) {
        	ErrorLogger::write("Exception has thrown DB insertion.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        $updateAverageRateStmt = null;

        DebugLogger::write("New rate registering succeeded.");

        return OutputUtil::getSuccessOutput();
    }
}

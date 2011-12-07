<?php

/**
* The class to operate messagereadhistory
*
* [method]
* + sendMessage : The method to send message
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class MessageReadHistoryOperator
{
    private $_messageReadHistory = null;

    public function __construct($messageReadHistory)
    {
        $this->_messageReadHistory = $messageReadHistory;
    }


    // function to update last read message
    public function updateLastReadMessage() {

        DebugLogger::write("Last read message will be updated from now.");

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }

        // prepare SQL statement
        $stmt = $db_connection->prepare(UPDATE_LASTREADMESSAGE);
        $stmt->bindValue(":THREADID",        $this->_messageReadHistory->getThreadid(),        PDO::PARAM_INT);
        $stmt->bindValue(":GALAXYUSERID",    $this->_messageReadHistory->getGalaxyuserid(),    PDO::PARAM_INT);
        $stmt->bindValue(":LASTREADMESSAGE", $this->_messageReadHistory->getLastReadMessage(), PDO::PARAM_INT);
         
        // execute SQL
        try {
        	$stmt->execute();
        	 
        } catch(Exception $e) {
        	ErrorLogger::write("Exception has thrown DB insertion.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }

        $stmt = null;
        
        DebugLogger::write("Last read message updating succeeded.");

        return OutputUtil::getSuccessOutput();
    }
}

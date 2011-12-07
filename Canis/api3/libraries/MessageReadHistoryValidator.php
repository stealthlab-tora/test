<?php

/**
* The class to validate message read history
*
* [method]
* + validateMessageReadHistory : The method to validate message read history
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class MessageReadHistoryValidator
{
    private $_messageReadHistory = null;
    
    public function __construct($messageReadHistory)
    {
        $this->_messageReadHistory = $messageReadHistory;
    }


    // function to validate message read history
    public function validateMessageReadHistory() {
    
        DebugLogger::write("Message read history will be validated from now.");

        $error = array();

        $threadid        = $this->_messageReadHistory->getThreadid();
        $galaxyuserid    = $this->_messageReadHistory->getGalaxyuserid();
        $lastreadmessage = $this->_messageReadHistory->getLastreadmessage();
        
        // Validate threadid

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }
        
        // prepare SQL statement
        $getMessageReadHistoryStmt = $db_connection->prepare(GET_MESSAGE_READ_HISTORY);
        $getMessageReadHistoryStmt->bindValue(":THREADID",     $threadid, PDO::PARAM_STR);
        $getMessageReadHistoryStmt->bindValue(":GALAXYUSERID", $galaxyuserid, PDO::PARAM_STR);
        
        // execute SQL
        try {
        	$getMessageReadHistoryStmt->execute();
        	
        } catch(Exception $e) {
        	ErrorLogger::write("Message read history select operation failed.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        $messageReadHistories = $getMessageReadHistoryStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // close prepared statement
        $getMessageReadHistoryStmt = null;
        
        // message read history existence judgement
        if (count($messageReadHistories) == 1) {
        	InfoLogger::write("There is a message read history which has the requested threadid and galaxyuserid.");

        } else {
        	WarnLogger::write("There is no message read history which has the requested threadid and galaxyuserid.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        }
        
        
        // prepare SQL statement
        $getMessageStmt = $db_connection->prepare(GET_MESSAGE);
        $getMessageStmt->bindValue(":THREADID",  $threadid,        PDO::PARAM_STR);
        $getMessageStmt->bindValue(":MESSAGEID", $lastreadmessage, PDO::PARAM_STR);
        
        // execute SQL
        try {
        	$getMessageStmt->execute();
        	
        } catch(Exception $e) {
        	ErrorLogger::write("Message read history select operation failed.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        $messages = $getMessageStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // close prepared statement
        $getMessageStmt = null;
        
        // message read history existence judgement
        if (count($messages) == 1) {
        	InfoLogger::write("There is a message which has the requested threadid and messageid.");

        } else {
        	WarnLogger::write("There is no message which has the requested threadid and messageid.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        }
                
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("Message read history is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("Message read history is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
}

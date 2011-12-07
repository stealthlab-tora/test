<?php

/**
* The class to validate message information
*
* [method]
* + validateMessage : The method to validate message information
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class MessageValidator
{
    private $_message = null;
    private $_messageconstraints = null;
    
    public function __construct($message)
    {
        $this->_message = $message;
        $this->_messageconstraints = $GLOBALS["messageconstraints"];
    }


    // function to send message
    public function validateMessage() {
    
        DebugLogger::write("Message information will be validated from now.");

        $error = array();

        $threadid = $this->_message->getThreadid();
        $sender   = $this->_message->getSender();
        $receiver = $this->_message->getReceiver();
        
        // Validate threadid

        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }
        
        // prepare SQL statement
        $stmt = $db_connection->prepare(GET_THREAD_INFO);
        $stmt->bindValue(":THREADID", $threadid, PDO::PARAM_STR);
        
        // execute SQL
        try {
        	$stmt->execute();
        
        	// get result number
        	$threads    = $stmt->fetchAll(PDO::FETCH_ASSOC);
        	$threadsNum = count($threads);
        
        	// close prepared statement
        	$stmt = null;
        
        	// user id existence judgement
        	if ($threadsNum == 1) {
        		InfoLogger::write("There is a thread which has the requested threadid.");

        	} else {
        		WarnLogger::write("There is no thread which has the requested threadid.");
        		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	}
        	
        } catch(Exception $e) {
        	ErrorLogger::write("Thread select operation failed.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
        
        // Validate sender and receiver
        if (($threads[0]["seller"] == $sender && $threads[0]["buyer"] == $receiver) ||
            ($threads[0]["seller"] == $receiver && $threads[0]["buyer"] == $sender)) {
        	DebugLogger::write("Sender and Receiver information is correct.");
        	
        } else {
        	ErrorLogger::write("Sender and Receiver information is not correct.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	 
        }

        
        // Validate message
        if (is_null($this->_message->getMessage())) {
        	WarnLogger::write("Message is not requested.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
        	if (!CheckUtil::checkNotEmpty($this->_message->getMessage())) {
        		InfoLogger::write("Message is empty.");
        		$error[] = USER_EMPTY_MESSAGE;
        
        	} else if (!CheckUtil::checkMaxLength($this->_message->getMessage(), $this->_messageconstraints["message"]["max_length"])) {
        		InfoLogger::write("Message is too long.");
        		$error[] = USER_INVALID_MESSAGE;
        
        	} else {
        	}
        }
        
        $result = null;
    
        if (count($error) == 0) {
            InfoLogger::write("Message information is valid.");
            $result = OutputUtil::getSuccessOutput();
    
        } else {
            InfoLogger::write("Message information is invalid.");
            $result = OutputUtil::getErrorOutput($error);
        }
    
        return $result;
    }
}

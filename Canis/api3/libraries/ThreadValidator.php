<?php

/**
* The class to validate thread information
*
* [method]
* + validateThread : The method to validate itemid, buyer of thread
* + validateThreadid : The method to validate threadid
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ThreadValidator
{
    private $_thread = null;
    
    public function __construct($thread)
    {
        $this->_thread = $thread;
    }


    // function to send thread
    public function validateThread() {
    
        DebugLogger::write("Thread information will be validated from now.");

        // Validate itemid
        $item = new Item();
        $item->setItemid($this->_thread->getItemid());
        $itemidValidator = new ItemValidator($item);
        $itemidValidateResult = $itemidValidator->validateItemId();
        if ($itemidValidateResult["status"] != "true") {
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	 
        } else {
        }

        // Validate buyer
        $buyer = new User();
        $buyer->setGalaxyuserid($this->_thread->getBuyer());
        $buyerValidator = new UserValidator($buyer);
        $buyerValidateResult = $buyerValidator->validateGalaxyuserid();
        if ($buyerValidateResult["status"] != "true") {
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        
        } else {
        }
        
        
        InfoLogger::write("Thread information is valid.");
        return OutputUtil::getSuccessOutput();
    }


    // public function to validate galaxyuserid
    public function validateThreadid()
    {
    	$threadid = $this->_thread->getThreadid();
    
    	DebugLogger::write("Threadid will be validated from now.");
    
    	// Validate threadid
    	if (is_null($threadid)) {
    		WarnLogger::write("Threadid is not requested.");
    		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
    	} else {
    		if (!CheckUtil::checkNotEmpty($threadid)) {
    			WarnLogger::write("Threadid is empty.");
    			return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
    		} else {
    			DebugLogger::write("Thread existing check will be done from now.");
    
    			// DB connect
    			$db_connection = GalaxyDbConnector::getConnection();
    
    			// prepare SQL statement
    			$stmt = $db_connection->prepare(THREAD_EXIST_CHECK);
    			$stmt->bindValue(":THREADID", $threadid, PDO::PARAM_STR);
    
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
    					InfoLogger::write("There is a thread which has the requested threadid.");
    
    				} else {
    					WarnLogger::write("There is no thread which has the requested threadid.");
    					return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    				}
    
    			} catch(Exception $e) {
    				ErrorLogger::write("Thread select operation failed.", $e);
    				return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    			}
    		}
    	}
    
    	DebugLogger::write("Requested threadid is valid.");
    
    	return OutputUtil::getSuccessOutput();
    }
}

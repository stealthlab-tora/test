<?php

/**
* The class to operate thread
*
* [method]
* + getChannlenameByItemidAndBuyer : The method to get channelname by itemid and buyer and create thread and channelname if there is no thread
* + getChannlenameByItemidAndBuyer : The method to get channelname by threadid
* + getMessages : The method to get messages with threadid
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ThreadOperator
{
    private $_thread = null;

    public function __construct($thread)
    {
        $this->_thread = $thread;
    }


    // function to get channel name
    public function getChannelnameByItemidAndBuyer() {
    
        DebugLogger::write("Channel name will be got from now.");


        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }

        // prepare SQL statement
        $getThreadStmt = $db_connection->prepare(GET_THREAD_ID);
        $getThreadStmt->bindValue(":ITEMID",  $this->_thread->getItemid(),  PDO::PARAM_INT);
        $getThreadStmt->bindValue(":BUYER", $this->_thread->getBuyer(), PDO::PARAM_INT);

        // execute SQL
        try {
        	$getThreadStmt->execute();
        
        } catch(Exception $e) {
        	ErrorLogger::write("Exception has thrown in DB select operation.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }

        // get result number
        $getThreadResult = $getThreadStmt->fetchAll(PDO::FETCH_ASSOC);
        $getThreadResultNum = count($getThreadResult);
        
        // close prepared statement
        $getThreadStmt = null;
        
        // Thread check
        if ($getThreadResultNum == 0) {
        	InfoLogger::write("There is a user which has the requested galaxyuserid.");

        	$this->_thread->setChannelname(md5($this->_thread->getItemid() . $this->_thread->getBuyer() . CHANNEL_NAME_SALT_WORD));
        	
        	// prepare SQL statement
        	$createThreadStmt = $db_connection->prepare(CREATE_THREAD);
        	$createThreadStmt->bindValue(":ITEMID",      $this->_thread->getItemid(),      PDO::PARAM_INT);
        	$createThreadStmt->bindValue(":BUYER",       $this->_thread->getBuyer(),     PDO::PARAM_INT);
        	$createThreadStmt->bindValue(":CHANNELNAME", $this->_thread->getChannelname(), PDO::PARAM_STR);
        	
        	// execute SQL
        	try {
        		$createThreadStmt->execute();
        	
        	} catch(Exception $e) {
        		ErrorLogger::write("Exception has thrown DB insertion.", $e);
        		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        	}

        	$createThreadStmt = null;

        	$this->_thread->setThreadid($db_connection->lastInsertId());


        	// prepare SQL statement
        	$getSellerStmt = $db_connection->prepare(GET_SELLER);
        	$getSellerStmt->bindValue(":ITEMID", $this->_thread->getItemid(), PDO::PARAM_INT);
        	 
        	// execute SQL
        	try {
        		$getSellerStmt->execute();
        		 
        	} catch(Exception $e) {
        		ErrorLogger::write("Item select operation failed.", $e);
        		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        	}

        	$items = $getSellerStmt->fetchAll(PDO::FETCH_ASSOC);
        	
        	if (count($items) > 0) {
            	$seller = $items[0]["galaxyuserid"];
        	
        	} else {
        		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	}
        		
        	$getSellerStmt = null;


        	// prepare SQL statement
        	$createMessageReadHistoryStmt = $db_connection->prepare(CREATE_MESSAGE_READ_HISTORY);
        	$createMessageReadHistoryStmt->bindValue(":THREADID", $this->_thread->getThreadid(), PDO::PARAM_INT);
        	$createMessageReadHistoryStmt->bindValue(":SELLER",   $seller,                       PDO::PARAM_INT);
        	$createMessageReadHistoryStmt->bindValue(":BUYER",    $this->_thread->getBuyer(),    PDO::PARAM_INT);
        	 
        	
        	// execute SQL
        	try {
        		$createMessageReadHistoryStmt->execute();
        		 
        	} catch(Exception $e) {
        		ErrorLogger::write("Exception has thrown DB insertion.", $e);
        		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        	}
        	
        	$createMessageReadHistoryStmt = null;
        	
        	
        } else if ($getThreadResultNum == 1) {
        	$this->_thread->setThreadid($getThreadResult[0]["threadid"]);
        	$this->_thread->setChannelname($getThreadResult[0]["channelname"]);
        
        } else {
        	WarnLogger::write("There is no user which has the requested galaxyuserid.");
        	return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
        	 
        }

        
        DebugLogger::write("Chat thread sending succeeded.");

        return OutputUtil::getSuccessOutput(array("threadid" => $this->_thread->getThreadid(), "channelname" => $this->_thread->getChannelname()));
    }


    // function to get channel name
    public function getChannelnameByThreadid() {
    
    	DebugLogger::write("Channel name will be got from now.");
    
    
    	// DB connect
    	$db_connection = GalaxyDbConnector::getConnection();
    	if ($db_connection == null) {
    		ErrorLogger::write("DB connect failed.");
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
    	} else {
    	}
    
    	// prepare SQL statement
    	$stmt = $db_connection->prepare(GET_CHANNELNAME_BY_THREADID);
    	$stmt->bindValue(":THREADID",  $this->_thread->getThreadid(),  PDO::PARAM_INT);
    
    	// execute SQL
    	try {
    		$stmt->execute();
    
    	} catch(Exception $e) {
    		ErrorLogger::write("Exception has thrown in DB select operation.", $e);
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    	}
    
    	// get result number
    	$getChannelnameResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    	$getChannelnameResultNum = count($getChannelnameResult);
    
    	// close prepared statement
    	$stmt = null;

    	$result =null;
    	
    	// Thread check
    	if ($getChannelnameResultNum == 1) {
    		InfoLogger::write("Channel name getting succeeded.");
    		$result = OutputUtil::getSuccessOutput(array("channelname" => $getChannelnameResult[0]["channelname"]));

    	} else {
    		WarnLogger::write("There is no thread which has the requested threadid.");
    		return OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
    
    	}
    
    	return $result;
    }


    // function to get threads from DB
    public function getMessages() {
    
    	DebugLogger::write("Messages will be got from now.");
    
    	// DB connect
    	$db_connection = GalaxyDbConnector::getConnection();
    	if ($db_connection == null) {
    		ErrorLogger::write("DB connect failed.");
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
    	} else {
    	}
    
    	// Make prepared statement
    	$stmt = $db_connection->prepare(GET_MESSAGES);
    
    	// Bind galaxyuserid
    	$stmt->bindValue(":THREADID", $this->_thread->getThreadid(), PDO::PARAM_INT);
    
    	// execute SQL
    	try {
    		$stmt->execute();
    
    	} catch(Exception $e) {
    		ErrorLogger::write("Message selection failed.", $e);
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    	}
    
    	DebugLogger::write("Message selection succeeded.");
    
    	$result = null;
    	$result = OutputUtil::getSuccessOutput(array("message" => $stmt->fetchAll(PDO::FETCH_ASSOC)));
    
    	// close prepared statement
    	$stmt = null;
    
    	return $result;
    }


    // function to change threadstatus
    public function changeThreadstatus($newStatus) {
    
    	DebugLogger::write("Threadstatus will be changed from now.");
    
    	// DB connect
    	$db_connection = GalaxyDbConnector::getConnection();
    	if ($db_connection == null) {
    		ErrorLogger::write("DB connect failed.");
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
    	} else {
    	}
    
    	// Make prepared statement
    	$stmt = $db_connection->prepare(CHANGE_THREADSTATUS);
    
    	// Bind galaxyuserid
    	$stmt->bindValue(":THREADID",     $this->_thread->getThreadid(), PDO::PARAM_INT);
    	$stmt->bindValue(":THREADSTATUS", $newStatus, PDO::PARAM_INT);
    	
    	// execute SQL
    	try {
    		$stmt->execute();
    
    	} catch(Exception $e) {
    		ErrorLogger::write("Thread update failed.", $e);
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    	}
    
    	// close prepared statement
    	$stmt = null;
    
    	return OutputUtil::getSuccessOutput();
    }
    
    
    // function to close threads which has same itemid as the requested itemid.
    public function closeSameItemidThreads() {
    
    	DebugLogger::write("Threads will be closed from now.");
    
    	// DB connect
    	$db_connection = GalaxyDbConnector::getConnection();
    	if ($db_connection == null) {
    		ErrorLogger::write("DB connect failed.");
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
    	} else {
    	}
    
    	// Make prepared statement
    	$stmt = $db_connection->prepare(CLOSE_THREADS);
    
    	// Bind galaxyuserid
    	$stmt->bindValue(":ITEMID", $this->_thread->getItemid(), PDO::PARAM_INT);
    	 
    	// execute SQL
    	try {
    		$stmt->execute();
    
    	} catch(Exception $e) {
    		ErrorLogger::write("Thread update failed.", $e);
    		return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    	}
    
    	// close prepared statement
    	$stmt = null;
    
    	return OutputUtil::getSuccessOutput();
    }

    
    // function to notify item sold information
    public function notifyItemSoldInfo() {
    
    	DebugLogger::write("Item sold information will be notified from now.");
    
    	$getChannelnameResult = $this->getChannelnameByThreadid();
    	if ($getChannelnameResult["status"] == "true") {
    		$channelname = $getChannelnameResult["channelname"];
    		
    	} else {
    		WarnLogger::write(array(SRV_SYSTEM_ERROR_NONE));
    	
    	}
    	
    	$pushData = array();
    	$pusher = new Pusher(PUSHER_API_KEY, PUSHER_API_SECRET, PUSHER_APP_ID);
    	$pusher->trigger($channelname, PUSHER_ITEM_SOLD_EVENT, $pushData);
    	
    	return OutputUtil::getSuccessOutput();
    }
}

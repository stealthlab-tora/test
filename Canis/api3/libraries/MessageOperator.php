<?php

/**
* The class to operate message
*
* [method]
* + sendMessage : The method to send message
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class MessageOperator
{
    private $_message = null;

    public function __construct($message)
    {
        $this->_message = $message;
    }


    // function to send message
    public function sendMessage() {
    
        DebugLogger::write("Chat Message will be sent from now.");

        $channelname = null;
        $thread               = new Thread(array("threadid" => $this->_message->getThreadid()));
        $threadOperator       = new ThreadOperator($thread);
        $getChannelnameResult = $threadOperator->getChannelnameByThreadid();
        if ($getChannelnameResult["status"] == "true") {
            $channelname = $getChannelnameResult["channelname"];
        
        } else {
        	ErrorLogger::write("Channelname getting failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        	
        }
        
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
        	ErrorLogger::write("DB connect failed.");
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
        } else {
        }

        // prepare SQL statement
        $saveMessageStmt = $db_connection->prepare(SAVE_MESSAGE);
        $saveMessageStmt->bindValue(":THREADID", $this->_message->getThreadid(), PDO::PARAM_INT);
        $saveMessageStmt->bindValue(":SENDER",   $this->_message->getSender(),   PDO::PARAM_INT);
        $saveMessageStmt->bindValue(":RECEIVER", $this->_message->getReceiver(), PDO::PARAM_INT);
        $saveMessageStmt->bindValue(":MESSAGE",  $this->_message->getMessage(),  PDO::PARAM_STR);
        $saveMessageStmt->bindValue(":SENTTIME", date("Y-m-d H:i:s"),            PDO::PARAM_STR);
        
        // execute SQL
        try {
        	$saveMessageStmt->execute();
        	 
        } catch(Exception $e) {
        	ErrorLogger::write("Exception has thrown DB insertion.", $e);
        	return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }

        $saveMessageStmt = null;
        
        // TODO : Add time stamp
        $pushData = array();
        $pushData["messageid"] = $db_connection->lastInsertId();
        $pushData["sender"]    = $this->_message->getSender();
        $pushData["receiver"]  = $this->_message->getReceiver();
        $pushData["message"]   = $this->_message->getMessage();
        $pusher = new Pusher(PUSHER_API_KEY, PUSHER_API_SECRET, PUSHER_APP_ID);
        $pusher->trigger($channelname, PUSHER_NEW_CHAT_MESSAGE_EVENT, $pushData);
        
        DebugLogger::write("Chat message sending succeeded.");

        return OutputUtil::getSuccessOutput();
    }
}

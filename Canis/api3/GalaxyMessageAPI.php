<?php

/**
* The API around Message
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


if (!isset($_POST["action"])) {
	WarnLogger::write("Request without action has come.");

} else if ($_POST["action"] == "POST") {
    
    DebugLogger::write("Send chat message operaion starts.");

    $message          = new Message($_POST);
    $messageValidator = new MessageValidator($message);
    $messageOperator  = new MessageOperator($message);
    

    // Validation
    $validateResult = $messageValidator->validateMessage();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }

    
    // Send message
    $SendMessageResult = $messageOperator->sendMessage();
    echo(json_encode($SendMessageResult));
    
    DebugLogger::write("Send chat message operation ends.");


} else if ($_POST["action"] == "GET") {
    
    DebugLogger::write("Get chat message operaion starts.");

    $thread          = new Thread($_POST);
    $threadValidator = new ThreadValidator($thread);
    $threadOperator  = new ThreadOperator($thread);
    

    // Validation
    $validateResult = $threadValidator->validateThreadid();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }

    
    // Get messages
    $getMessagesResult = $threadOperator->getMessages();
    echo(json_encode($getMessagesResult));
    
    DebugLogger::write("Get chat message operation ends.");


} else if ($_POST["action"] == "GET_CHANNELNAME") {
    
    DebugLogger::write("Get channelname operaion starts.");

    $thread          = new Thread($_POST);
    $threadValidator = new ThreadValidator($thread);
    $threadOperator  = new ThreadOperator($thread);

    
    // Validation
    $validateResult = $threadValidator->validateThread();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }

    
    // Get messages
    $getChannelnameResult = $threadOperator->getChannelnameByItemidAndBuyer();
    echo(json_encode($getChannelnameResult));
    
    DebugLogger::write("Get channelname operation ends.");


} else if ($_POST["action"] == "LOG_LAST_READ_MESSAGE") {
    
    DebugLogger::write("Log last read message operaion starts.");
    
    $messageReadHistory          = new MessageReadHistory($_POST);
    $messageReadHistoryValidator = new MessageReadHistoryValidator($messageReadHistory);
    $messageReadHistoryOperator  = new MessageReadHistoryOperator($messageReadHistory);
    
    
    // Validation
    $validateResult = $messageReadHistoryValidator->validateMessageReadHistory();
    if ($validateResult["status"] != "true") {
    	echo(json_encode($validateResult));
    	return;
    
    } else {
    }
    
    
    // update last read message
    $updateLastReadMessageResult = $messageReadHistoryOperator->updateLastReadMessage();
    echo(json_encode($updateLastReadMessageResult));

    DebugLogger::write("Log last read message operaion ends.");
    
    
} else {
    WarnLogger::write("Request without proper action has come.");
}

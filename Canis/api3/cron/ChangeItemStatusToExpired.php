<?php

/**
* The PHP file called by cron to change item status to expired.
*
*/


try {
	$_SERVER["DOCUMENT_ROOT"] = "/home/dotcloud/current";

	require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";
	
	DebugLogger::write("From now, items which expiry dates are until yesterday will be expired.");

	
	/**
	* Expired items will be selected.
	*
	*/
	
	// DB connect
	$db_connection = GalaxyDbConnector::getConnection();
	if ($db_connection == null) {
	    ErrorLogger::write("DB connect failed.");
	    return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
	} else {
	}
	
	// prepare SQL statement
	$selectItemsStmt = $db_connection->prepare(GET_EXPIRED_ITEM_QUERY);
	$selectItemsStmt->bindValue(":TODAY", date("Y-m-d H:i:s", strtotime("today")), PDO::PARAM_STR);
	
	// initiate a variable to store items
	$selectedItems = null;
	
	// execute SQL
	try {
	    $selectItemsStmt->execute();
	        
	    // get result number
	    $selectedItems = $selectItemsStmt->fetchAll(PDO::FETCH_ASSOC);
	        
	} catch(Exception $e) {
	    ErrorLogger::write("Item select operation failed.", $e);
	}
	
	// close prepared statement
	$selectItemsStmt = null;
	
	
	/**
	* The status of expired items will be changed to "EXPIRED" and thread related to them will be closed.
	*
	*/
	
	// Judge there are expired items or not.
	if ($selectedItems != null && count($selectedItems) > 0) {
		
		$itemidList = array();
	
		foreach ($selectedItems as $tempItem) {
			$itemidList[] = $tempItem["itemid"];
		}
		
		$itemidCond = " (" . implode(", ", $itemidList) . ") ";
		
		// prepare SQL statement
		$changeItemStatusesStmt = $db_connection->prepare(CHANGE_ITEM_STATUSES_TO_EXPIRED . $itemidCond);
		$changeItemStatusesStmt->bindValue(":UPDATEDTIME",  date("Y-m-d H:i:s"),             PDO::PARAM_STR);
		
		$closeThreadsStmt       = $db_connection->prepare(CLOSE_THREADS_OF_EXPIRED_ITEMS . $itemidCond);
		
		// begin transaction
		$db_connection->beginTransaction();
		
		// execute SQL
		try {		
		    $changeItemStatusesStmt->execute();
		    $closeThreadsStmt->execute();
		    
		    // commit
		    $db_connection->commit();
	
		} catch(Exception $e) {
			// rollback
			$db_connection->rollBack();
		    ErrorLogger::write("Item or thread update operation failed.", $e);
		}
		
		// close prepared statement
		$changeItemStatusesStmt = null;
		$closeThreadsStmt       = null;
		
	} else {
	}
	
	DebugLogger::write("Items has been expired well.");

} catch (Exception $e) {
    echo($e->getMessage());
}

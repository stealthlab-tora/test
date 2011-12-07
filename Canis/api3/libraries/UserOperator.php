<?php

/**
 * The class to operate user information
 *
 * [method]
 * + login : The method to login
 * + changePassword : The method to change password
 * + registerUser : The method to register user
 * + resetPassword : The method to reset password
 * + sendTempPassword : The method to send password to user by Email
 * + getRecentSellItem : The method to get items which are sold by user recently
 * - _getRandomString : The method to get random 6-8 length string
 *
 */

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class UserOperator
{
	private $_user = null;
	private $_userconstraints = null;
	private $_rateconstraints = null;
	
	public function __construct($user)
	{
		$this->_user = $user;
		$this->_userconstraints = $GLOBALS["userconstraints"];
		$this->_rateconstraints = $GLOBALS["rateconstraints"];
	}

	
	// public static function to login to galaxy
	public function login()
	{
		DebugLogger::write("User information will be searched from DB from now.");
	
		$result = array();
	
		// encript password
		$this->_user->setPassword(md5($this->_user->getPassword() . USER_PASSWORD_SALT_WORD));
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
	
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
			 
		} else {
			
		}
	
		// prepare SQL statement
		$stmt = $db_connection->prepare(LOGIN_QUERY);
		$stmt->bindValue(":EMAIL", $this->_user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":PASSWORD", $this->_user->getPassword(), PDO::PARAM_STR);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("User select operation failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		// get result number
		$queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$queryResultNum = count($queryResult);
	
		// close prepared statement
		$stmt = null;
	
		// login judgement
		if ($queryResultNum == 1) {
			InfoLogger::write("User information found.");
	
			unset($queryResult[0]["password"]);
			$result = OutputUtil::getSuccessOutput($queryResult[0]);

		} else {
			InfoLogger::write("User information not found.");
			$result = OutputUtil::getErrorOutput(array(USER_LOGIN_FAILURE_NONE));
	
		}
	
		return $result;
	}

	
	// public static function to change password
	public function changePassword()
	{
		DebugLogger::write("Password will be changed from now.");
	
		$result = array();
	
		// encode password using MD5
		$modifiedPassword = (md5($this->_user->getPassword() . USER_PASSWORD_SALT_WORD));
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
	
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
			
		}
	
		// prepare SQL statement
		$stmt = $db_connection->prepare(CHANGE_PASSWORD_QUERY);
		$stmt->bindValue(":PCREQUESTCODE", $this->_user->getPcrequestcode(), PDO::PARAM_STR);
		$stmt->bindValue(":PASSWORD",	  $modifiedPassword,				PDO::PARAM_STR);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Exception has thrown DB insertion.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		DebugLogger::write("DB insertion succeeded.");
	
		// close prepared statement
		$stmt = null;
	
		return OutputUtil::getSuccessOutput();
	}
	
	
	// public static function to regist user to galaxy
	public function registerUser() {
	
		DebugLogger::write("User information will be inserted to DB from now.");
	
		// encode password using MD5
		$modifiedPassword = md5($this->_user->getPassword() . USER_PASSWORD_SALT_WORD);
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
	
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
			
		}
	
		$getLocationDataResult = LocationUtil::getLocationDataFromZipcode($this->_user->getZipcode());
		if ($getLocationDataResult["status"] != "true") {
			InfoLogger::write("There was no location data for requested zipcode.");
			return OutputUtil::getErrorOutput(array(USER_INVALID_ZIPCODE));
	
		} else {
			
		}
	
		// prepare SQL statement
		$stmt = $db_connection->prepare(REGIST_USER_QUERY);
		$stmt->bindValue(":FIRSTNAME",		$this->_user->getFirstname(),   PDO::PARAM_STR);
		$stmt->bindValue(":LASTNAME",		 $this->_user->getLastname(),	PDO::PARAM_STR);
		$stmt->bindValue(":EMAIL",			$this->_user->getEmail(),	   PDO::PARAM_STR);
		$stmt->bindValue(":PASSWORD",		 $modifiedPassword,	PDO::PARAM_STR);
		$stmt->bindValue(":ZIPCODE",		  $this->_user->getZipcode(),	 PDO::PARAM_STR);
		$stmt->bindValue(":COUNTRY",		  $this->_user->getCountry(),	 PDO::PARAM_STR);
		$stmt->bindValue(":STATE",			$this->_user->getState(),	   PDO::PARAM_STR);
		$stmt->bindValue(":CITY",			 $this->_user->getCity(),		PDO::PARAM_STR);
		$stmt->bindValue(":STREET",		   $this->_user->getStreet(),	  PDO::PARAM_STR);
		$stmt->bindValue(":PHONENUMBER",	  $this->_user->getPhonenumber(), PDO::PARAM_STR);
		$stmt->bindValue(":LATITUDE",		 $getLocationDataResult["latitude"],   PDO::PARAM_STR);
		$stmt->bindValue(":LONGTITUDE",	   $getLocationDataResult["longtitude"], PDO::PARAM_STR);
		$stmt->bindValue(":UPDATEDTIME", date("Y-m-d H:i:s"),  PDO::PARAM_STR);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Exception has thrown DB insertion.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		DebugLogger::write("DB insertion succeeded.");
	
		// close prepared statement
		$stmt = null;
	
		return OutputUtil::getSuccessOutput(array("galaxyuserid" => $db_connection->lastInsertId()));
	}

	
	
	// public static function to reset password
	private function _createPasswordChangeToken() {
	
		DebugLogger::write("Password change token will be created from now.");
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
	
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
		}
	
		$passwordChangeToken = md5($this->_user->getEmail() . strval(time()) . PASSWORD_CHANGE_SALT_WORD);
	
		// Email+ SALT+TIME
		// prepare SQL statement
		$stmt = $db_connection->prepare(REGISTER_PASSWORD_CHANGE_TOKEN);
		$stmt->bindValue(":PCREQUESTCODE",	$passwordChangeToken,	 PDO::PARAM_STR);
		$stmt->bindValue(":EMAIL",				  $this->_user->getEmail(), PDO::PARAM_STR);
		$stmt->bindValue(":PCREQUESTTIME", date("Y-m-d H:i:s"),	  PDO::PARAM_STR);
		
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Exception has thrown DB update.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		DebugLogger::write("DB update succeeded.");
	
		// close prepared statement
		$stmt = null;
	
		$this->_user->setPcrequestcode($passwordChangeToken);
		return OutputUtil::getSuccessOutput();
	}


	// public static function to send temp password to user
	public function sendPasswordChangeLink() {
	
		DebugLogger::write("Password change link notification will be sent to user from now.");
	
		$this->_createPasswordChangeToken();
		
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
	
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
	
		}
	
		// prepare SQL statement
		$stmt = $db_connection->prepare(GET_USER_INFO_QUERY);
		$stmt->bindValue(":EMAIL", $this->_user->getEmail(), PDO::PARAM_STR);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Exception has thrown DB insertion.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
	
		$users   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$country = $users[0]["country"];
		$firstname = $users[0]["firstname"];
		$lastname = $users[0]["lastname"];
	
		// close prepared statement
		$stmt = null;
	
		if ($country == "USA") {
			$subject = PW_RQ_EMAIL_SUBJECT_EN;
			$body	= "Dear " . $firstname . " " . $lastname . "," . PW_RQ_EMAIL_BODY_TOP_EN . PASSWORD_CHANGE_BASEURL . $this->_user->getPcrequestcode() . LANGUAGE_SELECTOR . $this->_userconstraints["language"]["value"]["en"] . PW_RQ_EMAIL_BODY_BTM_EN;
	
		} else if ($country == "JAPAN") {
			$subject = PW_RQ_EMAIL_SUBJECT_JP;
			$body	= $lastname . " " . $firstname . "様" . PW_RQ_EMAIL_BODY_TOP_JP . PASSWORD_CHANGE_BASEURL . $this->_user->getPcrequestcode() . LANGUAGE_SELECTOR . $this->_userconstraints["language"]["value"]["ja"] . PW_RQ_EMAIL_BODY_BTM_JP;
	
		} else {
		}
	
		$sendMailResult = mail($this->_user->getEmail(), $subject, $body, PW_RQ_EMAIL_HEADER);
	
		$result = null;
	
		if ($sendMailResult) {
			InfoLogger::write("Send mail succeeded.");
			$result = OutputUtil::getSuccessOutput();
	
		} else {
			ErrorLogger::write("Send mail failed.");
			$result = OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		return $result;
	}
	
	
	// function to get recent sell item from DB
	public function getRecentSellItem() {
	
		DebugLogger::write("Item will be got from now.");
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
				
		}
		
		// Make prepared statement
		$stmt = $db_connection->prepare(GET_RECENT_SELL_ITEM_QUERY);
	
		// Bind galaxyuserid
		$stmt->bindValue(":GALAXYUSERID", $this->_user->getGalaxyuserid(), PDO::PARAM_STR);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Item selection failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		DebugLogger::write("Item selection succeeded.");
	
		$result = null;
		$result = OutputUtil::getSuccessOutput(array("item" => $stmt->fetchAll(PDO::FETCH_ASSOC)));
	
		// close prepared statement
		$stmt = null;
			
		return $result;
	}


	// function to get thread list
	public function getThreadList() {
	
		DebugLogger::write("Thread list will be got from now.");
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
		}
	
		// Make prepared statement
		$getItemListStmt = $db_connection->prepare(GET_ITEM_LIST_QUERY);
	
		// Bind galaxyuserid
		$getItemListStmt->bindValue(":GALAXYUSERID", $this->_user->getGalaxyuserid(), PDO::PARAM_INT);
	
		// execute SQL
		try {
			$getItemListStmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Item selection failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		$itemList = $getItemListStmt->fetchAll(PDO::FETCH_ASSOC);
		
		$getItemListStmt = null;
		
		$itemIds = array();		
		foreach ($itemList as $tempItem) {
			$itemIds[] = $tempItem["itemid"];
		}
		
		$itemIdCond = null;
		if (count($itemIds)) {
			$itemIdCond = " or threads.itemid in (" . implode(", ", $itemIds) . ")";
		} else {
			$itemIdCond = "";
		}
		
		$orderCond  = " order by threads.threadid desc";

		// Make prepared statement
		$getThreadListStmt = $db_connection->prepare(GET_THREAD_LIST . $itemIdCond . $orderCond);
	
		// Bind galaxyuserid
		$getThreadListStmt->bindValue(":BUYER", $this->_user->getGalaxyuserid(), PDO::PARAM_INT);
	
		// execute SQL
		try {
			$getThreadListStmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("Thread selection failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}

		$threadList = $getThreadListStmt->fetchAll(PDO::FETCH_ASSOC);
	
		// close prepared statement
		$getThreadListStmt = null;

		if (count($threadList) > 0) {
			$threadidList = array();
			foreach ($threadList as $tempThread) {
				$threadidList[] = $tempThread["threadid"];
			}
			$threadidCond = " (" . implode(", ", $threadidList) . ") ";
			
			// Make prepared statement
			$getLatestMessageStmt = $db_connection->prepare(GET_LATEST_MESSAGE_TOP . $threadidCond . GET_LATEST_MESSAGE_BOTTOM);

			// execute SQL
			try {
				$getLatestMessageStmt->execute();
				 
			} catch(Exception $e) {
				ErrorLogger::write("Message selection failed.", $e);
				return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
			}
			
			$messages = $getLatestMessageStmt->fetchAll(PDO::FETCH_ASSOC);
			
			$getLatestMessageStmt = null;
			
			$messageidList		   = array();
			$messageHavingthreadList = array();
			foreach ($messages as $tempMessage) {
				$messageidList[]		   = $tempMessage["messageid"];
				$messageHavingthreadList[] = $tempMessage["threadid"];
			}
			
			$noUpdateThreadList = array();
			
			if (count($messages) > 0) {
				$messageidCond = " (" . implode(", ", $messageidList) . ") ";
				
				// Make prepared statement
				$getNoUpdateThreadListStmt = $db_connection->prepare(GET_NO_UPDATE_THREAD_LIST . $messageidCond);
				
				// Bind galaxyuserid
				$getNoUpdateThreadListStmt->bindValue(":GALAXYUSERID", $this->_user->getGalaxyuserid(), PDO::PARAM_STR);
				
				// execute SQL
				try {
					$getNoUpdateThreadListStmt->execute();
				
				} catch(Exception $e) {
					ErrorLogger::write("MessageReadHistory selection failed.", $e);
					return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
				}
		
				$messageReadHistories = $getNoUpdateThreadListStmt->fetchAll(PDO::FETCH_ASSOC);
				$getNoUpdateThreadListStmt = null;
				
				foreach ($messageReadHistories as $tempHistory) {
					$noUpdateThreadList[] = $tempHistory["threadid"];
				}
			
			} else {
			}
			
			foreach ($threadList as $index => $tempThread) {
				if (!in_array($tempThread["threadid"], $messageHavingthreadList)) {
					unset($threadList[$index]);
					
				} else if (in_array($tempThread["threadid"], $noUpdateThreadList)) {
					$threadList[$index]["unreadmessageflag"] = false;
					
				} else {
					$threadList[$index]["unreadmessageflag"] = true;
					
				}
			}
			
		} else {
		}
		
		$result = OutputUtil::getSuccessOutput(array("thread" => array_merge($threadList)));
				
		return $result;
	}


	// function to get Rating
	public function getAverageRate() {
	
		DebugLogger::write("User average rate will be selected from DB.");
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
		}
	
		// Make prepared statement
		$stmt = $db_connection->prepare(GET_AVERAGE_RATE);
	
		// Bind galaxyuserid
		$stmt->bindValue(":GALAXYUSERID", $this->_user->getGalaxyuserid(), PDO::PARAM_INT);
	
		// execute SQL
		try {
			$stmt->execute();
	
		} catch(Exception $e) {
			ErrorLogger::write("User selection failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
	
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		$stmt = null;
		
		$result = null;
		
		if (count($users) == 1) {
			InfoLogger::write("User averate rate is selected successfully.");
			$result = OutputUtil::getSuccessOutput($users[0]);
		
		} else {
			WarnLogger::write("There is no user or duplicate user which has requested galaxyuserid.");
			$result = OutputUtil::getErrorOutput(array(APP_SYSTEM_ERROR_NONE));
		}
		 
		return $result;
	}
	
	
	// function to get unread messsage number
	public function getUnreadMessageNumber() {
	
		DebugLogger::write("Unread message number will be got from now.");
	
		// DB connect
		$db_connection = GalaxyDbConnector::getConnection();
		if ($db_connection == null) {
			ErrorLogger::write("DB connect failed.");
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
	
		} else {
		}
	
		
		// Make prepared statement
		$getMessageReadHistoriesStmt = $db_connection->prepare(GET_MESSAGE_READ_HISTORIES_OF_OPEN_THREADS);

		// Bind galaxyuserid
		$getMessageReadHistoriesStmt->bindValue(":GALAXYUSERID", $this->_user->getGalaxyuserid(), PDO::PARAM_STR);
		
		// execute SQL
		try {
			$getMessageReadHistoriesStmt->execute();
			 
		} catch(Exception $e) {
			ErrorLogger::write("MessageReadHistory selection failed.", $e);
			return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
		}
		 
		$messageReadHistories = $getMessageReadHistoriesStmt->fetchAll(PDO::FETCH_ASSOC);
		 
		$getMessageReadHistoriesStmt = null;
		
		$unreadMessageNumber = 0;
		
		if (count($messageReadHistories) > 0) {
			$lastReadMessageList = array();
			foreach ($messageReadHistories as $tempMessageReadHistory) {
				$lastReadMessageList[$tempMessageReadHistory["threadid"]] = $tempMessageReadHistory["lastreadmessage"];
			}
			$threadidList = array_keys($lastReadMessageList);
	
			$threadidCond = " (" . implode(", ", $threadidList) . ") ";
			
			// Make prepared statement
			$getMessagesStmt = $db_connection->prepare(GET_VARIOUS_THREADS_MESSAGES . $threadidCond);
	
			// execute SQL
			try {
				$getMessagesStmt->execute();
	
			} catch(Exception $e) {
				ErrorLogger::write("Message selection failed.", $e);
				return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
			}
	
			$messages = $getMessagesStmt->fetchAll(PDO::FETCH_ASSOC);
			$getMessagesStmt = null;
	
			foreach ($threadidList as $tempThreadid) {
				foreach ($messages as $tempMessage) {
		            if ($tempMessage["threadid"] == $tempThreadid && $tempMessage["messageid"] > $lastReadMessageList[$tempThreadid]) {
		            	$unreadMessageNumber++;
		            }
				}
			}
			
		} else {
		}
	
		$result = OutputUtil::getSuccessOutput(array("unreadmessagenumber" => strval($unreadMessageNumber)));
	
		return $result;
	}
}

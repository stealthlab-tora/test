<?php

define("LOG_DIRECTORY", "/home/dotcloud/logs");

// class to write log easily
class Logger
{
	// method to write log
    public static function write($log) {    	
    	$filename = basename($_SERVER['SCRIPT_NAME'], ".php");
    	$modifiedLog = date("Y-m-d H:i:s") . ", " . $log;
    	writeLog($filename, $modifiedLog);
    } 
}


// function to write log
function writeLog($filename, $log) {
	
	if (!file_exists(LOG_DIRECTORY)) {
		mkdir(LOG_DIRECTORY, 0755, true);
	}
	
	// make log file name
	$logFilename = LOG_DIRECTORY . "/" . $filename . ".log";
	
	// file open
	$logFile = fopen($logFilename, "a");
	
	// write log
	fwrite($logFile, $log . "\n");
	
	// file close
	fclose($logFile);
}


// function to write access log
function writeAccessLog() {
	// make logArray array
	$logArray = array();
	
	// date
	$logArray[] = date("Y-m-d H:i:s");
	
	// URI
	$logArray[] = $_SERVER["REQUEST_URI"];

	// request method
	$logArray[] = $_SERVER["REQUEST_METHOD"];
	
	// referer
	$logArray[] = $_SERVER["HTTP_REFERER"];

	// IP address
	$logArray[] = $_SERVER["REMOTE_ADDR"];

	// host name
	$logArray[] = $_SERVER["REMOTE_ADDR"];

	// browser
	$logArray[] = $_SERVER["HTTP_USER_AGENT"];

	$log = implode (", ", $logArray);
	
	writeLog("access", $log);
}


// call writeAccessLog function
writeAccessLog();
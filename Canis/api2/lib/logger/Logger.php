<?php

define("LOG_DIRECTORY", "/home/dotcloud/logs");

// class to write log easily
class Logger
{
    // method to write log
    public static function write($log, $exception = null) {        
        $filename = basename($_SERVER['SCRIPT_NAME'], ".php");
        $modifiedLog = date("Y-m-d H:i:s") . ", ";
        if ($exception != null) {
        	$modifiedLog = $modifiedLog . "Exception has thrown.";
        	$modifiedLog = $modifiedLog . "\n" . $exception->getMessage();
        	$modifiedLog = $modifiedLog . "\n" . $exception->getTraceAsString() . "\n";
        }
        $modifiedLog = $modifiedLog . $log;
        writeLog($filename, $modifiedLog);
    } 
}


// function to write log
function writeLog($filename, $log) {
    
    try {
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
        
    } catch (Exception $e) {
    	// nothing is done because this is just a logger.
    }
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
<?php

/**
* The class to write a log at Debug level.
*
* [method]
* + write : The method to write log considering log level and adding error message/stack trace of an exception.
* - writeLog : The method to write a log.
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


// class to write log easily
class Logger
{
    // method to write log
    public static function write($log, $level = null, $exception = null) {
    	
    	$filename = null;
    	
    	if (isset($_POST["action"])) {
            $filename = "[" . basename($_SERVER['SCRIPT_NAME'], ".php") . "]" . $_POST["action"];
    	} else {
    		$filename = basename($_SERVER['SCRIPT_NAME'], ".php");
    	}
    	
        $modifiedLog = date("Y-m-d H:i:s") . ", ";
        
        if ($level != null) {
            $modifiedLog .= '[' . $level . ']';
        }
        
        $modifiedLog .= $log;
        
        if ($exception != null) {
            $modifiedLog .= "Exception has thrown.";
            $modifiedLog .= "\n" . $exception->getMessage();
            $modifiedLog .= "\n" . $exception->getTraceAsString() . "\n";
        
        } else {
            
        }
        
        self::_writeLog($filename, $modifiedLog);
    }
    
    
    // function to write log
    private static function _writeLog($filename, $log) {
    
        try {
            if (!file_exists(LOG_DIRECTORY)) {
                mkdir(LOG_DIRECTORY, 0755, true);
    
            } else {
                
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
}

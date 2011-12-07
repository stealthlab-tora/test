<?php

/**
* The class to write a log at Error level.
*
* [method]
* + write : The method to write a log at Error level.
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


// class to write log easily
class ErrorLogger extends Logger
{
    // method to write log
    public static function write($log, $exception = null) {
        Logger::write($log, 'ERROR', $exception);
    }
}

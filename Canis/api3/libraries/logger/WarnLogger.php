<?php

/**
* The class to write a log at Warn level.
*
* [method]
* + write : The method to write a log at Warn level.
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


// class to write log easily
class WarnLogger extends Logger
{
    // method to write log
    public static function write($log) {
        Logger::write($log, 'WARN');
    }
}

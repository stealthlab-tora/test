<?php

/**
* The class to write a log at Fatal level.
*
* [method]
* + write : The method to write a log at Fatal level.
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


// class to write log easily
class FatalLogger extends Logger
{
    // method to write log
    public static function write($log, $exception = null) {
        Logger::write($log, 'FATAL', $exception);
    }
}

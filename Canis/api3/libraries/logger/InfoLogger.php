<?php

/**
* The class to write a log at Info level.
*
* [method]
* + write : The method to write a log at Info level.
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


// class to write log easily
class InfoLogger extends Logger
{
    // method to write log
    public static function write($log) {
        Logger::write($log, 'INFO');
    }
}

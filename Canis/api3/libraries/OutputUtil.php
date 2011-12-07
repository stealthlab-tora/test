<?php

/**
* The class to get API output format
*
* [method]
* + getErrorOutput : The method to get API output format in case of process error
* + getSuccessOutput : The method to get API output format in case of process success
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class OutputUtil
{
    public static function getErrorOutput($errorCodes = null)
    {
        $result = array();
        $result["status"] = "false";
        if ($errorCodes != null) {
            if (in_array(APP_SYSTEM_ERROR_NONE, $errorCodes)) {
                $result["error"] = array(APP_SYSTEM_ERROR_NONE);
                    
            } else if (in_array(SRV_SYSTEM_ERROR_NONE, $errorCodes)) {
                $result["error"] = array(SRV_SYSTEM_ERROR_NONE);
                
            } else if (in_array(UNKNOWN_SYSTEM_ERROR_NONE, $errorCodes)) {
                $result["error"] = array(UNKNOWN_SYSTEM_ERROR_NONE);
                
            } else {
                $result["error"] = $errorCodes;
            }
        }
        
        return $result;
    }
    
    
    public static function getSuccessOutput($values = null)
    {
        $result = array();
        $result["status"] = "true";
        if ($values != null) {
            foreach ($values as $key => $value) {
                if ($key !== "status") {
                    $result[$key] = $value;

                } else {
                    FatalLogger::write("There is a key which name is \"status\".");
                    throw new Exception("There is a key which name is \"status\".");
                }
            }
        }
        
        return $result;    
    }
}

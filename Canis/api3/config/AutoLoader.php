<?php

/**
* The class to load libraries automatically.
*
* [method]
* - loader : The method which works when an undefined class is called. 
*  
*/


class Autoloader {
    public function __construct() {
        $include_paths = array();
        $include_paths[] = get_include_path();
        $include_paths[] = $_SERVER["DOCUMENT_ROOT"] . "/models/";
        $include_paths[] = $_SERVER["DOCUMENT_ROOT"] . "/libraries/";
        $include_paths[] = $_SERVER["DOCUMENT_ROOT"] . "/libraries/logger/";
        set_include_path(implode(PATH_SEPARATOR, $include_paths));
        
        spl_autoload_register(array($this, 'loader'));
    }
    
    private function loader($className) {
        if ((strpos($className, 'Microsoft') !== false)) {
            return false;
        }
        
         require_once $className . ".php";
    }
}

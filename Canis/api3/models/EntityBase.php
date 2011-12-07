<?php

/**
* The class which define the base of models
*
* [method]
* + setParams : The method to set values to the corresponging field
* + setParam : The method to set a value to the corresponging field
* + getParam : The method to get a data from the field which is corresponding to value
*
*/

class EntityBase
{
    public function __construct($param = null) 
    {
    	if ($param != null) {
            $this->setParams($param);
    	}
    }
    
    public function setParams($param)
    {
        if (is_array($param)) {
            foreach ($param as $columnName => $columnValue) {
                $this->setParam($columnName, $columnValue);
            }
        }
    }
    
    public function setParam($key, $value)
    {
        $methodName = str_replace("_", " ", $key);
        $methodName = str_replace(" ", "", $methodName);
        $methodName = "set" . ucwords($methodName);
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        }
    }
    
    public function getParam($key)
    {
        $methodName = str_replace("_", " ", $key);
        $methodName = str_replace(" ", "", $methodName);
        $methodName = "get" . ucwords($methodName);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }
    }
}

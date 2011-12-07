<?php

/**
* The model to store item search information
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ItemSearchInfo extends EntityBase
{
    private $_galaxyuserid     = null;
    private $_value            = null;
    private $_type             = null;
    private $_order            = null;
    private $_latitude         = null;
    private $_longtitude       = null;
    
    
    public function getGalaxyuserid()
    {
        return $this->_galaxyuserid;
    }
    
    public function setGalaxyuserid($value)
    {
        $this->_galaxyuserid = $value;
    }
    
    public function getValue()
    {
        return $this->_value;
    }
    
    public function setValue($value)
    {
        $this->_value = $value;
    }
    
    public function getType()
    {
        return $this->_type;
    }
    
    public function setType($value)
    {
        $this->_type = $value;
    }
    
    public function getOrder()
    {
        return $this->_order;
    }
    
    public function setOrder($value)
    {
        $this->_order = $value;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }
    
    public function setLatitude($value)
    {
        $this->_latitude = $value;
    }
    
    public function getLongtitude()
    {
        return $this->_longtitude;
    }
    
    public function setLongtitude($value)
    {
        $this->_longtitude = $value;
    }
}
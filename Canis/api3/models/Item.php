<?php

/**
* The model to store items table data and inputted data via form for item
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Item extends EntityBase
{
    private $_itemid           = null;
    private $_galaxyuserid     = null;
    private $_itemname         = null;
    private $_description      = null;
    private $_currency         = null;
    private $_price            = null;
    private $_locationtype     = null;
    private $_zipcode          = null;
    private $_state            = null;
    private $_city             = null;
    private $_latitude         = null;
    private $_longtitude       = null;
    private $_itemstatus           = null;
    private $_updatedtime = null;

    
    public function getItemid()
    {
        return $this->_itemid;
    }
    
    public function setItemid($value)
    {
        $this->_itemid = $value;
    }
    
    public function getGalaxyuserid()
    {
        return $this->_galaxyuserid;
    }

    public function setGalaxyuserid($value)
    {
        $this->_galaxyuserid = $value;
    }

    public function getItemname()
    {
        return $this->_itemname;
    }
    
    public function setItemname($value)
    {
        $this->_itemname = $value;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setDescription($value)
    {
        $this->_description = $value;
    }

    public function getCurrency()
    {
        return $this->_currency;
    }
    
    public function setCurrency($value)
    {
        $this->_currency = $value;
    }
    
    public function getPrice()
    {
        return $this->_price;
    }
    
    public function setPrice($value)
    {
        $this->_price = $value;
    }
    
    public function getLocationtype()
    {
        return $this->_locationtype;
    }
    
    public function setLocationtype($value)
    {
        $this->_locationtype = $value;
    }
        
    public function getZipcode()
    {
        return $this->_zipcode;
    }
    
    public function setZipcode($value)
    {
        $this->_zipcode = $value;
    }

    public function getState()
    {
        return $this->_state;
    }
    
    public function setState($value)
    {
        $this->_state = $value;
    }
    
    public function getCity()
    {
        return $this->_city;
    }
    
    public function setCity($value)
    {
        $this->_city = $value;
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

    public function getItemstatus()
    {
        return $this->_itemstatus;
    }
    
    public function setItemstatus($value)
    {
        $this->_itemstatus = $value;
    }

    public function getUpdatedtime()
    {
        return $this->_updatedtime;
    }
    
    public function setUpdatedtime($value)
    {
        $this->_updatedtime = $value;
    }
}


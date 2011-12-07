<?php

/**
* The model to store rates table data and sent data for rate
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Rate extends EntityBase
{
    private $_rateid       = null;
    private $_galaxyuserid = null;
    private $_rate         = null;
    private $_rater         = null;

    
    public function getRateid()
    {
        return $this->_rateid;
    }
    
    public function setRateid($value)
    {
        $this->_rateid = $value;
    }

    public function getGalaxyuserid()
    {
        return $this->_galaxyuserid;
    }
    
    public function setGalaxyuserid($value)
    {
        $this->_galaxyuserid = $value;
    }

    public function getRate()
    {
        return $this->_rate;
    }
    
    public function setRate($value)
    {
        $this->_rate = $value;
    }
    
    public function getRater()
    {
    return $this->_rater;
    }
    
    public function setRater($value)
    {
    $this->_rater = $value;
    }
    
}


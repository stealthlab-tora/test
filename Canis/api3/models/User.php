<?php

/**
* The model to store users table data and inputted data via form for user
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class User extends EntityBase
{
    private $_galaxyuserid        = null;
    private $_firstname           = null;
    private $_lastname            = null;
    private $_email               = null;
    private $_password            = null;
    private $_password2           = null;
    private $_phonenumber         = null;
    private $_zipcode             = null;
    private $_country             = null;
    private $_state               = null;
    private $_city                = null;
    private $_street              = null;
    private $_latitude            = null;
    private $_longtitude          = null;
    private $_pcrequestcode       = null;
    private $_pcrequesttime       = null;
    
    public function getGalaxyuserid()
    {
        return $this->_galaxyuserid;
    }

    public function setGalaxyuserid($value)
    {
        $this->_galaxyuserid = $value;
    }
    
    public function getFirstname()
    {
        return $this->_firstname;
    }
    
    public function setFirstname($value)
    {
        $this->_firstname = $value;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }
    
    public function setLastname($value)
    {
        $this->_lastname = $value;
    }
    
    public function getEmail()
    {
        return $this->_email;
    }
    
    public function setEmail($value)
    {
        $this->_email = $value;
    }

    public function getPassword()
    {
        return $this->_password;
    }
    
    public function setPassword($value)
    {
        $this->_password = $value;
    }
    
    public function getPassword2()
    {
        return $this->_password2;
    }
    
    public function setPassword2($value)
    {
        $this->_password2 = $value;
    }

    public function getPhonenumber()
    {
        return $this->_phonenumber;
    }
    
    public function setPhonenumber($value)
    {
        $this->_phonenumber = $value;
    }
    
    public function getZipcode()
    {
        return $this->_zipcode;
    }
    
    public function setZipcode($value)
    {
        $this->_zipcode = $value;
    }
    
    public function getCountry()
    {
        return $this->_country;
    }
    
    public function setCountry($value)
    {
        $this->_country = $value;
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

    public function getStreet()
    {
        return $this->_street;
    }
    
    public function setStreet($value)
    {
        $this->_street = $value;
    }
    
    public function getLatitude()
    {
        return $this->_latitude;
    }
    
    public function setLaitude($value)
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

    public function getPcrequestcode()
    {
    	return $this->_pcrequestcode;
    }
    
    public function setPcrequestcode($value)
    {
    	$this->_pcrequestcode = $value;
    }

    public function getPcrequesttime()
    {
    	return $this->_pcrequesttime;
    }
    
    public function setPcrequesttime($value)
    {
    	$this->_pcrequesttime = $value;
    }
    
}


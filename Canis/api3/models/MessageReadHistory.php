<?php

/**
* The model to store messagereadhistorys table data and inputted data  for messagereadhistory
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class MessageReadHistory extends EntityBase
{
    private $_threadid             = null;
    private $_galaxyuserid         = null;
    private $_lastreadmessage      = null;
    private $_messagereadhistoryid = null;

    
    public function getThreadid()
    {
        return $this->_threadid;
    }
    
    public function setThreadid($value)
    {
        $this->_threadid = $value;
    }

    public function getGalaxyuserid()
    {
        return $this->_galaxyuserid;
    }
    
    public function setGalaxyuserid($value)
    {
        $this->_galaxyuserid = $value;
    }

    public function getLastreadmessage()
    {
        return $this->_lastreadmessage;
    }
    
    public function setLastreadmessage($value)
    {
        $this->_lastreadmessage = $value;
    }

    public function getMessagereadhistoryid()
    {
        return $this->_messagereadhistoryid;
    }
    
    public function setMessagereadhistoryid($value)
    {
        $this->_messagereadhistoryid = $value;
    }
}


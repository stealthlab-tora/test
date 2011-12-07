<?php

/**
* The model to store threads table data and inputted data via form for message
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Thread extends EntityBase
{
    private $_threadid     = null;
    private $_itemid       = null;
    private $_buyer        = null;
    private $_channelname  = null;
    private $_threadstatus = null;

    
    public function getThreadid()
    {
        return $this->_threadid;
    }
    
    public function setThreadid($value)
    {
        $this->_threadid = $value;
    }
    
    public function getItemid() 
    {
        return $this->_itemid;
    }
    
    public function setItemid($value) 
    {
        $this->_itemid = $value;
    }

    public function getBuyer() 
    {
        return $this->_buyer;
    }
    
    public function setBuyer($value)
    {
        $this->_buyer = $value;
    }
    
    public function getChannelname()
    {
        return $this->_channelname;
    }
    
    public function setChannelname($value)
    {
        $this->_channelname = $value;
    }

    public function getThreadstatus()
    {
    	return $this->_threadstatus;
    }
    
    public function setThreadstatus($value)
    {
    	$this->_threadstatus = $value;
    }
}

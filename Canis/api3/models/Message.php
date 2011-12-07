<?php

/**
* The model to store messages table data and inputted data via form for message
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Message extends EntityBase
{
    private $_threadid    = null;
    private $_messageid   = null;
    private $_sender        = null;
    private $_receiver          = null;
    private $_message     = null;

    
    public function getThreadid()
    {
        return $this->_threadid;
    }
    
    public function setThreadid($value)
    {
        $this->_threadid = $value;
    }

    public function getMessageid()
    {
        return $this->_messageid;
    }
    
    public function setMessageid($value)
    {
        $this->_messageid = $value;
    }

    public function getSender()
    {
        return $this->_sender;
    }
    
    public function setSender($value)
    {
        $this->_sender = $value;
    }

    public function getReceiver()
    {
        return $this->_receiver;
    }
    
    public function setReceiver($value)
    {
        $this->_receiver = $value;
    }

    public function getMessage()
    {
        return $this->_message;
    }
    
    public function setMessage($value)
    {
        $this->_message = $value;
    }
}


<?php

/**
* The model to store images table data and inputted data via form for image
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Image extends EntityBase
{
    private $_imageid   = null;
    private $_itemid    = null;
    private $_imagetype = null;
    private $_imageurl  = null;
    private $_size      = null;
    private $_error     = null;
    private $_name      = null;
    private $_tmp_name  = null;
    private $_filename  = null;

    
    public function getImageid()
    {
        return $this->_imageid;
    }
    
    public function setImageid($value)
    {
        $this->_imageid = $value;
    }
    
    public function getItemid() 
    {
        return $this->_itemid;
    }
    
    public function setItemid($value) 
    {
        $this->_itemid = $value;
    }

    public function getImagetype() 
    {
        return $this->_imagetype;
    }
    
    public function setImagetype($value)
    {
        $this->_imagetype = $value;
    }
    
    public function getImageurl()
    {
        return $this->_imageurl;
    }
    
    public function setImageurl($value)
    {
        $this->_imageurl = $value;
    }

    public function getSize()
    {
        return $this->_size;
    }
    
    public function setSize($value)
    {
        $this->_size = $value;
    }

    public function getError()
    {
        return $this->_error;
    }
    
    public function setError($value)
    {
        $this->_error = $value;
    }

    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($value)
    {
        $this->_name = $value;
    }
    
    public function getTmpname()
    {
        return $this->_tmp_name;
    }
    
    public function setTmpname($value)
    {
        $this->_tmp_name = $value;
    }

    public function getFilename()
    {
        return $this->_filename;
    }
    
    public function setFilename($value)
    {
        $this->_filename = $value;
    }
}

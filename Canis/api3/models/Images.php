<?php

/**
* The model which has 2 images(thumbnail and image)
*
* [method]
* All methods are getter/setter of properties
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class Images extends EntityBase
{
    private $_image     = null;
    private $_thumbnail = null;

    public function getImage()
    {
        return $this->_image;
    }
    
    public function setImage($value)
    {
        $this->_setImageBase("_image", $value, "IMAGE");
    }
    
    public function getThumbnail() 
    {
        return $this->_thumbnail;
    }
    
    public function setThumbnail($value) 
    {
        $this->_setImageBase("_thumbnail", $value, "THUMBNAIL");
    }
    
    private function _setImageBase($propertyName, $value, $imageType)
    {
        if (is_object($value) && get_class($value) == "Image") {
            $this->$propertyName = $value;
        
        } else {
            $image = new Image($value);
            $image->setImagetype($imageType);
            $this->$propertyName = $image;
        }
    }
}

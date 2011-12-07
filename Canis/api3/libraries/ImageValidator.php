<?php

/**
* The class to validate image information
*
* [method]
* + validateImage : The method to validate image information
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ImageValidator
{
    private $_image = null;
    private $_thumbnail = null;

    public function __construct($images)
    {
        $this->_image     = $images->getImage();
        $this->_thumbnail = $images->getThumbnail();
    }
    

    public function validateImage()
    {
        DebugLogger::write("Images will be validated from now.");
        
        // Validate thumbnail file
        if (!is_null($this->_thumbnail) && $this->_thumbnail->getSize() != 0) {
        
            if ($this->_thumbnail->getError() != 0) {
                WarnLogger::write("thumbnail upload failed.");
                return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
            } else {
            }
        
        } else {
        }
        
        
        // Validate image file
        if (!is_null($this->_image) && $this->_image->getSize() != 0) {
            if ($this->_image->getError() != 0) {
                WarnLogger::write("Image upload failed.");
                return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        
            } else {
            }
            
        } else {
        }
        
        DebugLogger::write("Images are valid.");
        
        return OutputUtil::getSuccessOutput();
    }
}

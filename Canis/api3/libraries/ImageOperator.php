<?php

/**
* The class to operate image
*
* [method]
* + saveImageToServer : The method to save images which is sent by user to the server
* + saveImageToAzure : The method to save images in the server to WindowsAzure
* + registerImage : The method to register image information to DB
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class ImageOperator
{
    private $_image = null;
    private $_thumbnail = null;

    public function __construct($images)
    {
        $this->_image     = $images->getImage();
        $this->_thumbnail = $images->getThumbnail();
    }


    // function to upload galaxy images
    public function saveImageToServer($itemId) {
    
        DebugLogger::write("Uploaded files will be saved to temporary directory from now.");
    
        // Make ids of uploaded files
        $thumbnailId = $itemId . "s";
        $imageId     = $itemId;
    
        // Make names of uploaded files
        $thumbnailFilename = $thumbnailId . ".jpg";
        $imageFilename     = $imageId . ".jpg";
    
        // Save files in local
    
        // thumbnail
        $thumbnailMoveResult = move_uploaded_file($this->_thumbnail->getTmpname(), FILE_UPLOAD_PATH . $thumbnailFilename);
        if ($thumbnailMoveResult) {
            InfoLogger::write("Thumbnail(" . $this->_thumbnail->getName() . ") is saved as " . $thumbnailFilename . "locally.");
    
        } else {
            ErrorLogger::write("Thumbnail(" . $this->_thumbnail->getName() . ") is not saved locally.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
         
        // image
        $imageMoveResult = move_uploaded_file($this->_image->getTmpname(), FILE_UPLOAD_PATH . $imageFilename);
        if ($imageMoveResult) {
            InfoLogger::write("Image(" . $this->_image->getName() . ") is saved as " . $imageFilename . "locally.");
    
        } else {
            ErrorLogger::write("Image(" . $this->_image->getName() . ") is not saved locally.");
            unlink(FILE_UPLOAD_PATH . $thumbnailFilename);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
            return $result;
        }
    
        DebugLogger::write("Uploaded files save succeeded.");

        $this->_thumbnail->setItemid($itemId);
        $this->_image->setItemid($itemId);
        
        $this->_thumbnail->setFilename($thumbnailFilename);
        $this->_image->setFilename($imageFilename);
        
        $this->_thumbnail->setImageid($thumbnailId);
        $this->_image->setImageid($imageId);
        
        return OutputUtil::getSuccessOutput();
    }



    // function to save galaxy images to Azure
    public function saveImageToAzure() {
    
        DebugLogger::write("Image files will be saved to Windows Azure from now.");
    
        // connection setting
        $host          = "blob.core.windows.net";
        $accountName   = "rakutensfadc";
        $accountKey    = "l+a4G6TsojYT+6HHHzDThyvIasaldhWZkY7g6QmCOxMnmS2GMmT74wpgpSvsnaNMuyXUYV/xXK9GuHitABbtHA==";
        $storageBlob   = new Microsoft_WindowsAzure_Storage_Blob($host, $accountName, $accountKey);
        $containerName = "galaxy";
    
        // check dirctory
        if (!$storageBlob->containerExists($containerName)) {
            $storageBlob->createContainer($containerName);
    
        } else {    
        }
    
        // Push blob
        $storageBlob->setContainerAcl($containerName, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC);
    
        // thumbnail
        $thumbnailBlobProperties = $storageBlob->putBlob($containerName, $this->_thumbnail->getFilename(), FILE_UPLOAD_PATH . $this->_thumbnail->getFilename());
        $thumbnailUrl = $thumbnailBlobProperties->__get("url");
        if (CheckUtil::checkNotEmpty($thumbnailUrl)) {
            InfoLogger::write("Thumbnail(" . $this->_thumbnail->getFilename() . ") is saved to Widows Azure.");
    
        } else {
            ErrorLogger::write("Thumbnail(" . $this->_thumbnail->getFilename() . ") is not saved to Windows Azure.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        // image
        $imageBlobProperties    = $storageBlob->putBlob($containerName, $this->_image->getFilename(), FILE_UPLOAD_PATH . $this->_image->getFilename());
        $imageUrl = $imageBlobProperties->__get("url");
        if (CheckUtil::checkNotEmpty($imageUrl)) {
            InfoLogger::write("Image(" . $this->_image->getFilename() . ") is saved to Widows Azure.");
    
        } else {
            ErrorLogger::write("Image(" . $this->_image->getFilename() . ") is not saved to Windows Azure.");
            $storageBlob->deleteBlob($containerName, $this->_thumbnail->getFilename());
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        $this->_thumbnail->setImageurl($thumbnailUrl);
        $this->_image->setImageurl($imageUrl);

        return OutputUtil::getSuccessOutput();
    }



    // function to save image url to DB
    public function registerImage() {
    
        DebugLogger::write("Image information will be saved to DB from now.");
            
        // get DB connection
        $db_connection   = GalaxyDbConnector::getConnection();
        if ($db_connection == null) {
            ErrorLogger::write("DB connect failed.");
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
    
        } else {
        }
    
        // Register thumbnail
    
        // Prepare SQL statement to update thumbnail image url
        $thumbnail_stmt = $db_connection->prepare(REGISTER_IMAGE_DML);
        $thumbnail_stmt->bindValue(":IMAGEID",   $this->_thumbnail->getImageid(),   PDO::PARAM_STR);
        $thumbnail_stmt->bindValue(":ITEMID",    $this->_thumbnail->getItemid(),    PDO::PARAM_STR);
        $thumbnail_stmt->bindValue(":IMAGETYPE", $this->_thumbnail->getImagetype(), PDO::PARAM_STR);
        $thumbnail_stmt->bindValue(":IMAGEURL",  $this->_thumbnail->getImageurl(),  PDO::PARAM_STR);
    
    
        // Execute SQL
        try {
            $thumbnail_stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Thumbnail image regist operation failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
        // Close prepared statement
        $thumbnail_stmt = null;
    
    
        // Register image
    
        // Prepare SQL statement to update image url
        $image_stmt = $db_connection->prepare(REGISTER_IMAGE_DML);
        $image_stmt->bindValue(":IMAGEID",   $this->_image->getImageid(),   PDO::PARAM_STR);
        $image_stmt->bindValue(":ITEMID",    $this->_image->getItemid(),    PDO::PARAM_STR);
        $image_stmt->bindValue(":IMAGETYPE", $this->_image->getImagetype(), PDO::PARAM_STR);
        $image_stmt->bindValue(":IMAGEURL",  $this->_image->getImageurl(),  PDO::PARAM_STR);
                    
        // Execute SQL
        try {
            $image_stmt->execute();
    
        } catch(Exception $e) {
            ErrorLogger::write("Image regist operation failed.", $e);
            return OutputUtil::getErrorOutput(array(SRV_SYSTEM_ERROR_NONE));
        }
    
    
        // Close prepared statement
        $image_stmt = null;
    
        DebugLogger::write("Image information saved to DB successfully.");
    
        return OutputUtil::getSuccessOutput();
    }
}
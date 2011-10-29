<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to save image url to DB
function registImage($itemId, $thumbnailId, $thumbnailUrl, $imageId, $imageUrl) {
	
	Logger::write("Image information will be saved to DB from now.");

	$result = array();
	 	
    // get DB connection
    $db_connection   = galaxyDbConnector::getConnection();
    if ($db_connection == null) {
    	Logger::write("DB connect failed.");
    	$result["status"] = "false";
    	$result["error"] = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }
    
    // Register thumbnail
    
    // Prepare SQL statement to update thumbnail image url
    $thumbnail_stmt = $db_connection->prepare(REGIST_IMAGE_QUERY);
    $thumbnail_stmt->bindValue(":IMAGEID",   $thumbnailId,  PDO::PARAM_STR);
    $thumbnail_stmt->bindValue(":ITEMID",    $itemId,       PDO::PARAM_STR);
    $thumbnail_stmt->bindValue(":IMAGETYPE", "THUMBNAIL",   PDO::PARAM_STR);
    $thumbnail_stmt->bindValue(":IMAGEURL",  $thumbnailUrl, PDO::PARAM_STR);

    
    // Execute SQL
    try {
        $thumbnail_stmt->execute();
    
    } catch(Exception $e) {
        Logger::write("Thumbnail image regist operation failed.", $e);
        $result["status"] = "false";
        $result["error"] = array(SRV_SYSTEMERROR_NONE);
        return $result;
    }

    // Close prepared statement
    $thumbnail_stmt = null;


    // Register image
    
    // Prepare SQL statement to update image url
    $image_stmt = $db_connection->prepare(REGIST_IMAGE_QUERY);
    $image_stmt->bindValue(":IMAGEID",   $imageId,  PDO::PARAM_STR);
    $image_stmt->bindValue(":ITEMID",    $itemId,   PDO::PARAM_STR);
    $image_stmt->bindValue(":IMAGETYPE", "IMAGE",   PDO::PARAM_STR);
    $image_stmt->bindValue(":IMAGEURL",  $imageUrl, PDO::PARAM_STR);
     
    // Execute SQL
    try {
        $image_stmt->execute();
         
    } catch(Exception $e) {
        Logger::write("Image regist operation failed.", $e);
        $result["status"] = "false";
        $result["error"] = array(SRV_SYSTEMERROR_NONE);
        return $result;
    }

    
    // Close prepared statement
    $image_stmt = null;

    Logger::write("Image information saved to DB successfully.");
    
    $result["status"] = "true";
    return $result;
}
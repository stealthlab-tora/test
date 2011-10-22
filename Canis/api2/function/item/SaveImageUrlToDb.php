<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";

// function to save image url to DB
function saveImageUrlToDb($thumbnailId, $thumbnailUrl, $imageId, $imageUrl) {
    // get DB connection
    $db_connection   = galaxyDbConnector::getConnection();
    $result = true;
      
    // begin transaction
    $db_connection->beginTransaction();
   
    // update thumbnail
    
    // prepare SQL statement to update thumbnail image url
    $thumbnail_stmt = $db_connection->prepare(THUMBNAILURL_SAVE_QUERY);
    $thumbnail_stmt->bindValue(":THUMBNAIL_URL", $thumbnailUrl, PDO::PARAM_STR);
    $thumbnail_stmt->bindValue(":THUMBNAIL_ID", $thumbnailId, PDO::PARAM_STR);
    
    // execute SQL
    try {
        $thumbnail_stmt->execute();
    
    } catch(Exception $e) {
        Logger::write("Thumbnail image update operation failed.");
        $db_connection->rollback();
        return false;
    }

    // close prepared statement
    $thumbnail_stmt = null;


    // update image
    
    // prepare SQL statement to update image url
    $image_stmt = $db_connection->prepare(IMAGEURL_SAVE_QUERY);
    $image_stmt->bindValue(":IMAGE_URL", $imageUrl, PDO::PARAM_STR);
    $image_stmt->bindValue(":IMAGE_ID", $imageId, PDO::PARAM_STR);
     
    // execute SQL
    try {
        $image_stmt->execute();
         
    } catch(Exception $e) {
        Logger::write("image update operation failed.");
        $db_connection->rollback();
        return false;
    }

    
    // close prepared statement
    $image_stmt = null;

    
    // commit image
    $db_connection->commit();
    
    return true;
}
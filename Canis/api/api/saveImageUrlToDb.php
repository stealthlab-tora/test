<?php

// include php filess
require_once("../lib/DB/galaxyDbConnector.php");
require_once("../lib/Logger/logger.php");

// function to save image url to DB
function saveImageUrlToDb($thumbnailId, $thumbnailUrl, $imageId, $imageUrl) {
    // get DB connection
    $db_connection   = galaxyDbConnector::getConnection();
    $result = true;
    
    // get logger
    $log = Logger::getLogger('saveImageUrlToDb');
    
    // begin transaction
    $db_connection->beginTransaction();
    

    // update thumbnail
    
    // create query to update thumbnail image url
    // TODO : constant
    $thumbnail_query_array   = array();
    $thumbnail_query_array[] = "update";
    $thumbnail_query_array[] = "images";
    $thumbnail_query_array[] = "set";
    $thumbnail_query_array[] = "imageurl = :THUMBNAIL_URL";
    $thumbnail_query_array[] = "where";
    $thumbnail_query_array[] = "imageid = :THUMBNAIL_ID";
    
    $thumbnail_query = implode(" ", array_map("trim", $thumbnail_query_array));
    
    // prepare SQL statement
    $thumbnail_stmt = $db_connection->prepare($thumbnail_query);
    $thumbnail_stmt->bindValue(":THUMBNAIL_URL", $thumbnailUrl, PDO::PARAM_STR);
    $thumbnail_stmt->bindValue(":THUMBNAIL_ID", $thumbnailId, PDO::PARAM_STR);
    
    // execute SQL
    try {
        $thumbnail_stmt->execute();
    
    } catch(Exception $e) {
        $log->error("Thumbnail image update operation failed.");
        $db_connection->rollback();
        return false;
    }

    // close prepared statement
    $thumbnail_stmt = null;


    // update image
    
    // create query to update image url
    $image_query_array   = array();
    $image_query_array[] = "update";
    $image_query_array[] = "images";
    $image_query_array[] = "set";
    $image_query_array[] = "imageurl = :IMAGE_URL";
    $image_query_array[] = "where";
    $image_query_array[] = "imageid = :IMAGE_ID";
     
    $image_query = implode(" ", array_map("trim", $image_query_array));
     
    // prepare SQL statement
    $image_stmt = $db_connection->prepare($image_query);
    $image_stmt->bindValue(":IMAGE_URL", $imageUrl, PDO::PARAM_STR);
    $image_stmt->bindValue(":IMAGE_ID", $imageId, PDO::PARAM_STR);
     
    // execute SQL
    try {
        $image_stmt->execute();
         
    } catch(Exception $e) {
        $log->error("image update operation failed.");
        $db_connection->rollback();
        return false;
    }

    
    // close prepared statement
    $image_stmt = null;

    
    // commit image
    $db_connection->commit();
    
    return true;
}
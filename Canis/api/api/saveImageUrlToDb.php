<?php

// include php filess
require_once("../lib/DB/galaxyDbConnector.php");
require_once("../lib/Logger/logger.php");

// function to save image url to DB
function saveImageUrlToDb($thumbnailId, $thumbnailUrl, $imageId, $imageUrl) {
    // get DB connection
    $conn   = galaxyDbConnector::getConnection();
    $result = true;
    
    // get logger
    $log = Logger::getLogger('saveImageUrlToDb');
    
    try {
    	// set auto commit off
    	$conn->autocommit(false);
    	
        // update thumbnail Url
        $thumbnailUpdateSql    = "update images set imageurl = '" . $thumbnailUrl .
                                         "' where imageid = '" . $thumbnailId . "'";
    
        // TODO : use prepared statement
        $thumbnailUpdateResult = $conn->query($thumbnailUpdateSql);
    
        // update image url
        $imageUpdateSql        = "update images set imageurl = '" . $imageUrl .
                                             "' where imageid = '" . $imageId . "'";
        
        // TODO : use prepared statement
        $imageUpdateResult      = $conn->query($imageUpdateSql);
        
        // judge update is succeeded or not
        if ($thumbnailUpdateResult == true && $imageUpdateResult == true) {
            $conn->commit();
            
        } else {
        	$log->error("Save imageURL failed.");
        	$conn->rollback();
        	$result = false;
        }

        // set auto commit on
        $conn->autocommit(true);
        
    } catch(Exception $e) {
        $log->error("Save imageURL failed : " . $e->getMessage());
        $result = false;
    }
    
    return $result;
}
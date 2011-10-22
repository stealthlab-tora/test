<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";


// function to upload galaxy images
function uploadImageToServer() {
    $db_connection = galaxyDbConnector::getConnection();
    
    // begin transaction
    $db_connection->beginTransaction();

    // thumbnail
    if ($_FILES["imageThumbnail"]["error"] > 0) {
        Logger::write("Error: " . $_FILES["imageThumbnail"]["error"]);
    }
    $thumbnailID = getUniqueID($db_connection, "thumbnail");
    
    $thumbnailFilename = $thumbnailID . ".jpg";

    // save thumbnail in local
    move_uploaded_file($_FILES["imageThumbnail"]["tmp_name"], FILE_UPLOAD_PATH . $thumbnailFilename);
    Logger::write($_FILES["imageThumbnail"]["name"] . "is saved as " . $thumbnailFilename . "locally");

  
    // big image
    if ($_FILES["image"]["error"] > 0) {
        Logger::write("Error: " . $_FILES["image"]["error"]);
    }

    $bigImageID = getUniqueID($db_connection, "large");
    $bigImageFilename = $bigImageID . ".jpg";


    // save thumbnail in local
    move_uploaded_file($_FILES["image"]["tmp_name"], FILE_UPLOAD_PATH . $bigImageFilename);
    Logger::write($_FILES["image"]["name"] . "is saved as " . $bigImageFilename . "locally");


    // commit if both IDs are not false
    if ($thumbnailID != false && $bigImageID != false) {
    	$db_connection->commit();
    } else {
    	$db_connection->rollback();
    	Logger::write("Getting imageIDs is failed.");
    	return false;
    }
    
    
    $imageIds = array("thumbnail" => $thumbnailID, "image" => $bigImageID);
    return $imageIds;
}


// funciton to save uploaded image to local file
// get unique ID for each uploaded image
// insert an entry to images table, let DB generate a unique ID.
function getUniqueID($db_connection, $type) {
    
    // prepare SQL statement
    $stmt = $db_connection->prepare(INSERT_IMAGE_RECORD_QUERY);
    $stmt->bindValue(":TYPE", $type, PDO::PARAM_STR);

    // execute SQL
    try {
        $stmt->execute();
    
    } catch(Exception $e) {
        $log->error("image upload operation failed : " . $e->getMessage());
        return false;
    }
    
    // get generated unique ID
    $uniqueID = $db_connection->lastInsertId();

    // close stmt
    $stmt = null;
    
    return $uniqueID;
}

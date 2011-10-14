<?php
// include php files
require_once("../lib/DB/galaxyDbConnector.php");
require_once("../lib/Logger/logger.php");
require_once("../constants/imageUploadConstants.php");


// function to upload galaxy images
function uploadImageToServer() {
    $db_connection = galaxyDbConnector::getConnection();
    
    // begin transaction
    $db_connection->beginTransaction();

    // TODO : not good way to use log4php, research it later
    $log = Logger::getLogger('uploadImageToServer');

    // thumbnail
    if ($_FILES["imageThumbnail"]["error"] > 0) {
        $log->error("Error: " . $_FILES["imageThumbnail"]["error"]);
    }
    $thumbnailID = getUniqueID($db_connection, "thumbnail");
    
    $thumbnailFilename = $thumbnailID . ".jpg";

    // save thumbnail in local
    move_uploaded_file($_FILES["imageThumbnail"]["tmp_name"], FILE_UPLOAD_PATH . $thumbnailFilename);
    $log->info($_FILES["imageThumbnail"]["name"] . "is saved as " . $thumbnailFilename . "locally");

  
    // big image
    if ($_FILES["image"]["error"] > 0) {
        $log->error("Error: " . $_FILES["image"]["error"]);
    }

    $bigImageID = getUniqueID($db_connection, "large");
    $bigImageFilename = $bigImageID . ".jpg";


    // save thumbnail in local
    move_uploaded_file($_FILES["image"]["tmp_name"], FILE_UPLOAD_PATH . $bigImageFilename);
    $log->info($_FILES["image"]["name"] . "is saved as " . $bigImageFilename . "locally");


    // commit if both IDs are not false
    if ($thumbnailID != false && $bigImageID != false) {
    	$db_connection->commit();
    } else {
    	$db_connection->rollback();
    	$log->info("Getting imageIDs is failed.");
    	return false;
    }
    
    
    $imageIds = array("thumbnail" => $thumbnailID, "image" => $bigImageID);
    return $imageIds;
}


// funciton to save uploaded image to local file
// get unique ID for each uploaded image
// insert an entry to images table, let DB generate a unique ID.
function getUniqueID($db_connection, $type) {
	$log = Logger::getLogger('getUniqueID');
	
    // create query
    $query_array   = array();
    $query_array[] = "insert into";
    $query_array[] = "    images";
    $query_array[] = "(";
    $query_array[] = "    itemid,";
    $query_array[] = "    imagetype";
    $query_array[] = ")";
    $query_array[] = "values";
    $query_array[] = "(";
    $query_array[] = "    1,";
    $query_array[] = "    :TYPE";
    $query_array[] = ")";
    
    $query = implode(" ", array_map("trim", $query_array));
    
    // prepare SQL statement
    $stmt = $db_connection->prepare($query);
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

<?php
// include php files
require_once("../lib/DB/galaxyDbConnector.php");
require_once("../lib/Logger/logger.php");
require_once("../constants/imageUploadConstants.php");


// function to upload galaxy images
function handleImageUpload() {
    $conn = galaxyDbConnector::getConnection();

    // set auto commit off
    $conn->autocommit(false);

    
    // TODO : not good way to use log4php, research it later
    $log = Logger::getLogger('uploadImageToServer');
    // thumbnail
    if ($_FILES["imageThumbnail"]["error"] > 0) {
        $log->error("Error: " . $_FILES["imageThumbnail"]["error"]);
    }
    $thumbnailID = getUniqueID($conn, "thumbnail");
    
    $thumbnailFilename = $thumbnailID . ".jpg";

    // save thumbnail in local
    move_uploaded_file($_FILES["imageThumbnail"]["tmp_name"], FILE_UPLOAD_PATH . $thumbnailFilename);

    $log->info($_FILES["imageThumbnail"]["name"] . "is saved as " . $thumbnailFilename . "locally");

  
    // big image
    if ($_FILES["image"]["error"] > 0) {
        $log->error("Error: " . $_FILES["image"]["error"]);
    }
    $bigImageID = getUniqueID($conn, "large");
    $bigImageFilename = $bigImageID . ".jpg";

    // save thumbnail in local
    move_uploaded_file($_FILES["image"]["tmp_name"], FILE_UPLOAD_PATH . $bigImageFilename);
    
    $log->info($_FILES["image"]["name"] . "is saved as " . $bigImageFilename . "locally");

    
    // commit
    $conn->commit();
    
    // set auto commit on
    $conn->autocommit(true);
    
    $imageIds = array("thumbnail" => $thumbnailID, "image" => $bigImageID);
    return $imageIds;
}


// funciton to save uploaded image to local file
// get unique ID for each uploaded image
// insert an entry to images table, let DB generate a unique ID.
function getUniqueID(&$conn, $type) {

	try {
	    // TODO : insert item to get item ID first
        $sql = "insert into images (itemid, imagetype) values (1, '$type')";
    
        // TODO : use prepared statement
        $conn->query($sql);
        
    // TODO : consider exception process more
	} catch (Exception $e) {
        $log->error("items table insert error : " . $e->getMessage());
        $conn->rollback();
	}
	
    // get generated unique ID
    $uniqueID = $conn->insert_id;

    return $uniqueID;
}

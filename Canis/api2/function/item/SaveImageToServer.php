<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to upload galaxy images
function saveImageToServer($files, $itemId) {

	Logger::write("Uploaded files will be saved to temporary directory from now.");
	
	$result = array();
	
    // Make ids of uploaded files
    $thumbnailId = $itemId . "s";
    $imageId     = $itemId;
    
    // Make names of uploaded files
    $thumbnailFilename = $thumbnailId . ".jpg";
    $imageFilename     = $imageId . ".jpg";

    // Save files in local
    
    // thumbnail
    $thumbnailMoveResult = move_uploaded_file($files["imageThumbnail"]["tmp_name"], FILE_UPLOAD_PATH . $thumbnailFilename);
    if ($thumbnailMoveResult) {
    	Logger::write("Thumbnail(" . $files["imageThumbnail"]["name"] . ") is saved as " . $thumbnailFilename . "locally.");

    } else {
    	Logger::write("Thumbnail(" . $files["imageThumbnail"]["name"] . ") is not saved locally.");
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }
    	
    // image
    $imageMoveResult = move_uploaded_file($files["image"]["tmp_name"], FILE_UPLOAD_PATH . $imageFilename);
    if ($imageMoveResult) {
        Logger::write("Image(" . $files["image"]["name"] . ") is saved as " . $imageFilename . "locally.");

    } else {
    	Logger::write("Image(" . $files["image"]["name"] . ") is not saved locally.");
    	unlink(FILE_UPLOAD_PATH . $thumbnailFilename);
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }

    Logger::write("Uploaded files save succeeded.");
  
    $result["status"] = "true";
    $result["filenames"] = array("thumbnail" => $thumbnailFilename, "image" => $imageFilename);
    $result["imageids"] = array("thumbnail" => $thumbnailId, "image" => $imageId);
    return $result;
}

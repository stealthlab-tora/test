<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/GalaxyDbConnector.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SaveImageToServer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SaveImageToAzure.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/RegistImage.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/RegistItem.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/ValidateItemInfo.php";

    
// validate item information
$validateResult = validateItemInfo($_POST, $_FILES);
if ($validateResult["status"] != "true") {
    die(json_encode($validateResult));
}

$result = array();

try {
    // get DB connection
    $db_connection   = galaxyDbConnector::getConnection();

    // begin transaction
    $db_connection->beginTransaction();
    
    // register item
    $registItemResult = registItem($_POST);
    if ($registItemResult["status"] != "true") {
        $db_connection->rollback();
        die(json_encode($registItemResult));
    }
    
    if (isset($_FILES["imageThumbnail"]) && $_FILES["imageThumbnail"]["size"] != 0 &&
        isset($_FILES["image"]) && $_FILES["image"]["size"] != 0) {
        
        // receive image files, save it and get image Id from DB
        $saveImageToServerResult = saveImageToServer($_FILES, $registItemResult["itemid"]);
        if ($saveImageToServerResult["status"] != "true") {
            $db_connection->rollback();
            die(json_encode($saveImageToServerResult));
        }
            
        // store image to Azure 
        $saveImageToAzureResult = saveImageToAzure($saveImageToServerResult["filenames"]);
        if ($saveImageToAzureResult["status"] != "true") {
            $db_connection->rollback();
            die(json_encode($saveImageToAzureResult));
        }
                
        // save image URL to server
        $registImageResult = registImage($registItemResult["itemid"], $saveImageToServerResult["imageids"]["thumbnail"], $saveImageToAzureResult["urls"]["thumbnail"], $saveImageToServerResult["imageids"]["image"], $saveImageToAzureResult["urls"]["image"]);
        if ($registImageResult["status"] != "true") {
            $db_connection->rollback();
            die(json_encode($registImageResult));
        }
    }
        
    // commit image
    $db_connection->commit();

    $result["status"] = "true";
    $result["itemid"]  = $registItemResult["itemid"];
    
} catch (Exception $e) {
    Logger::write("Posting item failed.", $e);
    $result["status"] = "false";
    $result["error"]  = array(UNKNOWN_SYSTEMERROR_NONE);
}

echo(json_encode($result));

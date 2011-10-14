<?php

// include php filess
require_once("../lib/Logger/logger.php");
require_once("./uploadImageToServer.php");
require_once("./saveImageToAzure.php");
require_once("./saveImageUrlToDb.php");
require_once("../lib/util/checkUtil.php");

// get logger
$log = Logger::getLogger('ItemSaveController');

// define return value
$result = true;

// receive image files, save it and get image Id from DB
$imageIds = handleImageUpload();
$filenames = array("thumbnail" => $imageIds["thumbnail"] . ".jpg", "image" => $imageIds["image"] . ".jpg");

// store image to Azure 
$savedUrls = saveImageToAzure($filenames);
if (!checkNotEmpty($savedUrls["thumbnail"]) || !checkNotEmpty($savedUrls["image"])) {
	$log->error("Saving URL is failed.");
	echo("false");
	exit();
}

// save image URL to server
$imageUrlSaveResult = saveImageUrlToDb($imageIds["thumbnail"], $savedUrls["thumbnail"], $imageIds["image"], $savedUrls["image"]);

// return response
if ($imageUrlSaveResult) {
    echo("true");
} else {
	echo("false");
}

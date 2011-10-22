<?php

// include php filess
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/UploadImageToServer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SaveImageToAzure.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SaveImageUrlToDb.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";


// receive image files, save it and get image Id from DB
$imageIds = uploadImageToServer();
$filenames = array("thumbnail" => $imageIds["thumbnail"] . ".jpg", "image" => $imageIds["image"] . ".jpg");

// store image to Azure 
$savedUrls = saveImageToAzure($filenames);
if (!checkNotEmpty($savedUrls["thumbnail"]) || !checkNotEmpty($savedUrls["image"])) {
	Logger::write("Saving URL is failed.");
	die("false");
}

// save image URL to server
$imageUrlSaveResult = saveImageUrlToDb($imageIds["thumbnail"], $savedUrls["thumbnail"], $imageIds["image"], $savedUrls["image"]);

// return response
if ($imageUrlSaveResult) {
    echo("true");
} else {
	echo("false");
}

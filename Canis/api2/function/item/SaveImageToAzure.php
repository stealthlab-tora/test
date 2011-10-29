<?php

// include Windows azure library
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/phpazure/library/Microsoft/WindowsAzure/Storage.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/phpazure/library/Microsoft/WindowsAzure/Credentials/CredentialsAbstract.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/phpazure/library/Microsoft/WindowsAzure/Storage/Blob.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/ItemConstant.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/constant/CommonConstant.php";


// function to save galaxy images to Azure
function saveImageToAzure($filenames) {
    
	Logger::write("Image files will be saved to Windows Azure from now.");
	
	$result = array();
	
    // connection setting
    $host          = "blob.core.windows.net";
    $accountName   = "rakutensfadc";
    $accountKey    = "l+a4G6TsojYT+6HHHzDThyvIasaldhWZkY7g6QmCOxMnmS2GMmT74wpgpSvsnaNMuyXUYV/xXK9GuHitABbtHA==";
    $storageBlob   = new Microsoft_WindowsAzure_Storage_Blob($host, $accountName, $accountKey);
    $containerName = "galaxy";
    
    // check dirctory
    if (!$storageBlob->containerExists($containerName)) {
        $storageBlob->createContainer($containerName);
    }
    
    // Push blob
    $storageBlob->setContainerAcl($containerName, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC);

    // thumbnail
    $thumbnailBlobProperties = $storageBlob->putBlob($containerName, $filenames["thumbnail"], FILE_UPLOAD_PATH . $filenames["thumbnail"]);
    $thumbnailUrl = $thumbnailBlobProperties->__get("url");
    if (checkNotEmpty($thumbnailUrl)) {
    	Logger::write("Thumbnail(" . $filenames["thumbnail"] . ") is saved to Widows Azure.");
    
    } else {
    	Logger::write("Thumbnail(" . $filenames["thumbnail"] . ") is not saved to Windows Azure.");
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }

    // image
    $imageBlobProperties    = $storageBlob->putBlob($containerName, $filenames["image"], FILE_UPLOAD_PATH . $filenames["image"]);
    $imageUrl = $imageBlobProperties->__get("url");
    if (checkNotEmpty($imageUrl)) {
    	Logger::write("Image(" . $filenames["image"] . ") is saved to Widows Azure.");
    
    } else {
    	Logger::write("Image(" . $filenames["image"] . ") is not saved to Windows Azure.");
        $storageBlob->deleteBlob($containerName, $filenames["thumbnail"]);
    	$result["status"] = "false";
    	$result["error"]  = array(SRV_SYSTEMERROR_NONE);
    	return $result;
    }
    
    $result["status"] = "true";
    $result["urls"] = array("thumbnail" => $thumbnailUrl, "image" => $imageUrl);
    return $result;
}
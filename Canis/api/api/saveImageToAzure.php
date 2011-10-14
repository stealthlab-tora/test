<?php

// include Windows azure library
require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Storage.php");
require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Credentials/CredentialsAbstract.php");
require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Storage/Blob.php");

// function to save galaxy images to Azure
function saveImageToAzure($filenames) {
    
    // connection setting
    $host          = "blob.core.windows.net";
    $accountName   = "rakutensfadc";
    $accountKey    = "l+a4G6TsojYT+6HHHzDThyvIasaldhWZkY7g6QmCOxMnmS2GMmT74wpgpSvsnaNMuyXUYV/xXK9GuHitABbtHA==";
    $storageBlob   = new Microsoft_WindowsAzure_Storage_Blob($host, $accountName, $accountKey);
    $containerName = "galaxy";
    $tempImageDir  = "../upload/";
    
    // check dirctory
    if (!$storageBlob->containerExists($containerName)) {
        $storageBlob->createContainer($containerName);
    }
    
    // push blob
    $storageBlob->setContainerAcl($containerName, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC);
    $thumnailBlobProperties = $storageBlob->putBlob($containerName, $filenames["thumbnail"], $tempImageDir . $filenames["thumbnail"]);
    $imageBlobProperties    = $storageBlob->putBlob($containerName, $filenames["image"], $tempImageDir . $filenames["image"]);
    
    // set return value
    $savedUrls              = array();
    $savedUrls["thumbnail"] = $thumnailBlobProperties->__get("url");
    $savedUrls["image"]     = $imageBlobProperties->__get("url");

    return $savedUrls;
}
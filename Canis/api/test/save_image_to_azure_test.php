<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is save image test</title>
</head>
<body>
<?php
if (empty($_POST)) {
?>
Pushing save button, cool1.jpg and cool2.jpg will be saved.
<form action="" method="post">
<input type="hidden" name="post" value="post" />
<input type="submit" value="save" />
</form>
<?php
} else {
    require_once("../api/saveImageToAzure.php");

    $filenames = array();
    $filenames["thumbnail"] = "cool1.jpg";
    $filenames["image"]     = "cool2.jpg";

    $result = saveImageToAzure($filenames);

    if ($result["thumbnail"] == "cool1.jpg" && $result["image"] == "cool2.jpg") {
        echo("Saving image succeeded.");
    } else {
    	die("Saving image failed.");
    }


    // delte test data
    
    // include Windows azure library
    require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Storage.php");
    require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Credentials/CredentialsAbstract.php");
    require_once("../lib/PHPAzure/library/Microsoft/WindowsAzure/Storage/Blob.php");
    
    // connection setting
    $host          = "blob.core.windows.net";
    $accountName   = "rakutensfadc";
    $accountKey    = "l+a4G6TsojYT+6HHHzDThyvIasaldhWZkY7g6QmCOxMnmS2GMmT74wpgpSvsnaNMuyXUYV/xXK9GuHitABbtHA==";
    $storageBlob   = new Microsoft_WindowsAzure_Storage_Blob($host, $accountName, $accountKey);
    $containerName = "galaxy";
    $tempImageDir  = "../temp_image/";

    // delete blob
    $storageBlob->setContainerAcl($containerName, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC);
    $storageBlob->deleteBlob($containerName, $result["thumbnail"]);
    $storageBlob->deleteBlob($containerName, $result["image"]);
}
?>
</body>
</html>
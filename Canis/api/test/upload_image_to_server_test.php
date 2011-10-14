<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is image upload test</title>
</head>
<body>
<?php
if (empty($_FILES)) {
?>
<form action="./upload_image_to_server_test.php" method="post" enctype="multipart/form-data">
Thumbnail   : <input type="file" name="imageThumbnail" /><br />
Large image : <input type="file" name="image" /><br />
<input type="submit" value="upload" />
</form>
<?php
} else {
    require("../api/uploadImageToServer.php");

    $result = uploadImageToServer();

    echo("Thumbnail ID is : " . $result["thumbnail"]);
    echo("<br />");
    echo("Image ID is : " . $result["image"]);}
?>
</body>
</html>
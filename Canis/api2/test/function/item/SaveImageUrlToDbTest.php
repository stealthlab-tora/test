<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is save imageURL to DB test</title>
</head>
<body>
<?php
if (empty($_POST)) {
?>
Pushing save button, thumbnail URL and image URL will be saved.<br />
<form action="" method="post">
Thumbnail Id  : <input type="text" name="thumbnailid" />
Thumbnail URL : <input type="text" name="thumbnailurl" />
image Id      : <input type="text" name="imageid" />
image URL     : <input type="text" name="imageurl" />
<input type="submit" value="save" />
</form>
<?php
} else {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SaveImageUrlToDb.php";
    
    $result = saveImageUrlToDb($_POST["thumbnailid"], $_POST["thumbnailurl"],
                                                      $_POST["imageid"], $_POST["imageurl"]);

    if ($result) {
        echo("Saving image succeeded.");
    } else {
    	die("Saving image failed.");
    }

    
}
?>
</body>
</html>
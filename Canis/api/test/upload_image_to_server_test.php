<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is image upload test</title>
</head>
<body>
<?php
if (empty($_POST)) {
?>
<form action="" method="post">
Email Address : <input type="text" name="email" /><br />
Thumbnail   : <input type="file" name="imageThumbnail" /><br />
Large image : <input type="file" name="image" /><br />
<input type="submit" value="upload" />
</form>
<?php
} else {
//    require("../api/uploadImageToServer.php");

}
?>
</body>
</html>
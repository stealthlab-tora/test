<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is login test</title>
</head>
<body>
<?php
if (empty($_POST)) {
?>
<form action="" method="post">
Email Address : <input type="text" name="email" /><br />
Password      : <input type="password" name="password" /><br />
<input type="submit" value="login" />
</form>
<?php
} else {
    require_once("../api/galaxyLogin.php");

    $result = galaxyLogin($_POST["email"], $_POST["password"]);
    
    if ($result) {
        echo("Login succeeded!");
    } else {
        echo("Login failed!");
    }
}
?>
</body>
</html>
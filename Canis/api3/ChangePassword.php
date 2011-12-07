<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";

$userconstraints = $GLOBALS["userconstraints"];
$errorMessages    = $GLOBALS["errorMessages"];
$pcPageStrings   = $GLOBALS["pcPageStrings"];

// Get language information
$language = null;
if (isset($_GET["lang"]) && CheckUtil::checkNotEmpty($_GET["lang"])) {
	if (in_array($_GET["lang"], $userconstraints["language"]["value"])) {
        $language = $_GET["lang"];
		
	} else {
		$language = $userconstraints["language"]["value"]["en"];
		
	}
	
} else {
	$language = $userconstraints["language"]["value"]["en"];

}

$pageStrings = $pcPageStrings[$language];

// Judge action
$action = null;
if (isset($_GET["requestcode"]) && CheckUtil::checkNotEmpty($_GET["requestcode"])) {

	$user = new User(array("pcrequestcode" => $_GET["requestcode"]));
	$requestcodeValidator = new UserValidator($user);
	$requestcodeValidateResult = $requestcodeValidator->validatePcrequestcode();
	if ($requestcodeValidateResult["status"] == "true") {

		// In case that password is inputted
		if (isset($_POST["password"]) && isset($_POST["password2"])) {
			
			$user->setPassword($_POST["password"]);
			$user->setPassword2($_POST["password2"]);
			$passwordValidator = new UserValidator($user);
			$passwordValidateResult = $passwordValidator->validateUserInfoToChangePassword();
			
			// In case that password is valid
			if ($passwordValidateResult["status"] == "true") {
				$userOperator = new UserOperator($user);
				$changePasswordResult = $userOperator->changePassword();
				
				// In case that password change succeeded
				if ($changePasswordResult["status"] == "true") {
	    			$action = "SHOW_CHANGE_SUCCEDEED";
				
	    		// In case that password change failed
				} else {
					$action = "SHOW_CHANGE_FAILED";
				}
				
			
			// In case that password is invalid
			} else {
				$action = "SHOW_FORM";
				$requestcode = $_GET["requestcode"];
				$error = $passwordValidateResult["error"];
				
			}
		
		// In case that password is not inputted
		} else {
		    $action = "SHOW_FORM";
		    $requestcode = $_GET["requestcode"];
		    $error = null;
		}
	
	} else if (in_array(USER_PWCH_URL_EXPIRED_NONE, $requestcodeValidateResult["error"])) {
		$action = "SHOW_EXPIRATION_MESSAGE";
		
	} else {
	}

} else {
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title><?php echo($pageStrings["title"]);?></title>
<?php if ($action != "SHOW_CHANGE_FAILED" && $action != "SHOW_FORM") {?>
<script type="text/javascript" language="JavaScript">
<!--
function close_window(){
	var closingWindow = window.open('', '_self');
	closingWindow.close();
}
-->
</script>
<?php } else {}?>
</head>
<body>
<?php if ($action == "SHOW_CHANGE_SUCCEDEED") {?>
<h2><?php echo($pageStrings["succeeded_message"]);?></h2>
<input type="button" value="<?php echo($pageStrings["close_window"]);?>" onclick="close_window();"/>

<?php } else if ($action == "SHOW_CHANGE_FAILED") {?>
<h2><?php echo($pageStrings["failed_message"]);?></h2>
<a href="<?php echo(PASSWORD_CHANGE_BASEURL . $requestcode . LANGUAGE_SELECTOR . $language);?>"><?php echo($pageStrings["return_to_form"]);?></a>

<?php } else if ($action == "SHOW_FORM") {?>
<h3><?php echo($pageStrings["guide"]);?></h3>
<?php
	if ($error != null) {
	    echo("<br />\n<div id=\"error\" style=\"border:1px solid #222222;\">\n");
	    foreach ($error as $tempError) {
	        echo($errorMessages[$language][$tempError] . "<br />\n");
	    }
	    echo("</div>\n<br />\n");
	}
?>
<form action="<?php echo(PASSWORD_CHANGE_BASEURL . $requestcode . LANGUAGE_SELECTOR . $language);?>" method="post">
<?php echo($pageStrings["password_field"]);?>  : <input type="password" name="password" id="password" /><br />
<?php echo($pageStrings["password2_field"]);?>  : <input type="password" name="password2" id="password2" /><br /><br />
<input type="submit" value="<?php echo($pageStrings["change_password_button"]);?>"/>
</form>

<?php } else if ($action == "SHOW_EXPIRATION_MESSAGE") {?>
<h3><?php echo($pageStrings["expiration_message"]);?></h3>
<input type="button" value="<?php echo($pageStrings["close_window"]);?>" onclick="close_window();"/>

<?php } else {?>
<h2><?php echo($pageStrings["invalid_access"]);?></h2>
<input type="button" value="<?php echo($pageStrings["close_window"]);?>" onclick="close_window();"/>
<?php }?>
</body>
</html>

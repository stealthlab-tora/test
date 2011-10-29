<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ChangePassword.php";


Logger::write("Change password operaion starts.");

// Validation
$validateResult = validateUserInfoToChangePassword($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// Change password
$changePasswordResult = changePassword($_POST["galaxyuserid"], $_POST["newpassword"]);
echo(json_encode($changePasswordResult));

Logger::write("Change password operation ends.");

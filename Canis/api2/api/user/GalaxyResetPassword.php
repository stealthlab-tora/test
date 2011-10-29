<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ResetPassword.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/SendTempPassword.php";


Logger::write("Reset password and send email operaion starts.");

// Validation
$validateResult = validateUserEmail($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// Reset password
$resetResult = resetPassword($_POST["email"]);
if ($resetResult["status"] != "true") {
	die(json_encode($resetResult));
}

// Send email
$sendTempPasswordResult = sendTempPassword($_POST["email"], $resetResult["temppassword"]);

echo(json_encode($sendTempPasswordResult));

Logger::write("Reset password and send email operation ends.");

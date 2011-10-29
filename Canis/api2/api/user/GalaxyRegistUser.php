<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/RegistUser.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";


Logger::write("User regist operation starts.");

// Validation
$validateResult = validateUserInfoToRegist($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// Regist user
$registResult = registUser($_POST);
if ($registResult["status"] == "true") {
	Logger::write("User regist operation succeeded.");
	
} else {
	Logger::write("User regist operation failed.");
}
echo(json_encode($registResult));

Logger::write("User regist operation ends.");

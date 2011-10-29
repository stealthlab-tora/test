<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/Login.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";


Logger::write("User login operation starts.");

// validation
$validateResult = validateUserInfoToLogin($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// login
$loginResult = login($_POST["email"], $_POST["password"]);
echo(json_encode($loginResult));

Logger::write("User login operation ends.");

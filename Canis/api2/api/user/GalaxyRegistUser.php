<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/RegistUser.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";

Logger::write("User regist operation start.");

$validateResult = validateUserInfo($_POST);
if ($validateResult !== true) {
	die($validateResult);
}

$user = array();
$user["firstname"]   = $_POST["firstname"];
$user["lastname"]    = $_POST["lastname"];
$user["email"]       = $_POST["email"];
$user["password"]    = $_POST["password"];
$user["password2"]   = $_POST["password2"];
$user["zipcode"]     = $_POST["zipcode"];
$user["country"]     = $_POST["country"];
$user["state"]       = $_POST["state"];
$user["city"]        = $_POST["city"];
$user["street"]      = $_POST["street"];
$user["phonenumber"] = $_POST["phonenumber"];

if (registUser($user)) {
	Logger::write("User regist operation succeeded.");
	echo("true");
} else {
	Logger::write("User regist operation failed.");
	echo("false");
}

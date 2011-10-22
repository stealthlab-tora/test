<?php

// include php files
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/Login.php";

// get POST parameter
$email = $_POST["email"];
Logger::write("email:" .$email);
$password = $_POST["password"];
Logger::write("password:" . $password);

// validation
if (!checkEmail($email)) {
	die("false");
}
if (!checkPassword($password)) {
    die("false");
}

// login
if (login($email,$password)) {
  Logger::write("result:" . "true");
  echo "true";
} else { 
  Logger::write("result:" . "false");
  echo "false";
}

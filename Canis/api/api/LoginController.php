<?php

// include php files
require_once('../lib/Logger/logger.php');
require_once('../lib/util/checkUtil.php');
require("./galaxyLogin.php");

// get logger
$log = Logger::getLogger('LoginController');

// get POST parameter
$email = $_POST["email"];
$log->info("email:" . $email);
$password = $_POST["password"];
$log->info("password:" . $password);

// validation
if (!checkEmail($email)) {
	die("false");
}
if (!checkPassword($password)) {
    die("false");
}

// login
if (galaxyLogin($email,$password)) {
  $log->info("result:" . "true");
  echo "true";
} else { 
  $log->info("result:" . "false");
  echo "false";
}

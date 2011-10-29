<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";


Logger::write("User info validate operation start.");

$validateResult = validateUserInfoToRegist($_POST);
echo(json_encode($validateResult));

Logger::write("User info validate operation done.");
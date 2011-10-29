<?php

// Include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/user/ValidateUserInfo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/GetRecentSellItem.php";


Logger::write("Get recent sell item operation starts.");

// Validate galaxyuserid
$validateResult = validateGalaxyuserId($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// Get recent sell item
$getRecentSellItemResult = getRecentSellItem($_POST["galaxyuserid"]);
echo(json_encode($getRecentSellItemResult));

Logger::write("Get recent sell item  operation ends.");

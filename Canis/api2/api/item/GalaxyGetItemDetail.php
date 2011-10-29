<?php

// Include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/ValidateItemInfo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/GetItemDetail.php";


Logger::write("Serve Item detail operation starts.");

// Validate item information
$validateResult = validateItemId($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

// Get item detail
$getItemDetailResult = getItemDetail($_POST["itemid"]);
echo(json_encode($getItemDetailResult));

Logger::write("Serve Item detail operation ends.");

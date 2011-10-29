<?php

// Include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/ValidateSearchInfo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/SearchItem.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/util/Check.php";

Logger::write("Item search operation starts.");

// Validate item information
$validateResult = validateSearchInfo($_POST);
if ($validateResult["status"] != "true") {
	die(json_encode($validateResult));
}

if (!isset($_POST["order"])) {
	$_POST["order"] = null;
}

// Search item
$searchItemResult = searchItem($_POST["galaxyuserid"], $_POST["value"], $_POST["type"], $_POST["order"]);
echo(json_encode($searchItemResult));

Logger::write("Item search operation ends.");

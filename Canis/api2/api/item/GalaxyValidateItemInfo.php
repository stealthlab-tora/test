<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/function/item/ValidateItemInfo.php";


Logger::write("Item info validate operation start.");

$validateResult = validateItemInfo($_POST, $_FILES);
echo(json_encode($validateResult));

Logger::write("Item info validate operation done.");
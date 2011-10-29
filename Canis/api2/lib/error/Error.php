<?php

function returnError($errorCode) {
	$result = array();
	$result["status"] = "false";
	$result["error"] = array($errorCode);
	return $result;
}

<?php

// include libraries
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/logger/Logger.php";

function getGeoLocation($address)
{
	$username = "az@stealthlab.net";
	$password = "0EPqoWKDHf-DvdMKABZn5DyWLKtT4I4CJLxTjtg";

	// Start XML file, create parent node
	$doc = domxml_new_doc("1.0");
	$node = $doc->create_element("markers");
	$parnode = $doc->append_child($node);
		
	header("Content-type: text/xml");

	$node = $doc->create_element("marker");
	$newnode = $parnode->append_child($node);
	
//	$newnode->set_attribute("name", $row['name']);
	$newnode->set_attribute("address", $row['address']);
//	$newnode->set_attribute("lat", $row['lat']);
//	$newnode->set_attribute("lng", $row['lng']);
//	$newnode->set_attribute("type", $row['type']);
		
	$xmlfile = $doc->dump_mem();
	echo $xmlfile;
}

var_dump(getGeoLocation("94115"));
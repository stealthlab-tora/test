<?php

/**
* The API around Item
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


if (!isset($_POST["action"])) {
	WarnLogger::write("Request without action has come.");

} else if ($_POST["action"] == "CANCEL_ITEM") {

    DebugLogger::write("Cancel item operation starts.");

    $item           = new Item($_POST);
    $itemValidator  = new ItemValidator($item);
    $itemOperator   = new ItemOperator($item);
    $thread		     = new Thread($_POST);
    $threadOperator  = new ThreadOperator($thread);
    
    
    // Validate item id
    $validateItemIdResult = $itemValidator->validateItemId("ON_SALE", true);
    if ($validateItemIdResult["status"] != "true") {
        echo(json_encode($validateItemIdResult));
        return;
    
    } else {
    }

    
    // Change item status
    $changeItemstatusResult = $itemOperator->changeItemstatus("DELETED");
    
    // Change thread status
    $closeSameItemidThreadsResult = $threadOperator->closeSameItemidThreads();
    
    if ($closeSameItemidThreadsResult["status"] == "true") {
        echo(json_encode($changeItemstatusResult));
    
    } else {
    	echo(json_encode($closeSameItemidThreadsResult));
    	
    }
    
    DebugLogger::write("Cancel item operation ends.");


} else if ($_POST["action"] == "UPDATE_ITEM_TO_BE_SOLD") {
    
	DebugLogger::write("Update item to be sold operation starts.");

	$item		     = new Item($_POST);
	$itemValidator   = new ItemValidator($item);
	$itemOperator    = new ItemOperator($item);
	$thread		     = new Thread($_POST);
	$threadValidator = new ThreadValidator($thread);
	$threadOperator  = new ThreadOperator($thread);
	
	// Validate item id
	$validateItemIdResult = $itemValidator->validateItemId("ON_SALE", true);
	if ($validateItemIdResult["status"] != "true") {
		echo(json_encode($validateItemIdResult));
		return;

	} else {
	}

	// Validate thread id
	$validateThreadidResult = $threadValidator->validateThreadid();
	if ($validateThreadidResult["status"] != "true") {
		echo(json_encode($validateThreadidResult));
		return;
	
	} else {
	}
	
	// Change item status
	$changeItemstatusResult = $itemOperator->changeItemstatus("SOLD");
	if ($changeItemstatusResult["status"] != "true") {
		echo(json_encode($changeItemstatusResult));
		return;
	
	} else {
	}
	
	// Change thread statuses to "CLOSED" which has same itemid
	$closeSameItemidThreadsResult = $threadOperator->closeSameItemidThreads();
	if ($closeSameItemidThreadsResult["status"] != "true") {
		echo(json_encode($closeSameItemidThreadsResult));
		return;
	
	} else {
	}
	
	// Change thread status
	$changeThreadstatusResult = $threadOperator->changeThreadstatus("SHOULD_BE_RATED");
	if ($changeThreadstatusResult["status"] != "true") {
		echo(json_encode($changeThreadstatusResult));
	
	} else {
	}
	
    // Notify item sold information to thread
	$threadOperator->notifyItemSoldInfo();
	
	echo(json_encode($changeItemstatusResult));

	DebugLogger::write("Update item to be sold operation ends.");
    
    
} else if ($_POST["action"] == "GET_ITEM_DETAIL") {
    
    DebugLogger::write("Serve Item detail operation starts.");

    $item           = new Item($_POST);
    $itemValidator  = new ItemValidator($item);
    $itemOperator   = new ItemOperator($item);

    
    // Validate item information
    $validateResult = $itemValidator->validateItemId("ONSALE_OR_SOLD");
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }
    
    // Get item detail
    $getItemDetailResult = $itemOperator->getItemDetail();
    echo(json_encode($getItemDetailResult));
    
    DebugLogger::write("Serve Item detail operation ends.");

    
} else if ($_POST["action"] == "POST_ITEM") {
    
    DebugLogger::write("Post item operation starts.");
    
    $item           = new Item($_POST);
    $itemValidator  = new ItemValidator($item);
    $itemOperator   = new ItemOperator($item);
    $images         = new Images($_FILES);
    $imageValidator = new ImageValidator($images);
    $imageOperator  = new ImageOperator($images);
    
    // validate item information
    $validateItemResult = $itemValidator->validateItemInfo(false);
    if ($validateItemResult["status"] != "true") {
        echo(json_encode($validateItemResult));
        return;
    
    } else {
    }
    
    // validate image information
    $validateImageResult = $imageValidator->validateImage();
    if ($validateImageResult["status"] != "true") {
        echo(json_encode($validateImageResult));
        return;
    
    } else {
    }
        
    $result = null;
    
    try {
        // get DB connection
        $db_connection   = GalaxyDbConnector::getConnection();
    
        // begin transaction
        $db_connection->beginTransaction();
    
        // register item
        $registerItemResult = $itemOperator->registerItem();
        if ($registerItemResult["status"] != "true") {
            $db_connection->rollback();
            echo(json_encode($registerItemResult));
            return;
    
        } else {
        }
    
        if (!is_null($images->getThumbnail()) && $images->getThumbnail()->getSize() != 0 &&
        !is_null($images->getImage()) && $images->getImage()->getSize()) {
    
            // receive image files, save it and get image Id from DB
            $saveImageToServerResult = $imageOperator->saveImageToServer($registerItemResult["itemid"]);
            if ($saveImageToServerResult["status"] != "true") {
                $db_connection->rollback();
                echo(json_encode($saveImageToServerResult));
                return;
    
            } else {
            }
    
            // store image to Azure
            $saveImageToAzureResult = $imageOperator->saveImageToAzure();
            if ($saveImageToAzureResult["status"] != "true") {
                $db_connection->rollback();
                echo(json_encode($saveImageToAzureResult));
                return;
    
            } else {
            }
    
            // save image URL to server
            $registImageResult = $imageOperator->registerImage();
            if ($registImageResult["status"] != "true") {
                $db_connection->rollback();
                echo(json_encode($registImageResult));
                return;
    
            } else {
            }
    
        } else {
        }
    
        // commit image
        $db_connection->commit();
    
        $result = OutputUtil::getSuccessOutput(array("itemid" => $registerItemResult["itemid"]));
    
    } catch (Exception $e) {
        ErrorLogger::write("Posting item failed.", $e);
        $result = OutputUtil::getErrorOutput(array(UNKNOWN_SYSTEM_ERROR_NONE));
    }
    
    echo(json_encode($result));
    
    DebugLogger::write("Post item operation starts.");
    
    
} else if ($_POST["action"] == "SEARCH_ITEM") {

    DebugLogger::write("Item search operation starts.");
    
    $itemSearchInfo = new ItemSearchInfo($_POST);
    $itemSearchInfoValidator = new ItemSearchInfoValidator($itemSearchInfo);
    $itemSearcher = new ItemSearcher($itemSearchInfo);

    // Validate item information
    $validateResult = $itemSearchInfoValidator->validateSearchInfo();
    if ($validateResult["status"] != "true") {
        echo(json_encode($validateResult));
        return;
    
    } else {
    }
    
    // Search item
    $searchItemResult = $itemSearcher->search();
    echo(json_encode($searchItemResult));
    
    DebugLogger::write("Item search operation ends.");

    
} else if ($_POST["action"] == "VALIDATE_ITEM_INFO") {

    DebugLogger::write("Item info validate operation start.");
    
    $item = new Item($_POST);
    $itemValidator = new ItemValidator($item);
    
    $validateResult = $itemValidator->validateItemInfo(true);
    echo(json_encode($validateResult));
    
    DebugLogger::write("Item info validate operation done.");
    
    
} else {
    WarnLogger::write("Request without proper action has come.");
}

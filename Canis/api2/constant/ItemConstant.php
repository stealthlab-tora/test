<?php

define("FILE_UPLOAD_PATH",           $_SERVER["DOCUMENT_ROOT"] . "/temp_image/");
define("THUMBNAILURL_SAVE_QUERY",    "update images set imageurl = :THUMBNAIL_URL where imageid = :THUMBNAIL_ID");
define("IMAGEURL_SAVE_QUERY",        "update images set imageurl = :IMAGE_URL where imageid = :IMAGE_ID");
define("INSERT_IMAGE_RECORD_QUERY",  "insert into images(itemid, imagetype) values (1, :TYPE)");
<?php

/**
* The PHP file to require AutoLoaders, read settings and define constants.
* 
*/


/**
* Require AutoLoaders
*
*/

// Require AutoLoader for Our own PHPs
require_once $_SERVER["DOCUMENT_ROOT"] . "/config/AutoLoader.php";
$autoloader = new AutoLoader();

// Require Autoloader for Windows Azure
require_once $_SERVER["DOCUMENT_ROOT"] . "/libraries/phpazure/library/Microsoft/AutoLoader.php";


/**
* Read settings
*
*/

// Read environment.json in which dotcloud unique setting is written
$env = json_decode(file_get_contents($_SERVER['HOME'].'/environment.json'), true);


/**
* Define constants
*
*/

// Define paths
define("LOG_DIRECTORY",    "/home/dotcloud/logs");
define("FILE_UPLOAD_PATH", $_SERVER["DOCUMENT_ROOT"] . "/temp_image/");

// Define error codes
define("USER_EMPTY_EMAIL",             "10101");
define("USER_EMPTY_PASSWORD",          "10102");
define("USER_EMPTY_FIRSTNAME",         "10104");
define("USER_EMPTY_LASTNAME",          "10105");
define("USER_EMPTY_STREET",            "10106");
define("USER_EMPTY_CITY",              "10107");
define("USER_EMPTY_COUNTRY",           "10109");
define("USER_EMPTY_ZIPCODE",           "10110");
define("USER_EMPTY_PHONENUMBER",       "10111");
define("USER_EMPTY_LOCATION_STATE",    "10112");
define("USER_EMPTY_LOCATION_CITY",     "10113");
define("USER_EMPTY_ITEMNAME",          "10114");
define("USER_EMPTY_ITEMDESCRIPTION",   "10115");
define("USER_EMPTY_ITEMPRICE",         "10116");
define("USER_EMPTY_SEARCH_VALUE",      "10117");
define("USER_EMPTY_MESSAGE",           "10118");
define("USER_TOOSHORT_PASSWORD",       "10202");
define("USER_INVALID_EMAIL",           "10301");
define("USER_INVALID_PASSWORD",        "10302");
define("USER_INVALID_FIRSTNAME",       "10304");
define("USER_INVALID_LASTNAME",        "10305");
define("USER_INVALID_STREET",          "10306");
define("USER_INVALID_CITY",            "10307");
define("USER_INVALID_ZIPCODE",         "10310");
define("USER_INVALID_PHONENUMBER",     "10311");
define("USER_INVALID_LOCATION_STATE",  "10312");
define("USER_INVALID_LOCATION_CITY",   "10313");
define("USER_INVALID_ITEMNAME",        "10314");
define("USER_INVALID_ITEMDESCRIPTION", "10315");
define("USER_INVALID_ITEMPRICE",       "10316");
define("USER_INVALID_SEARCH_VALUE",    "10317");
define("USER_INVALID_MESSAGE",         "10318");
define("USER_UNMATCH_PASSWORD",        "10402");
define("USER_REGISTRATED_USER_NONE",   "10599");
define("USER_LOGIN_FAILURE_NONE",      "10699");
define("USER_LOCATION_NOT_FOUND_NONE", "10799");
define("USER_PWCH_URL_EXPIRED_NONE",   "10899");
define("APP_SYSTEM_ERROR_NONE",        "29999");
define("SRV_SYSTEM_ERROR_NONE",        "39999");
define("UNKNOWN_SYSTEM_ERROR_NONE",    "99999");


// Define Pusher settings
define('PUSHER_API_KEY',                '3c15bf2b12d3b1df122c');
define('PUSHER_API_SECRET',             '7f9e78a323515005c0f3');
define('PUSHER_APP_ID',                 '11012');
define('PUSHER_NEW_CHAT_MESSAGE_EVENT', 'NewChatMessage');
define('PUSHER_ITEM_SOLD_EVENT',        'ItemSold');


// Define Salts
define("CHANNEL_NAME_SALT_WORD",    "shaggy");
define("PASSWORD_CHANGE_SALT_WORD", "cioppino");
define("USER_PASSWORD_SALT_WORD",   "cool");


// Define MySQL queries

// for users
define("GET_USER_LOCATION_QUERY",             "select country, latitude, longtitude from users where galaxyuserid = :GALAXYUSERID");
define("UPDATE_AVERAGE_RATE",                 "update users set averagerate = :AVERAGERATE where galaxyuserid = :GALAXYUSERID");
define("GET_AVERAGE_RATE",                    "select averagerate from users where galaxyuserid = :GALAXYUSERID");
define("LOGIN_QUERY",                         "select * from users where email = :EMAIL and password = :PASSWORD");
define("REGIST_USER_QUERY",                   "insert into users(firstname, lastname, email, password, zipcode, country, state, city, street, phonenumber, latitude, longtitude, updatedtime) values (:FIRSTNAME, :LASTNAME, :EMAIL, :PASSWORD, :ZIPCODE, :COUNTRY, :STATE, :CITY, :STREET, :PHONENUMBER, :LATITUDE, :LONGTITUDE, :UPDATEDTIME)");
define("REGISTER_PASSWORD_CHANGE_TOKEN",      "update users set pcrequestcode = :PCREQUESTCODE, pcrequesttime = :PCREQUESTTIME where email = :EMAIL");
define("CHANGE_PASSWORD_QUERY",               "update users set password = :PASSWORD, pcrequestcode = null where pcrequestcode = :PCREQUESTCODE");
define("USER_CHECK_QUERY",                    "select galaxyuserid from users where email = :EMAIL");
define("USER_EXIST_CHECK_QUERY_BY_ID",        "select galaxyuserid from users where galaxyuserid = :GALAXYUSERID");
define("PCREQUESTCODE_CHECK",                 "select * from users where pcrequestcode = :PCREQUESTCODE");
define("USER_EXIST_CHECK_QUERY_BY_ID_AND_PW", "select galaxyuserid from users where galaxyuserid = :GALAXYUSERID and password = :PASSWORD");
define("GET_USER_INFO_QUERY",                 "select firstname, lastname, country from users where email = :EMAIL");

// for items
define("REGISTER_ITEM_DML",          "insert into items (galaxyuserid, itemname, description, price, currency, locationtype, zipcode, state, city, latitude, longtitude, itemstatus, updatedtime, expirytime) values (:GALAXYUSERID, :ITEMNAME, :DESCRIPTION, :PRICE, :CURRENCY, :LOCATIONTYPE, :ZIPCODE, :STATE, :CITY, :LATITUDE, :LONGTITUDE, 'ON_SALE', :UPDATEDTIME, :EXPIRYTIME)");
define("GET_EXPIRED_ITEM_QUERY",     "select * from items where expirytime < :TODAY and itemstatus = 'ON_SALE'");
define("GET_ITEM_DETAIL_QUERY",      "select items.itemid, items.galaxyuserid, items.itemname, items.description, items.price, items.currency, items.locationtype, items.zipcode, items.state, items.city, items.latitude, items.longtitude, items.itemstatus, images.imageurl, users.averagerate from (items left join images on items.itemid = images.itemid and images.imagetype = 'IMAGE') left join users on items.galaxyuserid = users.galaxyuserid where items.itemid = :ITEMID");
define("GET_RECENT_SELL_ITEM_QUERY", "select items.itemid, items.galaxyuserid, items.itemname, items.price, items.currency, items.locationtype, items.zipcode, items.state, items.city, items.latitude, items.longtitude, items.itemstatus, images.imageurl from items left join images on items.itemid = images.itemid and images.imagetype = 'THUMBNAIL' where items.galaxyuserid = :GALAXYUSERID and items.itemstatus != 'DELETED' order by updatedtime desc limit 5");
define("GET_ITEM_LIST_QUERY",        "select * from items where items.galaxyuserid = :GALAXYUSERID");
define("GET_SELLER",                 "select galaxyuserid from items where itemid = :ITEMID");


// In these queries, spherical law of cosines formulas is used to calulate distance.
// (Spherical law : distance = acos(sin(lat1)*sin(lat2)+cos(lat1)*cos(lat2)*cos(lng2-lng1))*R   #lat=>latitude, lng=>longtitude)
// Please reference the following link to know details.
// http://www.movable-type.co.jp/scripts/latlong.html
define("SEARCH_ITEM_BASE_QUERY_TOP",      "select items.itemid, items.galaxyuserid, items.itemname, items.price, items.currency, items.locationtype, items.zipcode, items.state, items.city, items.latitude, items.longtitude, items.updatedtime, images.imageurl, (3959 * acos(cos(radians(:LATITUDE1)) * cos(radians(items.latitude)) * cos(radians(items.longtitude) - radians(:LONGTITUDE)) + sin(radians(:LATITUDE2)) * sin(radians(items.latitude)))) as distance, users.averagerate from (items left join images on items.itemid = images.itemid and images.imagetype = 'THUMBNAIL') left join users on items.galaxyuserid = users.galaxyuserid where items.currency = :CURRENCY and items.itemstatus = 'ON_SALE'");
define("SEARCH_ITEM_BASE_QUERY_BOTTOM",   "having distance < 50");
define("ITEM_EXIST_CHECK_QUERY",          "select itemid from items where itemid = :ITEMID");
define("CHANGE_ITEM_STATUS_QUERY",        "update items set itemstatus = :ITEMSTATUS, updatedtime = :UPDATEDTIME where itemid = :ITEMID");
define("CHANGE_ITEM_STATUSES_TO_EXPIRED", "update items set itemstatus = 'EXPIRED', updatedtime = :UPDATEDTIME where itemid in");


// for images
define("REGISTER_IMAGE_DML", "insert into images (imageid, itemid, imagetype, imageurl) values (:IMAGEID, :ITEMID, :IMAGETYPE, :IMAGEURL)");

// for threads
define("CLOSE_THREADS_OF_EXPIRED_ITEMS", "update threads set threadstatus = 'CLOSED' where itemid in");
define("GET_CHANNELNAME_BY_THREADID",    "select channelname from threads where threadid = :THREADID");
define("GET_THREAD_ID",                  "select * from threads where itemid = :ITEMID and buyer = :BUYER");
define("GET_THREAD_INFO",                "select threads.itemid as itemid, threads.buyer as buyer, threads.threadstatus as threadstatus, items.galaxyuserid as seller from threads left join items on threads.itemid = items.itemid where threads.threadid = :THREADID");
define("GET_THREAD_LIST",                "select threads.threadid as threadid, threads.itemid as itemid, threads.buyer as buyer, threads.channelname as channelname, threads.threadstatus as threadstatus, items.galaxyuserid as seller, items.itemname as itemname, items.itemstatus as itemstatus, images.imageurl as thumbnailurl from (threads left join items on threads.itemid = items.itemid) left join images on threads.itemid = images.itemid and images.imagetype = 'THUMBNAIL' where threads.buyer = :BUYER");
define("CREATE_THREAD",                  "insert into threads(itemid, buyer, channelname, threadstatus) values (:ITEMID, :BUYER, :CHANNELNAME, 'OPEN')");
define("THREAD_EXIST_CHECK",             "select * from threads where threadid = :THREADID");
define("CHANGE_THREADSTATUS",            "update threads set threadstatus = :THREADSTATUS where threadid = :THREADID");
define("CLOSE_THREADS",                  "update threads set threadstatus = 'CLOSED' where itemid = :ITEMID");

// for messages
define("SAVE_MESSAGE",                 "insert into messages(threadid, sender, receiver, message, senttime) values (:THREADID, :SENDER, :RECEIVER, :MESSAGE, :SENTTIME)");
define("GET_VARIOUS_THREADS_MESSAGES", "select * from messages where threadid in");
define("GET_MESSAGES",                 "select * from messages where threadid = :THREADID");
define("GET_MESSAGE",                  "select * from messages where threadid = :THREADID and messageid = :MESSAGEID");
define("GET_LATEST_MESSAGE_TOP",       "select threadid, max(messageid) as messageid from messages where threadid in");
define("GET_LATEST_MESSAGE_BOTTOM",    "group by threadid");

// for messagereadhistories
define("CREATE_MESSAGE_READ_HISTORY",                "insert into messagereadhistories (threadid, galaxyuserid, lastreadmessage) values (:THREADID, :SELLER, 0), (:THREADID, :BUYER, 0)");
define("UPDATE_LASTREADMESSAGE",                     "update messagereadhistories set lastreadmessage = :LASTREADMESSAGE where threadid = :THREADID and galaxyuserid = :GALAXYUSERID");
define("GET_MESSAGE_READ_HISTORY",                   "select * from messagereadhistories where threadid = :THREADID and galaxyuserid = :GALAXYUSERID");
define("GET_MESSAGE_READ_HISTORIES_OF_OPEN_THREADS", "select * from messagereadhistories left join threads on messagereadhistories.threadid = threads.threadid where messagereadhistories.galaxyuserid = :GALAXYUSERID and threads.threadstatus = 'OPEN'");
define("GET_NO_UPDATE_THREAD_LIST",                  "select threadid from messagereadhistories where galaxyuserid = :GALAXYUSERID and lastreadmessage in");

// for rates
define("RATE_USER", "insert into rates(galaxyuserid, rate, rater) values (:GALAXYUSERID, :RATE, :RATER)");
define("GET_RATE",  "select * from rates where galaxyuserid = :GALAXYUSERID");


// Define mail message for password change request
define("PW_RQ_EMAIL_SUBJECT_EN",              "[Galaxy]About changing your password");
define("PW_RQ_EMAIL_BODY_TOP_EN",             "\n\nThank you for your request to change your passsword.\n" .
                                              "Please access the following URL within 24hours\n" .
                                              "and change your password as soon as possible.\n\n");
define("PASSWORD_CHANGE_BASEURL",             $env["DOTCLOUD_WWW_HTTP_URL"] . "ChangePassword.php?requestcode=");
define("LANGUAGE_SELECTOR",                   "&lang=");
define("PW_RQ_EMAIL_BODY_BTM_EN",             "\n\nThank you.\n" .
                                              "\n" .
                                              "Galaxy\n");
define("PW_RQ_EMAIL_SUBJECT_JP",              "[Galaxy]パスワードのご変更について");
define("PW_RQ_EMAIL_BODY_TOP_JP",             "\n\nこの度はパスワード変更のお手続きをしていただき、ありがとうございます。\n" .
                                              "下記のURLにアクセスし、新しいパスワードを入力していただくと、パスワードの変更が完了いたします。\n" .
                                              "URLはこのメールが送られてから24時間以内のみ有効ですので、お早めにお手続きしていただければと存じます。\n" .
                                              "今後とも楽天のサービスのご利用をよろしくお願いいたします。\n\n");
define("PW_RQ_EMAIL_BODY_BTM_JP",             "\n\n" .
                                              "Galaxy\n");
define("PW_RQ_EMAIL_HEADER",                  "From: galaxyadmin@stealthlab.net . \n . Reply-To: galaxyadmin@stealthlab.net");


// Define constraints

// for user
$userconstraints                              = array();
$userconstraints["firstname"]["max_length"]   = 255;
$userconstraints["lastname"]["max_length"]    = 255;
$userconstraints["password"]["min_length"]    = 6;
$userconstraints["password"]["max_length"]    = 255;
$userconstraints["zipcode"]["max_length"]     = 255;
$userconstraints["country"]["value"]          = array("USA", "JAPAN");
$userconstraints["state"]["max_length"]       = 255;
$userconstraints["city"]["max_length"]        = 255;
$userconstraints["street"]["max_length"]      = 255;
$userconstraints["phonenumber"]["max_length"] = 255;
$userconstraints["language"]["value"]         = array("en" => "EN", "ja" => "JA");

// for item
$itemconstraints                               = array();
$itemconstraints["itemname"]["max_length"]     = 255;
$itemconstraints["description"]["max_length"]  = 1000;
$itemconstraints["price"]["max_length"]        = 11;
$itemconstraints["currency"]["value"]          = array("USD", "JPY");
$itemconstraints["locationtype"]["value"]      = array("zipcode" => "ZIPCODE", "address" => "ADDRESS", "geolocation" => "GEOLOCATION");
$itemconstraints["zipcode"]["max_length"]      = 20;
$itemconstraints["state"]["max_length"]        = 255;
$itemconstraints["city"]["max_length"]         = 255;
$itemconstraints["search_value"]["max_length"] = 255;
$itemconstraints["search_type"]["value"]       = array("keyword" => "KEYWORD", "barcode" => "BARCODE");
$itemconstraints["search_order"]["value"]      = array("updatedtime_asc" => "LMDASC", "updatedtime_desc" => "LMDDESC", "price_asc" => "PRICEASC", "price_desc" => "PRICEDESC");
$itemconstraints["itemstatus"]["value"]        = array("SOLD", "DELETED");

// for message
$messageconstraints                          = array();
$messageconstraints["message"]["max_length"] = 1000;

// for rate
$rateconstraints                  = array();
$rateconstraints["rate"]["value"] = array("good" => "GOOD", "fair" => "FAIR", "bad" => "BAD");


// Define error messages
$errorMessages                = array();
$errorMessages["EN"]["10102"] = "Please enter password";
$errorMessages["EN"]["10202"] = "password is too short.";
$errorMessages["EN"]["10302"] = "Missing password. Please correct and try again.";
$errorMessages["EN"]["10402"] = "Your password entries must match. Please check both";
$errorMessages["EN"]["29999"] = "Sorry, system error is occurred. Please try again.";
$errorMessages["EN"]["39999"] = "Sorry, system error is occurred. Please try again.";
$errorMessages["EN"]["99999"] = "Sorry, system error is occurred. Please try again.";
$errorMessages["JA"]["10102"] = "パスワードを入力してください。";
$errorMessages["JA"]["10202"] = "パスワードが短すぎます。";
$errorMessages["JA"]["10302"] = "パスワードが不正です。";
$errorMessages["JA"]["10402"] = "パスワードが一致しません。";
$errorMessages["JA"]["29999"] = "エラーが発生しました。申し訳ありませんが最初からやり直してください。";
$errorMessages["JA"]["39999"] = "エラーが発生しました。申し訳ありませんが最初からやり直してください。";
$errorMessages["JA"]["99999"] = "エラーが発生しました。申し訳ありませんが最初からやり直してください。";


// Define passwrod change page strings
$pcPageStrings                                 = array();
$pcPageStrings["EN"]["title"]                  = "[Galaxy]Password change page";
$pcPageStrings["EN"]["guide"]                  = "Please input password and click the button to change password.";
$pcPageStrings["EN"]["expiration_message"]     = "This URL is expired. Please request password change again.";
$pcPageStrings["EN"]["password_field"]         = "Password";
$pcPageStrings["EN"]["password2_field"]        = "Password(re-enter)";
$pcPageStrings["EN"]["change_password_button"] = "Change Password!";
$pcPageStrings["EN"]["succeeded_message"]      = "Password change succeeded!";
$pcPageStrings["EN"]["failed_message"]         = "Password change failed...Try again.";
$pcPageStrings["EN"]["invalid_access"]         = "Invalid Access!";
$pcPageStrings["EN"]["close_window"]           = "Close this page";
$pcPageStrings["EN"]["return_to_form"]         = "Return to form";
$pcPageStrings["JA"]["title"]                  = "[Galaxy]パスワード変更ページ";
$pcPageStrings["JA"]["guide"]                  = "パスワードを入力し、ボタンをクリックしてください。";
$pcPageStrings["JA"]["expiration_message"]     = "URLの期限が切れました。再度、パスワード変更の手続きを初めから行ってください。";
$pcPageStrings["JA"]["password_field"]         = "パスワード";
$pcPageStrings["JA"]["password2_field"]        = "パスワード(再入力)";
$pcPageStrings["JA"]["change_password_button"] = "パスワード変更！";
$pcPageStrings["JA"]["succeeded_message"]      = "パスワードが変更されました！";
$pcPageStrings["JA"]["failed_message"]         = "パスワードの変更に失敗しました。再度お試しください。";
$pcPageStrings["JA"]["invalid_access"]         = "不正なアクセスです！";
$pcPageStrings["JA"]["close_window"]           = "このページを閉じる";
$pcPageStrings["JA"]["return_to_form"]         = "入力画面に戻る";

<?php

define("SALT_WORD",         "cool");
define("LOGIN_QUERY",       "select * from users where email = :EMAIL and password = :PASSWORD");
define("REGIST_USER_QUERY", "insert into users(firstname, lastname, email, password, zipcode, country, state, city, street, phonenumber, lastmodifieddate) values (:FIRSTNAME, :LASTNAME, :EMAIL, :PASSWORD, :ZIPCODE, :COUNTRY, :STATE, :CITY, :STREET, :PHONENUMBER, :LASTMODIFIEDDATE)");
define("RESET_PASSWORD_QUERY", "update users set password = :PASSWORD, temppwflag = 'TRUE', lastmodifieddate = :LASTMODIFIEDDATE where email = :EMAIL");
define("CHANGE_PASSWORD_QUERY", "update users set password = :PASSWORD, temppwflag = 'FALSE', lastmodifieddate = :LASTMODIFIEDDATE where galaxyuserid = :GALAXYUSERID");
define("USER_CHECK_QUERY",  "select galaxyuserid from users where email = :EMAIL");
if (!defined("USER_EXIST_CHECK_QUERY_BY_ID")) {
    define("USER_EXIST_CHECK_QUERY_BY_ID", "select galaxyuserid from users where galaxyuserid = :GALAXYUSERID");
}
define("USER_EXIST_CHECK_QUERY_BY_ID_AND_PW", "select galaxyuserid from users where galaxyuserid = :GALAXYUSERID and password = :PASSWORD");
define("GET_USER_INFO_QUERY", "select firstname, lastname, country from users where email = :EMAIL");
define("PW_RQ_EMAIL_SUBJECT_EN",     "[Galaxy]Password reset notification");
define("PW_RQ_EMAIL_BODY_TOP_EN",    "\n\nWe reset your Galaxy password.\n" . 
                                     "Please login with the temporary password which is valid for 24hours\n" .
                                     "and change your password immediately.\n\n");
define("PW_RQ_EMAIL_BODY_BTM_EN",    "\n\nThank you.\n" .
                                     "\n" .
                                     "Galaxy\n");
define("PW_RQ_EMAIL_SUBJECT_JP",     "[Galaxy]パスワードリセットのお知らせ");
define("PW_RQ_EMAIL_BODY_TOP_JP",    "\n\nお客様のパスワードはリセットされました。\n" .
                                     "24時間以内に下記のパスワードでログインし、パスワードを変更してください。\n\n");
define("PW_RQ_EMAIL_BODY_BTM_JP",    "\n\n" .
                                     "Galaxy\n");
define("PW_RQ_EMAIL_HEADER",         "From: cdwadmin@stealthlab.net . \n . Reply-To: cdwadmin@stealthlab.net");

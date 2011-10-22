<?php

define("SALT_WORD",         "cool");
define("LOGIN_QUERY",       "select galaxyuserid from users where email = :EMAIL and password = :PASSWORD");
define("REGIST_USER_QUERY", "insert into users(firstname, lastname, email, password, zipcode, country, state, city, street, phonenumber) values (:FIRSTNAME, :LASTNAME, :EMAIL, :PASSWORD, :ZIPCODE, :COUNTRY, :STATE, :CITY, :STREET, :PHONENUMBER)");
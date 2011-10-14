<?php

class galaxyDbConnector
{
	// DB connect information
	const DOTCLOUD_DB_MYSQL_HOST     = "galaxydb-tora.dotcloud.com";
	const DOTCLOUD_DB_MYSQL_USER     = "galaxy_user";
	const DOTCLOUD_DB_MYSQL_PASSWORD = "cool";
	const DOTCLOUD_DB_MYSQL_DBNAME   = "galaxy";
	const DOTCLOUD_DB_MYSQL_PORT     = 15770;
	
	// save DB connection
	private static $_db_connection = null;
	
	// constructor
	private function __construct()
	{
		self::$_db_connection =  new mysqli(self::DOTCLOUD_DB_MYSQL_HOST,
		                                    self::DOTCLOUD_DB_MYSQL_USER,
		                                    self::DOTCLOUD_DB_MYSQL_PASSWORD,
		                                    self::DOTCLOUD_DB_MYSQL_DBNAME,
		                                    self::DOTCLOUD_DB_MYSQL_PORT);
		    
// TODO : consider the process in case of DB connect error
	   if (self::$_db_connection->connect_error != null) {
		    die("DB connect error! : [" . self::$_db_connection->connect_errno . "]" .
                                          self::$_db_connection->connect_error);
		}

		return self::$_db_connection;
	}
	
	// getting DB connection 
	public static function getConnection()
	{
		if (self::$_db_connection == null) {
			new self();
		}
		
		return self::$_db_connection;
	}
}

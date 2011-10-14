<?php
class galaxyDbConnector
{
	// save DB connection
	private static $_db_connection = null;
	
	// constructor
	private function __construct()
	{
		// get DB info from environment.json
		$filepath = $_SERVER['HOME'].'/environment.json';
		$env = json_decode(file_get_contents($filepath), true);
		
		// DB info is saved like "DOTCLOUD_DATA_MYSQL_xxx"
		$host = $env['DOTCLOUD_DATA_MYSQL_HOST'];
		$port = $env['DOTCLOUD_DATA_MYSQL_PORT'];
		$dbname = 'galaxy';
		$dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$dbname;
		$user = $env['DOTCLOUD_DATA_MYSQL_LOGIN'];
		$pass = $env['DOTCLOUD_DATA_MYSQL_PASSWORD'];

		try {
    		// create PDO
	    	self::$_db_connection = new PDO($dsn, $user, $pass);
		    self::$_db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch(PDOException $e) {
			die("DB connection failed : " . $e->getMessage());
		}
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

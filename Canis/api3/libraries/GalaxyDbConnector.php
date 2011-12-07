<?php

/**
* The class to connect to DB
*
* [method]
* + getConnection : The method to get connection with DB
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/config/Config.php";


class GalaxyDbConnector
{
    // save DB connection
    private static $_db_connection = null;
    
    // constructor
    private function __construct()
    {
    	// get DB info from environment.json
    	$env = $GLOBALS["env"];
        
        // DB info is saved like "DOTCLOUD_DATA_MYSQL_xxx"
//        $host = $env['DOTCLOUD_DATA_MYSQL_HOST'];
//        $port = $env['DOTCLOUD_DATA_MYSQL_PORT'];
//        $dbname = 'galaxy';
//        $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$dbname;
//        $user = $env['DOTCLOUD_DATA_MYSQL_LOGIN'];
//        $pass = $env['DOTCLOUD_DATA_MYSQL_PASSWORD'];

        $host = "galaxy-tora.dotcloud.com";
        $port = "16028";
        $dbname = 'galaxy';
        $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$dbname;
        $user = "root";
        $pass = "LosUTtSFuZzHt6x5c3z8";

        try {
            // create PDO
            self::$_db_connection = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "set character set `utf8`"));
            self::$_db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e) {
            ErrorLogger::write("DB connection failed.", $e);
        }
    }


    // getting DB connection 
    public static function getConnection()
    {
        if (self::$_db_connection == null) {
            new self();
        
        } else {
            
        }

        return self::$_db_connection;
    }
}

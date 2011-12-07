<?php

/**
* The script to watch the DB table records via browser
*
*/

require_once $_SERVER["DOCUMENT_ROOT"] . "/libraries/GalaxyDbConnector.php";


if (isset($_GET['load'])){
    $queries = array();
    $queries["users"] = "select * from users";
    $queries["items"] = "select * from items";
    $queries["images"] = "select * from images";
    $queries["messages"] = "select * from messages";
    $queries["geolocations"] = "select * from geolocations where zipcode = '1570062'";
    
    foreach($queries as $tableName => $query) {
        // DB connect
        $db_connection = GalaxyDbConnector::getConnection();
        
        // prepare SQL statement
        $stmt = $db_connection->prepare($query);
        $stmt->execute();
        $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
         
        // view file
        echo($tableName . "<br />");
        echo("<table border='1' style='width:800px'>");
        $count = 0;
        foreach ($queryResult as $row){
            if ($count == 0) {
                echo("<tr>");
                foreach ($row as $key => $value) {
                    echo("<td bgcolor='#CCCCCC'>");
                    echo($key);
                    echo("</td>");
                }
                echo("</tr>");
            
            } else {
                
            }
            
            echo("<tr>");
            foreach ($row as $key => $value) {
                echo("<td>");
                echo($value);
                echo("</td>");
            }
            $count++;
            echo("</tr>");
        }            
                                
        echo("</table><br />");
    }
    exit;

} else {
        
}
 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript">
function get(){
    $('#console').load('?load=1');
}
</script>
<title>Galaxy DB data</title>
</head>
<body>
<h2>Galaxy DB data</h2>
<input type="button" onclick="get()" value="get data">
<span id="status"></span><br /><br />
<div id="console"></div>
</body>
</html>
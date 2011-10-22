<?php

define("LOG_DIRECTORY", "/home/dotcloud/logs");
define("LINES_COUNT", 10);

if (isset($_GET['load'])){

	// PHP error log
	echo("<table border='1' style='width:800px'>");
	echo("<tr><td bgcolor='#CCCCCC'>PHP error log</td></tr>");
	echo("<tr><td>");
	
	// get DB info from environment.json
	$filepath = $_SERVER['HOME'].'/environment.json';
	$env = json_decode(file_get_contents($filepath), true);
	
	foreach (read_tail("/var/log/nginx/" . $env["DOTCLOUD_PROJECT"] . "-default-www-0.error.log", LINES_COUNT) as $i => $line){
		$line = rtrim($line,"\n");
		echo strtr(htmlspecialchars($line,ENT_QUOTES),array("\t" => '&nbsp;&nbsp;&nbsp;&nbsp;'));
		if ($i <(LINES_COUNT - 1)){
			echo '<br />';
		}
	}
	
	echo("</td></tr></table><br />");


    // open directory
    if ($dir = opendir(LOG_DIRECTORY)) {
    
        // read and view file
        while (($file = readdir($dir)) !== false) {
        	$fileExtension = null;
        	if (preg_match("/\./", $file)) {
        		$fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        	}

            if (($file != ".") && ($file != "..") && $fileExtension == "log") {
    
                // view file
                echo("<table border='1' style='width:800px'>");
                echo("<tr><td bgcolor='#CCCCCC'>" . $file . "</td></tr>");
                echo("<tr><td>");
    
                foreach (read_tail(LOG_DIRECTORY . "/" . $file, LINES_COUNT) as $i => $line){
                    $line = rtrim($line,"\n");
                    echo strtr(htmlspecialchars($line,ENT_QUOTES),array("\t" => '&nbsp;&nbsp;&nbsp;&nbsp;'));
                    if ($i <(LINES_COUNT - 1)){
                        echo '<br />';
                    }
                }            
                                
                echo("</td></tr></table><br />");
            }
            
        }
        
        closedir($dir);
    }

    exit;
}
 

function read_tail($file, $lines) {
    //global $fsize;
    $handle = fopen($file, "r");
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter> 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true;
                break;
            }
            $t = fgetc($handle);
            $pos--;
        }
        $linecounter--;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose ($handle);
    return array_reverse($text);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript">
var timer = null;
function start_tail(){
    if (timer){
        clearInterval(timer);
    }
    $('#status').html('running...');
    timer = setInterval(run, 5000);
}
 
function run(){
    $('#console').load('?load=1');
}
 
function stop(){
    clearInterval(timer);
    $('#status').empty();
}
</script>
<title>Galaxy logs</title>
</head>
<body>
<h2>Galaxy logs</h2>
<input type="button" onclick="start_tail()" value="TAIL">
<input type="button" onclick="stop()" value="STOP">
<span id="status"></span><br /><br />
<div id="console"></div>
</body>
</html>
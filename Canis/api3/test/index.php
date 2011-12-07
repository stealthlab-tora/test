<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>This is the list of test sources for Galaxy server side application</title>
</head>
<body>
<h2>List of Galaxy sources</h2>
<ol>
<?php
$top_dir = $_SERVER["DOCUMENT_ROOT"] . "/test";
viewDirectory($top_dir);

function viewDirectory($dir) {
    $drc = dir($dir);
    echo("<ul>\n");
    while ($temp_filename = $drc->read()) {
        if ($temp_filename == ".." || $temp_filename == "." || $temp_filename == "index.php") {
            continue;
            
        } else {
            
        }
        
        echo("<li>\n");
        
        $temp_filepath     = $dir . "/" . $temp_filename;
        $temp_fileurl      = str_replace($_SERVER["DOCUMENT_ROOT"], "http://galaxy-tora.dotcloud.com", $temp_filepath);
        
        if (is_dir($temp_filepath)) {
            echo($temp_filename . "/<br />\n");
            viewDirectory($temp_filepath);
        } else {
            echo("<a href=\"" . $temp_fileurl . "\">" . $temp_filename . "</a>\n");
        }
        
        echo("</li>\n");
    }
    echo("</ul>\n");
    $drc->close();
}
?>
</ol>
</body>
</html>
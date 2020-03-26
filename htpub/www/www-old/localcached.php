<?php
//error_reporting(E_ERROR|E_WARNING|E_PARSE);
error_reporting(E_ALL);
require_once("globals.php");

function satlin_local_copy ($file) {
    //$de ="ftp://satlin.local/mirror/".$file;
    $ad ="/var/www/html/mirror/".$file;
    if (1 or !file_exists($ad)) {
	if (!file_exists(dirname($ad))) {
		if (!mkdir(dirname($ad), 0777, true)) {
			echo "cannot make directory {dirname$ad}\n";
			exit();
		}
	}
    	$de ="mirror/".htmlspecialchars($file);
	$fp =fopen($ad, 'w');
	$cid =ftp_connect("ftp.satlin.local");
	$res =ftp_login($cid, "ftp", "ftp");
	ftp_fget($cid, $fp, $de, FTP_BINARY, 0);
	fclose($fp);
    } else {
	    echo "file exists ".$ad;
    }
    return true;
}

$file =$_GET["q"];
if (satlin_local_copy ($file)) {
	$url=$SITE["AKALI_DOMAIN"]."/mirror/".htmlspecialchars($file);
	//header('Refresh : 0; url=' . $SITE["AKALI_DOMAIN"]."/mirror/".htmlspecialchars($file));
} else {
	echo "Error : cannot create a cache...\n";
	echo "  Please contact me at luckxa0@google.com, to help improving this site.\n";
	//header('Refresh : 0; url=' . $SITE["AKALI_DOMAIN"]."/localmirror.php?q=".$file);
	exit();
}
?>
<html>
<head>
<meta http-equiv="refresh" content="0;url=<?=$url?>">
</head>
<body>
<p>Downloading...</p>
If download will not start: <a href="<?=$url?>">Click Me! <?=$url?></a>
</body>
</html>

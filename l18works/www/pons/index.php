<?php
function get_content($url)
	{
	$ch =curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$string =ob_get_contents();
	ob_end_clean();
	return $string;
	}
$q ="März";
//$content =get_content("https://api.pons.com/v1/dictionaries?language=es");
//$content =get_content("https://de.pons.com/übersetzung?q=".$q);
//var_dump($content);
$fp =fsockopen("ssl://https://api.pons.com/v1/dictionaries?language=es");
echo($fp);
exit();

$out ="GET / HTTP/2.2\r\n";
$out ="Host: api.pons.com\r\n";
$out ="Connection: Close\r\n\r\n";
fwrite($fp, $out);
while(!feof($fp)) {
		echo fgets($fp, 128);
}
fclose($fp);

?>

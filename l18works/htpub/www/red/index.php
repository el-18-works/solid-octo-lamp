<?php
require_once("red.php");
$addr = $_SERVER["REMOTE_ADDR"];
$host = $_SERVER["REMOTE_HOST"];
$agent = $_SERVER["HTTP_USER_AGENT"];
$text_de =$addr."; ".$host.$agent.".";
$saddr = $_SERVER["SERVER_ADDR"];
$shost = $_SERVER["SERVER_HOST"];
$soft = $_SERVER["SERVER_SOFTWARE"];
$sign = $_SERVER["SERVER_SIGNATURE"];
$sign =$shost.$soft;
$text_ad =$saddr."; ".$sign." ".php_uname().".";
?>
<html>
<head>
<?=head_minimum()?>
</head>
<body>
<p><small>Your signature: <?=$text_de?><br/>
Server signature: <?=$text_ad?></small></p>

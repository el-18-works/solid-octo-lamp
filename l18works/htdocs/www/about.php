<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
	//background : url("teddy.png");
	//background : url("www/mai-closeup-bg.png");
	background-repeat : no-repeat;
	//background-size : 100%;
	background-color : black;
}
a {
	color : #123224;
}
#myabout {
	position : fixed;
	left: 0px;
	right: 0px;
	top: 0;
//	max-width: 400px;
	max-height:100%;
	z-index : -1;
}
</style>
</head>
<body>
<center>
<video autoplay muted loop id="myabout">
<source src="res/myabout.ogv" type="video/ogg">
</video>
<?php
require_once("globals.php");
echo "<br/>";
echo "<br/>";
echo "<h3>令和二年に突入〜L１８ワークス再始動〜！！</h3>";
echo "<pre>";
echo "サーバ所在地\n";
echo "<a href=\"https://www.google.com/maps/place/2747+Shimoda,+Konan,+Shiga+520-3201/@35.0190847,136.1305759,82m/data=!3m1!1e3!4m5!3m4!1s0x6001640998034375:0x597481e311523e3b!8m2!3d35.0190803!4d136.1308495\">滋賀県湖南市下田2747-421</a>\n";
echo "こうばの寮です。\n";
echo "070-6567-6883\n";
echo "satlin_luckxa@yahoo.com\n";
echo "\n";
readfile("new-introduction.txt");
echo "\n";
echo "With love, 流華.\n";
//echo "With love, <a href=?q=inf>Luckxa</a>.\n";
if (isset($_GET["q"])) {
	echo "\n<i>";
	readfile("last-introduction.txt");
	echo "\n</i>";
}
echo "<a href=/>top page</a><br><br>";
echo "<h2>&nbsp;<a href=".$SITE["PROTO"]."luckxa.l18.work>私の日記</a></h2>";
//echo "<a href=".$SITE["PROTO"]."luckxa.".$SITE["HOST"].">日記</a>";
?>

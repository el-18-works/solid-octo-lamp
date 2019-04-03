<html>
<head>
<style>
body {
	//background : url("teddy.png");
	background : url("www/mai-closeup-bg.png");
	background-repeat : no-repeat;
	//background-size : 100%;
}
</style>
</head>
<body>
<?php
require_once("globals.php");
echo "<h3>いよいよ、L１８ワークスのプレ始動ですよ〜！！</h3>";
echo "<pre>";
echo "サーバ所在地\n";
echo "<a href=\"https://www.google.com/maps/place/1+Chome-6-37+Banba,+Hikone-shi,+Shiga-ken+522-0069/@35.2811985,136.2476957,17z/data=!4m5!3m4!1s0x60022b29001dc94d:0x5b491fed45f286c3!8m2!3d35.2811985!4d136.2498844\">滋賀県彦根市馬場一丁目６の三十七</a>\n";
echo "遊歩道側二階のピンクのカーテンの部屋が私の部屋です。\n";
echo "070-6567-6883\n";
echo "satlin_luckxa@yahoo.co.jp\n";
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

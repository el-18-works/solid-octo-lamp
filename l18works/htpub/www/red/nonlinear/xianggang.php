<?php
require_once("red.php");
?>
<html>
<head>
<?=head_minimum()?>
<style>
<?=css_minimum()?>
<?=css_motto("rgb(10,10,100)")?>
.all {
	padding : 4em;
}
h1 {
	font : bold 30px "PingFang HK";
}
.mirror-table {
	border-style : dotted;
}
.mirror-type {
	border-style : solid;
	border-width : 1px;
}

.mirror-link {
	padding : 6px;
	border-color : rgb(120,100,100);
	border-style : solid;
	border-width : 1px;
}
p {
	font-family :  Roboto;
}
a.link:link {
	font-family :  "AR PL UKai HK";
	color : rgb(100,120,120);
}
a.link:hover {
	background-color : rgb(200,200,200);
	text-decoration : underline;
}
body {
	background-color : rgb(250,200,210);
}
img {
	border-radius : 10px;
	border-style : solid;
	border-color : rgb(180,200,160);
	border-opacity : 50%;
	border-width : 5px;
}
</style>
</head>
<body lang="zh-HK">
<?php
	echo "<div class=all lang=zh-HK>";
	echo "<h1>香港文</h1>";
	echo "<p>閲讀和下載</p>";
	if (passage()) {
			echo "<center>";
			echo "<img src=sakura.png alt=sakura>";
			echo "<p>中日友好！！理解相互的文化！！</p>";
			echo "</center>";
	}
	echo "<ol>";
	echo "<li><h2> <a class=link href=香港文wd.html>在線閲讀</a></h2>日譯。";
	echo "<li><h2>下載</h2>準備資料等。";
	echo "<ul>";
	echo "<li><h3>原文和翻譯</h3>";
	echo "<p>公開/<a href='http://breezymove.blogspot.jp/2017/02/copyright-vs-copyleft.html'>著作傳開放</a>的版本</p>";
	$mj =new JsonMirror("xgw-wd-public.json");
	$mj->table();
	echo "<li><h3>参考</h3>";
	echo "<p>非公開/私密的資料</p>";
	if (passage()) {
			$mj =new JsonMirror("xgw-wd-private.json");
			$mj->table();
	} else {
			echo "<i>Authorized users only!</i><br>";
			echo "<i>Please login!</i>";
			echo "<h4>為什麼不公開？</h4>";
			echo "<p>我們主張的是如下的兩個點而已。</p>";
			echo "<p>&nbsp;一、什麼都作品是被流布的。</p>";
			echo "<p>&nbsp;一、什麼都人不要不讓不敢稱為作品的東西流布下作品的名字。</p>";
			echo login_form();
	}
	echo "</ul>";
	echo "</ol>";
	echo "</div>";
?>
<?=foot_minimum()?>
<body>

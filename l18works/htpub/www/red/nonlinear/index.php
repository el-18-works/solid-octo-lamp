<?php
require_once("red.php");
?>
<html>
<head>
<?=head_minimum()?>
<link href="manifesto.css" rel="stylesheet" type="text/css" />
<style>
<?=css_minimum()?>
<?=css_motto("rgb(10,10,100)")?>
a.menu:link {
	color : rgb(10,10,100);
}
a.menu:visited {
	color : rgb(10,10,100);
}
a.menu:hover {
	background-color : rgb(200,200,200);
	text-decoration : underline;
}
</style>
</head>
<body lang=la>
<?=$banner_nl?>
<?php
	echo "<center>";
	$js =new JsonMotto("nonlinear-manifesto.json", "manifesto");
	echo "<div class=manifesto>";
	echo "<h1 class=motto-title-manifesto>Invisible Fruits of Science</h1>";
	$js->text();
	echo "<div>";
	echo "<p><hr class=motto-hr-manifesto></p>";
	echo "<p><a class=menu href=xianggang.php>香港</a></p>";
	echo "<p><hr class=motto-hr-manifesto></p>";
	echo "<p><a class=menu href=vl.php>News Letter</a></p>";
	echo "<p><hr class=motto-hr-manifesto></p>";
	echo "<p></p>";
	$js->notes();
	echo "</center>";
?>
<?=foot_minimum()?>
<body>

<?php
require_once("satlin.php");
?>
<html>
<head>
<?=head_minimum()?>
<style>
<?= css_minimum() ?>
body { 
	margin : 6px;
}
.greeting {
	font-size : 20px;
	color : rgb(210,190,160);
	text-align : center;
}
img {
	height : 300px;
}
#yuri {
	position : fixed;
	left:0;
	top: 0;
//	max-width: 400px;
	min-height:100%;
}
.content {
	text-align : left;
	position : fixed;
	background : rgba(12,100,100,0.5);
	padding : 20px;
//	min-height : 2000px;
}
#from {
	text-align : right;
	color : rgb(220,120,150);
}
</style>
</head>
<body>
<video autoplay muted loop id="yuri">
<source src="yuri.mp4" type="video/mp4">
</video>
<div class="content">
<br><br>
<a href=loveletter.php>まいちゃんへ</a><br><br>
<a href=loveletter.php><img src=sh-lily/im2.png></a><br><br>
<p id=from>
070-6567-6883<br>
satlin_luckxa@yahoo.com<br>
流華より
</p>
</div>
</body>
</html>

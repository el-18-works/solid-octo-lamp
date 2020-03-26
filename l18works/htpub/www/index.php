<?php
require_once("globals.php");
$motto =new JsonMotto("satlin.motto.json");

if (local_agent()) {
	$site_title_color ="color : rgb(100,200,100);";
	$site_title_color1 ="color : rgb(100,200,100);";
	$site_title_color2 ="color : rgb(255,100,100);";
	$site_title_text1 =$SITE["NAME1_TEXT"]." [local]";
	$site_title_text2 =$SITE["NAME2_TEXT"]."";
} else {
	$site_title_color1 ="color : rgb(255,100,100);";
	$site_title_color2 ="color : rgb(100,200,100);";
	$site_title_text1 =$SITE["NAME1_TEXT"]."";
	$site_title_text2 =$SITE["NAME2_TEXT"]."";
}
$r =rand(10,100); $g =rand(10,100); $b =rand(10,100);
$rgb ="$r,$g,$b";
$opacity_de ="20%";
$opacity_ad ="100%";
?>
<html>
<head>
<?=head_minimum()?>
<style>
<?= $motto->css("rgb(190,170,190)") ?>
<?= css_minimum() ?>
body { 
	margin : 6px;
	background :url("akali/alice-bird.png") right top no-repeat;
/*
	background :url("akali/female-octocoder.png") right top no-repeat;
*/
	<?php printf( "background-color : rgb(%s);", $rgb); ?> 
}
.greeting {
	font-size : 20px;
	color : rgb(210,190,160);
	text-align : center;
}
.greeting-8 {
	font-size : 26px;
	color : rgb(130,110,90);
	text-align : center;
	vertical-align : middle;
}
.site-title {
	padding-left : 40%;
	padding-bottom : 10px;
	<?=$site_title_color1?>
}
#site-title1 {
	padding-left : 40%;
	padding-bottom : 10px;
	<?=$site_title_color1?>
}
#site-title2 {
	padding-left : 30%;
	padding-top : 30px;
	padding-bottom : 30px;
	<?=$site_title_color2?>
}
.site-greeting {
	width : 40%;
	padding-top :60px;
	padding-bottom :40px;
	padding-left : 0%;
	position : left;
	vertical-align : center;
	float : left;
	opacity : <?=$opacity_ad?>;
}
.site-motto {
	width : 40%;
	float : left;
	bottom-margin : 10em;
	opacity : <?=$opacity_de?>;
	//font-size : 90%;
}
.site-greeting:hover {
	background-color : rgba(20,20,20, 0.5);
}
.site-motto:hover {
	background-color : rgba(20,20,20, 0.3);
}
.motto-unit {
	margin : 2em;
	border-style :solid;
	border-width : 3px;
	border-color : rgba(10,10,10, 0);
	padding-left : 16px;
}
.motto-unit:hover {
	margin : 2em;
	border-style :dotted;
	border-width : 3px;
	border-color : rgba(200,100,10, 0.6);
	background-color : rgba(10,10,10, 0.6);
	border-radius : 10px;
}

@-webkit-keyframes fadeIn {
	to {
	background-color : rgb(10,10,10,0.9);
		border-radius : 40px;
		width : 60%;
		opacity : <?=$opacity_ad?>;
	}
}
@keyframes fadeIn {
	to {
	background-color : rgb(10,10,10,0.9);
		border-radius : 40px;
		width : 60%;
		opacity : <?=$opacity_ad?>;
	}
}
@-webkit-keyframes fadeOut {
	to {
	background-color : rgb(<?=$rgb?>,0);
		border-radius : 310px;
		width : 40%;
		opacity : <?=$opacity_de?>;
	}
}
@keyframes fadeOut {
	to {
	background-color : rgb(<?=$rgb?>,0);
		border-radius : 310px;
		width : 40%;
		opacity : <?=$opacity_de?>;
	}
}
.fade-in {
	-webkit-animation: fadeIn 1.0s ease-in 1 forwards;
	animation : fadeIn 1.0s ease-in 1 forwards;
	background-color : rgb(<?=$rgb?>,0);
	border-radius : 310px;
	width : 40%;
	opacity : <?=$opacity_de?>;
}
.fade-out {
	-webkit-animation: fadeOut 1.0s ease-in 1 forwards;
	animation : fadeOut 1.0s ease-in 1 forwards;
	background-color : rgb(10,10,10,0.9);
	border-radius : 40px;
	width : 60%;
	opacity : <?=$opacity_ad?>;
}
.is-paused {
	-webkit-animation-play-state: paused;
	animation-play-state: paused;
}
</style>
</head>
<body>
<?php
function greeting() {
	global $site_title_text2;
	echo "<div class=site-greeting>";
	echo "<p class=greeting-8><small>∞</small></p>";
	echo "<p class=greeting-8>∞ <small>8</small> ∞</p>";
	echo "<p class=greeting>¡Hola! Llamame <b>Satl&iacute;n Luckxa</b>, una matem&aacute;tica japon&eacute;sa,</p>";
	echo "<p class=greeting>nacida buddhista :</p>";
	echo "<p class=greeting>No creo nada. No creo en nada. Y no crezco nunca.</p>";
	echo "<p class=greeting>Soy como ocho motas de luz</p>";
	echo "<p class=greeting>en tus ojos.</p>";
	echo "<p class=greeting-8>∞ <small>8</small> ∞</p>";
	echo "<p class=greeting-8><small>∞</small></p>";
	echo "<h1 id=site-title2>$site_title_text2</h1>\n";
	echo "</div>\n";
}

function motto() {
	global $site_title_text1, $motto;
	echo "<div class=site-motto >\n";
	echo "<div class=site-title>\n";
	echo "<h1 id=site-title1>$site_title_text1</h1>\n";
	echo "</div>\n";
	echo "<h2 class=motto-title>&nbsp;&nbsp;MOTTO :</h2>\n";
	$motto->text();
	$motto->notes();
	echo "</div>\n";
}
greeting();
motto();
?>
<?=foot_minimum()?>
</body>
</html>
<script>
var el1 =document.querySelector('.site-greeting');
var el2 =document.querySelector('.site-motto');
el1.addEventListener('mouseover', function(e) {
	el1.classList.remove('fade-out');
	el1.classList.add('fade-in');
	if (el2.classList.contains('fade-in')) {
		el2.classList.remove('fade-in');
		el2.classList.add('fade-out');
	}
});
el2.addEventListener('mouseover', function(e) {
	el2.classList.remove('fade-out');
	el2.classList.add('fade-in');
	if (el1.classList.contains('fade-in')) {
		el1.classList.remove('fade-in');
		el1.classList.add('fade-out');
	}
});
el1.classList.add('fade-in');
</script>

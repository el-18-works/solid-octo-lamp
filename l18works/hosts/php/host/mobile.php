<?php
/*
 * mobile check.
 */
function my_test_mobile() // : bool
{
	$proto= $_SERVER["HTTPS"] == "on" ? "https" : "http";
	$dom =$_SERVER["SERVER_NAME"];
	$path =$_SERVER["PHP_SELF"];
	$base =basename($path);
	$pdir =dirname($path);
	$dir =basename($pdir);
	return preg_match('/(android)|(mobile)/i', $_SERVER["HTTP_USER_AGENT"]) ;
}
//XXX @host/mobile.php
function my_mobilenavi($mobile=true, $pc=true) {
	$proto= $_SERVER["HTTPS"] == "on" ? "https" : "http";
	$dom =$_SERVER["SERVER_NAME"];
	$path =$_SERVER["PHP_SELF"];
	$base =basename($path);
	$pdir =dirname($path);
	$dir =basename($pdir);
	echo "<style>";
	echo "body {";
	echo 	"margin : 0px;";
	echo "}";
	echo ".mobile-navi {";
	echo 	"margin : 0px;";
	echo 	"border : 0px;";
	echo 	"padding : 1px;";
	echo 	"color : #ddd;";
	echo 	"font-size : 14px;";
	echo 	"background-color : #afd;";
	echo 	"text-align : center;";
	echo 	"border-color : #f00;";
	echo "}";
	echo ".mobile-navi, a {";
	echo 	"text-decoration : none;";
	echo 	"color : #aaf;";
	echo "}";
	echo ".mobile-navi, a:hover {";
	echo 	"text-decoration : none;";
	echo 	"color : #faf;";
	echo "}";
	echo "</style>";
	if(!preg_match('/(android)|(mobile)/i', $_SERVER["HTTP_USER_AGENT"])) {
		if ($pc && $dir == "m") {
			$pcdir =dirname($pdir);
			$href ="${proto}://${dom}/${pcdir}";
			if ($base != "index.php")
				$href .="/${base}";
			echo '<div class="mobile-navi">';
			echo 	"<p><a href=\"${href}\">パソコンの方はこちら</a></p>";
			echo '</div>';
		}
	} else {
		if ($mobile && $dir != "m") {
			$href ="${proto}://${dom}/${pdir}/m";
			if ($base != "index.php")
				$href .="/${base}";
			echo '<div class="mobile-navi">';
			echo 	"<p><a href=\"${href}\">スマホの方はこちら</a></p>";
			echo '</div>';
		}
	}
}
?>

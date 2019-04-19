<?php
function my_noscriptalert($test=false, $die=false) {
	echo "<style>";
	echo "body {";
	echo 	"margin : 0px;";
	echo "}";
	echo ".noscript-alert {";
	echo 	"margin : 0px;";
	echo 	"padding : 12px;";
	echo 	"color : #ddd;";
	echo 	"font-size : 12px;";
	echo 	"background-color : #222;";
	echo 	"text-align : center;";
	echo "}";
	echo ".noscript-alert a {";
	echo 	"text-decoration : none;";
	echo 	"color : #aaf;";
	echo "}";
	echo ".noscript-alert a:hover {";
	echo 	"text-decoration : none;";
	echo 	"color : #faa;";
	echo "}";
	echo "</style>";
	if (!$test)
		echo "<noscript>";
	echo "<div class=noscript-alert>";
	echo "<h3>この頁ではJavaScriptを使っています</h3p>";
	echo "<p>JavaScriptを有効にしてください</p>";
	echo "<p>（スマホ）<a href='https://www.fancl.co.jp/check/setting_js.html'>スマートフォン JavaScriptの有効化</a></p>";
	echo "<p>（パソコン）<a href='https://support.google.com/adsense/answer/12654?hl=ja'>ブラウザで JavaScript を有効にする</a></p>";
	echo "</div>";
	if (!$test)
		echo "</noscript>";
	if ($die) die("＠Ｌ１８サイト");
}

/*
 *
 */
function my_nowloading_init($dur =1) {
	echo "function myNowloadingInit(dur =${dur}) {";
	echo 	"let e =document.createElement(\"div\");";
	echo 	"e.id =\"nowloading\";";
	echo 	"e.style.position =\"fixed\";";
	echo 	"e.style.height =\"100%\";";
	echo 	"e.style.width =\"100%\";";
	echo 	"e.style.cursor =\"progress\";";
	echo 	"e.style.backgroundColor =\"#000\";";
	echo 	"e.style.backgroundImage =\"url(https://ens.l18.work/res/misc/nowloading.gif)\";";
	echo 	"e.style.backgroundRepeat =\"no-repeat\";";
	echo 	"e.style.backgroundPosition =\"center center\";";
	echo 	"e.style.backgroundAttachment =\"fixed\";";
	echo 	"e.style.opacity =\"0.8\";";
	echo 	"e.style.transitionDuration =dur + \"s\";";
	echo 	"document.body.insertBefore(e, document.body.firstChild);";
	echo "}";
}

/*
 *
 */
function my_nowloading_fini($dur =1) {
echo "function myNowloadingFini(dur =${dur}) {";
	echo "let e =document.getElementById(\"nowloading\");";
	echo "e.style.opacity =\"0\";";
	echo "setTimeout( () => (document.body.removeChild(e)) , dur * 1000);";
echo "}";
}

/*
 *
 */
function my_nowloading($dur =1) {
	my_nowloading_init($dur);
	my_nowloading_fini($dur);
	echo "function myNowloading(dur =${dur}) {";
	echo	 "var p =new Promise(function(resolve, reject) {";
	echo		 "myNowloadingInit(dur);";
	echo		 "setTimeout( () => resolve(myNowloadingFini(dur)), 2000);";
	echo	 "});";
	echo "}";
}

?>

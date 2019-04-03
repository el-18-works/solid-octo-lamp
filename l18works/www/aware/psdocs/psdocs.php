<?php require_once("globals.php"); 
function css_psdocs() {
	return "a:link { color : rgb(200,100,100); background-color : rgb(244,244,244); }".
		"a.dsc1:link {color : rgb(100,200,100);}".
		"a.dsc2:link {color : rgb(200,200,100);}".
		"a.dsc3:link {color : rgb(200,100,100);}" .
		"a.dsc1:visited {color : rgb(100,140,100);}".
		"a.dsc2:visited {color : rgb(140,140,100);}".
		"a.dsc3:visited {color : rgb(140,100,100);}".
		"body {padding : 1em; }".
		".code {padding : 1em; margin : 2em; background-color : rgb(250,250,250);}";
}
?>

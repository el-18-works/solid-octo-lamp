<html>
<head>
<style>
body {
	margin:0px;

	background-color:black;
}
#navi {
	margin:0px;
	width:100%;
	background-color:#121212;
}
#centro {
	opacity:0;
	border:0px;
	margin:0px;
	position:absolute;
	z-index:2;
	cursor:col-resize;

	transition:opacity 0.3s ease-in-out;
	background-color:black;
}
#cont {
	margin:0px;
	border:0px;
	padding:0px;
	width:100%;
	height:100%;

/*
	opacity:0.5;
*/
	background-color:gray;
}
.externo {
	position:absolute;
	margin:0px;
	bottom:0px;
}
.interno {
	position:relative;
	width:100%;
	height:100%;
	background-color:black;
}
.izquierda {
	opacity:0.8;
	float:left;
	left:8px;
	background-color:red;
}
.derecha {
	opacity:1;
	float:right;
	right:8px;
	background-color:#151515;
}
#editor {
	font-family : monospace;
	font-size : 15px;
}
#mostrador {
	background-color : #151515;
	opacity : 1;
	transition-duration : 0.5s;
}
#refmenu-circle {
	stroke : rgba(2,10,2,1);
	fill : none;
	transition-duration : 1s;
}
#refmenu-path {
	fill : rgba(2,10,2,1);
	transition-duration : 1s;
}
#colorear {
	border-color : rgba(2,10,2,1);
	background-color:#121212;
}
.menudiv {
	transition-duration : 0.5s;
}
.menudiv:hover {
	background-color:#000;
}
</style>
<script src="https://ens.l18.work/link/acemodule/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
/*
	Declaración previa.

 */
var editor;

function preparaciónEstilo() {
	const naviH =40;
	const centroW =9;
	const contMarg =8;
	var winH =window.innerHeight;
	var eNavi =document.getElementById("navi");
	var navi =eNavi.style;
	var cont =document.getElementById("cont").style;
	var seno =document.getElementById("seno").style;
	var coseno =document.getElementById("coseno").style;
	var eEditor =document.getElementById("editor");
	var editor =eEditor.style;
	var eCentro =document.getElementById("centro")
	var centro =eCentro.style;
	var winW =window.innerWidth;
	navi.height =naviH;
	cont.height =winH-naviH;
	centro.height =winH-naviH-contMarg;
	centro.width =centroW;
	coseno.top =seno.top =naviH;
	coseno.bottom =seno.bottom =contMarg;
	function desplazo(left) {
		seno.right =winW-left;
		centro.left =left;
		coseno.left =left+centroW;
	}
	desplazo((winW-centroW)/2);
	function desplazarRaton(e) {
		if (e.x>centroW && e.x<winW-centroW) 
			desplazo(e.x-centroW/2);
	}
	function  desplazadoRaton(e) {
		console.log("desplazado");
		centro.opacity =0;
		window.removeEventListener("mousemove", desplazarRaton);
		document.getElementById("mostrador").removeEventListener("mousemove", desplazarRaton);
	}
	eNavi.onmousedown = () => {
		centro.opacity =1;
		window.addEventListener("mousemove", desplazarRaton);
		document.getElementById("mostrador").addEventListener("mousemove", desplazarRaton);
		document.getElementById("mostrador").addEventListener("mouseup", desplazadoRaton);
	}
	window.onmouseup = () => {
		centro.opacity =0;
		window.removeEventListener("mousemove", desplazarRaton);
		document.getElementById("mostrador").removeEventListener("mousemove", desplazarRaton);
	}
	document.getElementById("mostrador").onmouseup = () => {
		centro.opacity =0;
		window.removeEventListener("mousemove", desplazarRaton);
	}
	window.onresize =preparaciónEstilo;
}

function reflejo() {
	let id =editor.session.getMode().$id;
	if (id == "ace/mode/html" || id == "ace/mode/svg") {
		let most =document.getElementById("mostrador");
		let texto ="";
		for(let i=0; i<editor.session.getLength(); i++) {
			texto +=editor.session.getLine(i);
		}
		mostrador.srcdoc =texto;
	}
}

function inicio(modo) {
	console.log("inicio");
	preparaciónEstilo();
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/"+modo);
    //editor.session.setMode("ace/mode/javascript");
	console.log(editor);
	editor.focus();
	console.log("inicio hecho");
	reflejo();
}

function código(f, src) {
	console.log("código");
	window.document.title ="sourcecode:"+f;
	var eEditor =document.getElementById("editor");
	eEditor.innerHTML =src;
	console.log("código hecho");
}

</script>

</head>
<body>
<div id=navi width="100%" style="vertical-align:center;">
	<div height=100% width=30px style="verticalAlign:center;margin:0px;float:left;" id="coloreardiv" class="menudiv" >
		<input type="color" id="colorear" value=#151515 style="margin:8px;" />
	</div>

	<div height=40px width=40px style="verticalAlign:center;margin:0px;border:0px;padding:0px;float:left;" id="reflejardiv" class="menudiv" >
		<svg height=30px width=30px style="margin:5px;float:left;" id="reflejar" >
			<circle cx=15 cy=15 r=10 stroke="rgba(2,10,2,1)" stroke-width=3 fill="none" class="refmenu" id="refmenu-circle" />
			<path d="M22 15 L11.5 21 L11.5 9 Z" class="refmenu" id="refmenu-path" />
		</svg>
	</div>
</div>
<div id=cont>
<div id=centro> </div>
	<div class ="externo izquierda" id=seno>
		<div class ="interno" id=editor></div>
	</div>
	<div class ="externo derecha" id=coseno>
		<iframe class="interno" id=mostrador></iframe>
	</div> 
</div>
<script>
var refmenu =document.getElementById("reflejar");
var colmenu =document.getElementById("colorear");
refmenu.onclick = () => {
	let rc =document.getElementById("refmenu-circle");
	let rp =document.getElementById("refmenu-path");
	rc.style.stroke ="rgba(200,20,20,0.5)";
	rp.style.fill ="rgba(200,20,20,0.5)";
	setTimeout( () => {
		rc.style.stroke ="rgba(2,10,2,1)";
		rp.style.fill ="rgba(2,10,2,1)";
	}, 1000);
	reflejo();
}
colorear.onchange = () => {
	let mr =document.getElementById("mostrador");
	let coseno =document.getElementById("coseno");
	let cont =document.getElementById("cont");
	mr.style.backgroundColor =colmenu.value;
	coseno.style.backgroundColor =colmenu.value;
	cont.style.backgroundColor =colmenu.value;
}
<?php 
$mode ="";
if (isset($_REQUEST["mode"])) {
	$mode =strtolower($_REQUEST["mode"]);
}
if (isset($_REQUEST["file"])) {
	$file =$_REQUEST["file"];
	$src =(file_get_contents($file));
} elseif (isset($_REQUEST["code"])) {
	$file ="";
	$src =($_REQUEST["code"]);
} else {
	$file ="";
	$src ="";
}
if ($mode == "" && $file != "") {
	$pos =strrpos($file, ".");
	if ($pos > 0) {
		$len =strlen($file);
		$ext =substr($file, $pos+1);
		if ($ext) $mode =$ext;
	}
}
if ($mode == "c" || $mode == "cpp" || $mode == "cxx" || $mode == "h" || $mode == "hpp" || $mode == "hxx" || $mode == "") {
	$mode ="c_cpp";
} elseif ($mode == "js" ) {
	$mode ="javascript";
} elseif ($mode == "py" ) {
	$mode ="python";
} elseif ($mode == "htm" ) {
	$mode ="html";
}
$a =array();
foreach( explode("\n", $src) as $l ) {
	$a[] =addslashes(htmlentities($l));
}
$src =implode("\\n", $a);
echo 'código("'.$file.'", "'.$src.'");';
echo "inicio('".$mode."');";
?>
</script>
</body>
</html>

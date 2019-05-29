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
	cursor:col-resize;
}
#centro {
	opacity:0;
	border:0px;
	margin:0px;
	position:absolute;
	z-index:2;

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
	cursor : default;
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

function indicar(t) {
	let most =document.getElementById("mostrador");
	let indicador =document.getElementById("indicador");
	indicador.innerHTML =t == undefined ? most.style.backgroundColor : t;
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
	editor.focus();
	indicar();
}

function inicio(modo) {
	preparaciónEstilo();
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/"+modo);
    //editor.session.setMode("ace/mode/javascript");
	console.log(editor);
	indicar();
	editor.focus();
}

function código(f, src) {
	window.document.title ="sourcecode:"+f;
	var eEditor =document.getElementById("editor");
	eEditor.innerHTML =src;
}

</script>

</head>
<body>
<div id=navi width="100%" style="vertical-align:center;">
	<div height=100% width=40px style="vertical-align:center;margin:0px;float:left;" id="coloreardiv" class="menudiv" >
		<input type="color" id="colorear" value=#151515 style="margin:8px;" />
	</div>

	<div height=40px width=40px style="margin:0px;border:0px;padding:0px;float:left;" id="reflejardiv" class="menudiv" >
		<svg viewBox="0 0 300 300" width=40px height=40px id="reflejar" >
			<circle cx=150 cy=150 r=100 stroke="rgba(2,10,2,1)" stroke-width=12 fill="none" class="refmenu" id="refmenu-circle" />
			<path d="M220 150 L115 210 L115 90 Z" class="refmenu" id="refmenu-path" />
		</svg>
	</div>
	<div height=100% width=40px style="vertical-align:center;margin:0px;border:0px;padding:10px;float:right;color:rgba(200,210,200,0.5)" id="indicador" class="menudiv" >
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
var indmenu =document.getElementById("indicador");
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
	let most =document.getElementById("mostrador");
	mr.style.backgroundColor =colmenu.value;
	coseno.style.backgroundColor =colmenu.value;
	cont.style.backgroundColor =colmenu.value;
	indicar();
}

indmenu.onclick = () => {
	let indicador =document.getElementById("indicador");
	let s =indicador.innerHTML;
	if (s.length > 1 && s[0] == "r") {
		let t =s.replace(/rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i, (match, p1, p2, p3) => { 
			q1= Number(p1).toString(16).toUpperCase(); 
			q2= Number(p2).toString(16).toUpperCase(); 
			q3= Number(p3).toString(16).toUpperCase(); 
			if (q1.length == 1) q1 ="0"+q1;
			if (q2.length == 1) q2 ="0"+q2;
			if (q3.length == 1) q3 ="0"+q3;
			return "#"+q1+q2+q3;
		});
		indicar(t);
	} else {
		let t =s.replace(/#(..)(..)(..)/, (match, p1, p2, p3) => {
    		return "rgb("+parseInt(p1,16)+", "+parseInt(p2,16)+", "+parseInt(p3,16)+")";
		});
		indicar(t);
	}
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

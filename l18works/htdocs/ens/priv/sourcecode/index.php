<html>
<head>
<script src="/link/bdf.js"></script>
<script src="modlst.js"></script>
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

function seleccionar(modo) {
	selmenu.innerHTML =modo;
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
	seleccionar(modo);
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

<!--
	<div height="100%" width="50px" style="margin:0px;border:0px;padding:0px;float:right;color:rgba(200,210,200,0.5)" id="selector" class="menudiv" >
		<svg viewBox="0 0 300 300" width="240px" height="40px" style="text-anchor:middle" id="seleccionar" >
		    <text x=150 y=175 style="font-family:sans; font-size:100; text-shadow:300 300 4 rgb(255,255,89); fill:#feefee; stroke:#53afe8; stroke-width:3;" id="seleccionado">
    		</text>
		</svg>
	</div>
-->
	<div height="100%" width="50px" style="margin:0px;border:0px;padding:10px;float:right;color:rgba(200,210,200,0.5)" id="selector" class="menudiv" >
	</div>
	<div height=100% width=40px style="vertical-align:center;margin:0px;border:0px;padding:10px;float:right;color:rgba(200,210,200,0.5)" id="indicador" class="menudiv" >
	</div>
</div>
<div id=cont>
<div id=centro> </div>
	<div class ="externo izquierda" id="seno">
		<div class ="interno" id="editor"></div>
	</div>
	<div class ="externo derecha" id="coseno">
		<iframe class="interno" id="mostrador"></iframe>
	</div> 
</div>
<script>
var selmenu =document.getElementById("selector");
var refmenu =document.getElementById("reflejar");
var colmenu =document.getElementById("colorear");
var indmenu =document.getElementById("indicador");

function bdfrect(s, call) {
	console.log(s);
	var svg =document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var i;
	for(i=0; i<s.length; ++i) {
		k8x12(s.charCodeAt(i), (x,y) => {
			//console.log("x y",x,y);
			var rect =document.createElementNS("http://www.w3.org/2000/svg", "rect");
			rect.setAttribute("x", x+i*8);
			rect.setAttribute("y", y+1);
			rect.setAttribute("width", 1); 
			rect.setAttribute("height", 1); 
			rect.style.fill ="#effaef";
			svg.appendChild(rect);
		});
	}
	svg.setAttribute("viewBox", "0 0 "+(i*8)+" 14");
	call(svg, i*8, 14);
	return svg;
}

selmenu.onclick = () => {
	if (document.getElementById("dialogo") != null) {
		return;
	}
	let dialogo =document.createElement("div");
	dialogo.id ="dialogo";
	dialogo.style.width ="80%";
	dialogo.style.height ="80%";
	dialogo.style.position ="fixed";
	dialogo.style.top ="10%";
	dialogo.style.left ="10%";
	dialogo.style.zIndex ="500";
	dialogo.style.backgroundColor ="rgba(01,02,01,0.8)";
	dialogo.style.borderRadius ="1% 1%";
	dialogo.style.border="10%"
	dialogo.style.borderColor ="#effaef";
	dialogo.style.borderStyle ="solid";
	dialogo.style.transition ="opacity 0.5s ease-in-out";
	dialogo.style.opacity="0"
	document.body.appendChild(dialogo);
	var cont =document.getElementById("cont");
	var navi =document.getElementById("navi");
	setTimeout( () => {
		dialogo.style.opacity ="1.0";
		dialogo.focus();
		function f() {
			dialogo.style.opacity ="0.0";
			cont.removeEventListener("click", f);
			navi.removeEventListener("click", f);
			window.removeEventListener("resize", f);
			window.removeEventListener("keydown", key);
			window.removeEventListener("keyup", keyup);
			setTimeout( () => {
				document.body.removeChild(dialogo);
			}, 500);
		}
		var shiftKey =false;
		function keyup(e) {
			if (e.code == "RightShift" || e.code == "LeftShift")
				shiftKey =false;
		}
		function key(e) {
			console.log(e.code);
			console.log(e.shifKey);
			if (e.code == "RightShift" || e.code == "LeftShift")
				shiftKey =true;
			else if (e.code == "Escape")
				f();
			else if (e.code == "Tab")
				movstar(shiftKey ? -1 : 1);
			else if (e.code == "ArrowDown")
				movstar(2);
			else if (e.code == "ArrowUp")
				movstar(-2);
			else if (e.code == "ArrowRight")
				movstar(1);
			else if (e.code == "ArrowLeft")
				movstar(-1);
			else if (e.code == "Enter" || e.code == "Space") {
				setTimeout( () => {
					inicio(starpos[curstar][0]);
					f();
				}, 500);
			}
			e.preventDefault();
		}
		cont.addEventListener("click", f);
		navi.addEventListener("click", f);
		window.addEventListener("resize", f);
		window.addEventListener("keydown", key);
		window.addEventListener("keyup", keyup);
		let s ="十字きーでもーどを選択してから、エンターきーを押してください。";
		bdfrect(s, (svg, w, h) => {
			var div1 =document.createElement("div");
			svg.style.width =w*2 + "px";
			svg.style.height =h*2 + "px";
			div1.style.width =w*2 + "px";
			div1.style.height =h*2 + "px";
			div1.style.position ="relative";
			div1.style.top ="10px";
			div1.style.left ="10px";
			div1.appendChild(svg);
			dialogo.appendChild(div1);
		});
		var div =document.createElement("div");
		div.style.width=dialogo.clientWidth-10;
		div.style.height=dialogo.clientHeight-50;
		div.style.position="absolute";
		div.style.top=50;
		div.style.zIndex=700;
		div.style.overflowY ="scroll";
		div.id ="modlst-vista";
		dialogo.appendChild(div);
		var starpos =[];
		var curstar =0;
		for(let i=0; i<acemode.length; ++i) {
			bdfrect(acemode[i], (svg, w, h) => {
				var div1 =document.createElement("div");
				div1.id ="svg-" + acemode[i];
				svg.style.width =w*2 + "px";
				svg.style.height =h*2 + "px";
				div1.style.width =w*2 + "px";
				div1.style.height =h*2 + "px";
				div1.style.position ="absolute";
				div1.style.top =(Math.floor(i/2)*40);
				console.log( (i/2)*40);
				if (i%2 == 0) {
					div1.style.left =30;
				} else {
					div1.style.left =div.clientWidth/2 + 30;
				}
				div1.appendChild(svg);
				div.appendChild(div1);
				starpos.push([acemode[i], div1.style.top]);
			});
		}
		var stardiv;
		bdfrect("＊", (svg, w, h) => {
			var div1 =document.createElement("div");
			div1.id ="star";
			svg.style.width =w*2 + "px";
			svg.style.height =h*2 + "px";
			div1.style.width =w*2 + "px";
			div1.style.height =h*2 + "px";
			div1.style.position ="absolute";
			div1.style.top =0;
			div1.style.left =10;
			div1.appendChild(svg);
			div.appendChild(div1);
			stardiv =div1;
		});
		function movstar(amnt) {
			let i=(starpos.length+curstar+amnt)%starpos.length;
			let sp =starpos[i];
			console.log(i, sp);
			stardiv.style.top =sp[1];
			if (i%2 == 0) {
				stardiv.style.left =10;
			} else {
				stardiv.style.left =div.clientWidth/2 + 10;
			}
			let curdiv =document.getElementById("svg-" + acemode[i]);
			// fallback...
			//curdiv.scrollIntoView(false);
			div.scrollBy(0,(curdiv.offsetTop-div.scrollTop)-div.clientHeight/2);
			curstar =i;
			div.focus();
		}
		let mod =document.getElementById("selector");
		let curmod =mod.innerHTML;
		for(let i =0; i<starpos.length; ++i) {
			if (starpos[i][0] == curmod) {
				movstar(i);
				break;
			}
		}
	}, 100);
}

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

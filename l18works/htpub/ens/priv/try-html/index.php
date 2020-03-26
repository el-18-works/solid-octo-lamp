<?php
?>
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

	background-color:green;
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
	opacity:0.5;
	margin:0px;
	border:0px;
	padding:0px;
	width:100%;
	height:100%;

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
	opacity:0.8;
	float:right;
	right:8px;

	background-color:green;
}
#editor {
	font-family : monospace;
	font-size : 15px;
}
</style>
<script src="https://ens.l18.work/link/acemodule/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
function preparaciónEstilo() {
	const naviH =40;
	const centroW =9;
	const contMarg =8;
	var winH =window.innerHeight;
	var navi =document.getElementById("navi").style;
	var cont =document.getElementById("cont").style;
	var seno =document.getElementById("seno").style;
	var coseno =document.getElementById("coseno").style;
	var eEditor =document.getElementById("editor");
	var editor =eEditor.style;
	//var editor =document.getElementById("editor").style;
	//var pagina =document.getElementById("página").style;
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
	eCentro.onmousedown = () => {
		centro.opacity =1;
		window.addEventListener("mousemove", desplazarRaton);
	}
	window.onmouseup = () => {
		centro.opacity =0;
		window.removeEventListener("mousemove", desplazarRaton);
	}
	window.onresize =preparaciónEstilo;
	return;

	navi.style.margin="0px";
	//navi.style.width=winW-20;
	//navi.style.boxShadow ="-2px 2px 5px grey";
	navi.style.height="30px";
	navi.innerHTML ="navi";
	navi.style.border ="1px solid red";
	//navi.style.backgroundColor ="black";
	//cont.innerHTML ="cont";



	editor.style.position="relative";
	editor.style.width=winW/2;

	coseno.style.position="absolute";
	seno.style.position="absolute";
	seno.style.width =coseno.style.width ="50%";
	//seno.style.width =coseno.style.width =winW/2;
	seno.style.left ="0px";
	seno.style.top ="30px";
	coseno.style.right ="0px";
	coseno.style.top ="30px";
	//seno.style.width =coseno.style.width ="50%";
	//seno.height="100%";
	//coseno.height="100%";
	seno.style.margin="0px";
	//seno.style.border="1px solid blue";
	//seno.style.backgroundColor="blue";
	coseno.style.backgroundColor="yellow";
	//coseno.style.border="1px solid blue";
	coseno.style.margin="0px";
	//seno.style.boxShadow="2px 2px 3px #344";
	//coseno.style.boxShadow="2px 2px 3px #344";
	seno.innerHTML ="seno";
	coseno.innerHTML ="coseno";
}

function inicio() {
	preparaciónEstilo();
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/svg");
    //editor.session.setMode("ace/mode/javascript");
	console.log(editor);
	editor.focus();
}

</script>

</head>
<body onload="inicio()">
<div id=navi width="100%"></div>
<div id=cont>
<?php /*
		<div class ="interno izquierda" id=editor></div>
		<iframe class ="interno derecha" id=página></iframe>
*/ ?>
<div id=centro>
</div>
	<div class ="externo izquierda" id=seno>
		<div class ="interno" id=editor></div>
	</div>
	<div class ="externo derecha" id=coseno>
	</div> 
</div>
</body>
</html>

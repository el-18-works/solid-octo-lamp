<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
body { margin : 0px; }
canvas { background-color: #013114; }
</style>
</head>
<body onload="startGame()">
<script>

var myNaipe;

function startGame() {
    myMesa.start();
	myNaipe =[new componente(40, 40, "rgba(0, 0, 255, 0.5)", 40, 30)];
	myNaipe.push(new componente(40, 40, "rgba(0, 255, 0, 0.5)", 40, 50));
}

var myMesa = {
    lienzo : document.createElement("canvas"),
    start : function() {
		this.lienzo.style.margin = "0px";
		this.lienzo.style.top = "0px";
		this.lienzo.style.border = "0px";
		this.lienzo.style.position = "absolute";
		this.lienzo.style.width = "100%";
		this.lienzo.style.height = "100%";
		this.lienzo.width =window.innerWidth/2;
		this.lienzo.height =window.innerHeight/2;
        this.contexto = this.lienzo.getContext("2d");
        this.contexto.shadowColor ="#000000";
        this.contexto.shadowOffsetX =3;
        this.contexto.shadowOffsetY =3;
        this.contexto.shadowBlur =30;
        this.contexto.canvas.onmousedown = (e) => {
			console.log(e);
			this.contexto.canvas.addEventListener("mousemove", this.raton);
		}
        this.contexto.canvas.onmouseup = (e) => {
			this.contexto.canvas.removeEventListener("mousemove", this.raton);
		}
        document.body.insertBefore(this.lienzo, document.body.childNodes[0]);
		this.intervalo =setInterval(actualizarMesa, 20);
    },
	raton : function(e) {
		console.log(e);
		myNaipe[1].x =e.x/2;
		myNaipe[1].y =e.y/2;
	},
	limpiar : function() {
		this.contexto.clearRect(0, 0, this.lienzo.width, this.lienzo.height);
	}
}

function componente(anchura, altura, color, x, y) {
	this.anchura =anchura;
	this.altura =altura;
	this.x =x;
	this.y =y;
	this.actualizar =function() {
		ctx =myMesa.contexto;
		ctx.fillStyle =color;
		ctx.fillRect(this.x, this.y, this.anchura, this.altura);
	}
}

function actualizarMesa() {
	myMesa.limpiar();
	//myNaipe[0].x +=1;
	for (let i=0; i<myNaipe.length; ++i)
		myNaipe[i].actualizar();
}

</script>
</body>
</html>


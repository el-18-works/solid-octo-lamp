<html>
<head>
<script src="cartes-web.js"></script>
<script src="cartes-medium.js"></script>
<script src="cartes-light.js"></script>
<style>
.c {
	margin : 3px 3px 3px 3px;
	position : fixed;
	display : none;
	opacity : 0;
	transition : 1.6s opacity, top 0.8s ease-out, left 0.8s ease-out, z-index 0.1s 0.4s ease-in-out;
	cursor : grab;
}
</style>
</head>
<body style="background-color:#121232; margin:2px">
<script>
	var bruit = {
		gauche : [],
		droite : [],
		loaded : 0,
		donner : function()  {
			for(let i=0; i<36; ++i) {
				bruit.gauche.push(document.querySelector("#bruits-gauche"+i));
				bruit.gauche[i].volume=0.2;
			}
			for(let i=0; i<36; ++i) {
				bruit.droite.push(document.querySelector("#bruits-droite"+i));
				bruit.droite[i].volume=0.2;
			}
		},
		produire : function(g, d) {
			try {
				bruit.loaded =(bruit.loaded+1)%36;
				bruit.gauche[bruit.loaded].volume =g;
				bruit.droite[bruit.loaded].volume =d;
				bruit.gauche[bruit.loaded].play();
				bruit.droite[bruit.loaded].play();
			} catch (e) {
				console.log("bruit produire ", e);
			}
		}
	}
</script>
<script>
	//var avail =screen.availWidth;
	var avail =window.innerWidth;
	if (avail < lightGrandeur[0]*10) {
		alert("not enough width available...");
	}
	if (avail-20 < mediumGrandeur[0]*10) {
		var paquet =light; var grandeur =lightGrandeur;
		console.log("light; avail=%d, gnd=%d", avail, grandeur[0]);
	} else if (avail-20 < webGrandeur[0]*10) {
		var paquet =medium; var grandeur =mediumGrandeur;
		console.log("medium; avail=%d, gnd=%d", avail, grandeur[0]);
	} else {
		var paquet =web; var grandeur =webGrandeur;
		console.log("web; avail=%d, gnd=%d", avail, grandeur[0]);
	}

	var menage = {
		plusgrand : 0,
		preparer : (talon) => {
			menage.nettoyer((plusgrand) => {
				let keys =talon.keys();
				for(let i =keys.next(); ! i.done; i=keys.next()) {
					document.querySelector("#c"+talon[i.value]).style.zIndex +=plusgrand;
				}
			});
		},
		nettoyer : (rappel) => {
			var f =(talon) => {
				let keys =talon.keys();
				let plusgrand =0;
				for(let i =keys.next(); ! i.done; i=keys.next()) {
					document.querySelector("#c"+talon[i.value]).style.zIndex =plusgrand++;
				}
				menage.plusgrand =Math.max(menage.plusgrand, plusgrand);
			}
			menage.plusgrand =0;
			f(pioche.talon);
			for(let i=0; i<10; ++i) {
				f(famille[i].talon);
			}
			if (rappel != undefined)
				rappel(menage.plusgrand);
		},
		sup : function(cid) {
			document.querySelector("#c"+cid).style.zIndex =++menage.plusgrand;
			return menage.plugrand;
		}
	}

	var pioche = {
		talon : [],
		pos : [],
		donner : function() {
			let bx =avail*0.98;
			let marge =grandeur[0]*0.02;
			let largeur =Math.max((bx-marge)/10, grandeur[0])+marge;
			let hauteur =(avail/10-grandeur[0])/2;
			pioche.pos =[largeur/2, hauteur];
		},
		distribuer : function() {
			console.log("pioche distribuer");
			alert("pioche distribuer");
			console.log(pioche);
			menage.nettoyer();
			var f =(i) => {
				let fid =((i)*3)%10;
				pioche.depart(famille[fid].arrivee);
				famille[fid].produire(200);
				if (i<53) {
					setTimeout(() => f(i+1), 200);
				} else {
					setTimeout(() => { for(let i=0; i<10; ++i)  famille[i].rectifier(); }, 2000);
				}
			}
			f(0);
			menage.preparer(pioche.talon);
		},
		depart : function(arrivee) {
			let cid =pioche.talon.pop();
			menage.sup(cid);
			arrivee(cid);
		},
		arrivee : function(cid,div) {
			let no =pioche.talon.length;
			pioche.talon.push(cid);
			x = pioche.pos[0]-no/24 + Math.random()*1-0.5;
			y = pioche.pos[1]-no/12 + Math.random()*1.1-0.5;
			r =Math.random() * 0.36*2;
			div.style.left =x;
			div.style.top =y;
			div.style.transform ="rotate("+r+"deg)";
			div.style.display ="block";
			div.style.opacity =1.0;
			div.style.zIndex =no;
		}
	}
	var defausse = {
		pos : [],
		donner : function() {
			let bx =avail*0.9;
			let marge =grandeur[0]*0.03;
			let largeur =Math.max((bx-marge)/10, grandeur[0])+marge;
			let hauteur =grandeur[1]*0.2;
			for(let i=2; i<10; ++i) {
				let p =[(avail-bx)/2 + largeur*i, hauteur];
				pioche.pos.push(p);

				let div =document.querySelector("#d"+(i-2));
				div.style.display ="block";
				div.style.opacity =1.0;
				div.style.left =p[0];
				div.style.top =p[1];
				div.style.width =grandeur[0];
				div.style.height =grandeur[1];
				div.style.backgroundColor ="rgba(1,1,1,0.01)";
				div.style.borderRadius ="15% 15%";
				div.style.boxShadow ="1px 1px 5px 5px rgba(1,20,20,0.2) inset";
			}
		}
	}

	var famille =[];
	for(let i=0; i<10; ++i) {
		famille.push({
			id : i,
			pos : [],
			talon : [],
			cid : -1,
			donner : () => {
				let bx =avail*0.99;
				let marge =grandeur[0]*0.03;
				let largeur =Math.max((bx-marge*2)/10, grandeur[0]/10);
				//let hauteur =grandeur[1]*1.1;
				let hauteur =grandeur[1]+(avail/10-grandeur[0]);
				let p =[(avail-bx)/2 + largeur*i, hauteur];
				famille[i].pos =p;
			},
			produire : (t) => {
				let g =0.1*i;
				let d =1-(0.1*i);
				setTimeout(() => bruit.produire(g,d), t);
			},
			arrivee : function(cid) {
				let div =document.querySelector("#c"+cid);
				console.log("famille arrivee ",i,cid,div);
				let z=famille[i].talon.push(cid);
				let ecart =grandeur[1]/8;
				let pos =famille[i].pos;
				div.style.left =pos[0];
				div.style.top =pos[1] + ecart*z;
				famille[i].cid =cid;
				//div.style.zIndex =i+famille[i].talon.length;
				//famille[0].sup(div);
				//console.log("arrivee",z,div.style.zIndex);
			},
			rectifier : () => {
				if ( famille[i].cid >= 0 )
					recto(famille[i].cid);
			}
		});
	}

	var verso =(cid, arrivee) => {
		let div =document.querySelector("#c"+cid);
		div.innerHTML =paquet[52];
		if (arrivee)
			arrivee(cid, div);
	}
	var recto =(cid, arrivee) => {
		//console.log(cid);
		let div =document.querySelector("#c"+cid);
		div.innerHTML =paquet[cid%52];
		if (arrivee)
			arrivee(cid, div);
	}
	var jeu = {
		donner : () => {
			console.log("jeu!");
			let a =[];
			for (let i=0; i<13*8; ++i) {
				a.splice(Math.random()*(a.length+1), 0, i);
			}
			let f = (aid) => {
				let cid =a[aid];
				console.log("jeu->donner %d", cid);
				if (aid+1 < 13*8)
					verso(cid, pioche.arrivee);
				else if (aid+1 == 13*8)
					defausse.donner();
				else {
					for(let i=0; i<10; ++i)
						famille[i].donner();
					pioche.distribuer();
				}
				if (aid+1 < 13*8-1)
					setTimeout(() => f(aid+1), 20);
				else if (aid+1 < 13*8)
					setTimeout(() => f(aid+1), 1000);
				else if (aid+1 < 13*8+1)
					setTimeout(() => f(aid+1), 1000);
			}
			f(0);
		}
	}
</script>
<?php 
	for($i=0;$i<36;++$i) {
		echo "<audio id=bruits-gauche".$i." controls=none preload=auto style='display:none'>";
		foreach(["ogg", "mp3", "wav"] as $ext) 
			echo "<source src='/res/snd/cardnoise-left.".$ext."'>";
		echo "</audio>";
	}
	for($i=0;$i<36;++$i) {
		echo "<audio id=bruits-droite".$i." controls=none preload=auto style='display:none'>";
		foreach(["ogg", "mp3", "wav"] as $ext) 
			echo "<source src='/res/snd/cardnoise-right.".$ext."'>";
		echo "</audio>";
	}
?>
<?php 
	for($i=0; $i<8; ++$i) {
		$id=strval($i);
		echo "<div class='c' id='d".$id."' width=150 height=210></div>";
	}
	for($i=0; $i<8; ++$i) {
		for($j=0; $j<52; ++$j) {
			$id=strval(52*$i+$j);
			echo "<div class='c' id='c".$id."' width=150 height=210></div>";
		}
	}
?>
<script>
	document.body.onload = () => {
		document.body.onload = "";
		pioche.donner();
		bruit.donner();
		setTimeout(() => jeu.donner(), 1000);
	}
	/*
	for(let i=0; i<4; ++i) {
		for(let j=0; j<13; ++j) {
			cid =13*i+j;
			x = wx + Math.random()*800;
			y = wy + Math.random()*800;
			r =Math.random() * 360;
			recto(cid, x, y, r);
		}
	}
			console.log(cid);
			let div =document.querySelector("#c"+cid);
			div.innerHTML =suite[cid];
			div.style.left =x;
			div.style.top =y;
			console.log(x,y);
			div.style.transform ="rotate("+r+"deg)";
			div.style.display ="block";
			div.onclick = (e) => {
				if (e.detail == 2) {	
					c0 =document.querySelector("#c0");
					if (flip[div.id] == undefined) {
						flip[div.id] =div.innerHTML;
						div.innerHTML =c0.innerHTML;
					} else {
						div.innerHTML =flip[div.id];
						flip[div.id] =undefined;
					}
				}
			}
			div.onmousedown = () => {
				var moving =div;
				moving.style.opacity =0.5;
				moving.style.cursor ="grabbing";
				window.onmousemove = (e) => {
					moving.style.left =e.x-150/2;
					moving.style.top =e.y-210/2;
				}
				window.onmouseup = (e) => {
					window.onmousemove ="";
					moving.style.zIndex =zIndex++;
					moving.style.opacity =1;
					moving.style.cursor ="grab";
				}
			}
		}
	}
	*/
</script>
</body>

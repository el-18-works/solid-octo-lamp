<html>
<style>
.c {
	margin : 30px 30px 30px;
	width : 150px;
	position : fixed;
	display : none;
	transition-timing-function : ease-out;
	transition-duration : 0.3s;
	cursor : grab;
}
</style>
<body style="background-color:#121232; margin:10px">
<?php 
	$prefix="https://ens.l18.work/res/misc/baraja/";
	$a =array();
	$figs =array("spade", "heart", "diamond", "club");
	$z =1;
	for($i=1; $i<14; ++$i) {
		foreach($figs as $f) {
			$id=$f.$i;
			$t ="<div class='c' id='".$id."' width=150 height=210 style='transform:rotate(20deg);z-index:".($z++)."'>";
			$t .=file_get_contents($prefix."card-web_".$f."_".$i.".svg"); 
			$t .="</div>";
			$a[]= $t;
		}
	}
	shuffle($a);
	foreach($a as $div) {
		echo $div;
	}
	$t ="<div class=c id=c0>";
	$t .=file_get_contents($prefix."card-web_0.svg"); 
	$t .="</div>";
	echo $t;
?>
<script>
	var flip ={};
	var zIndex =14*4;
	var figs =["spade", "heart", "diamond", "club"];
	wx =window.innerWidth-210;
	wy =window.innerHeight-210;
	for(let i=1; i<14; ++i) {
		for(let j=0; j<4; ++j) {
			f =figs[j];
			cid =f+i;
			console.log(cid);
			x =Math.random() * wx;
			y =Math.random() * wy;
			r =Math.random() * 360;
			let div =document.querySelector("#"+cid);
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
</script>
</body>

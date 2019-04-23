<!DOCTYPE html>
<html>
<head>
<title>留華のおうち</title>
<link rel="icon" href="https://l18.work/akali/female-octocoder-120x120.png" type="image/png">
<link rel="stylesheet" href="styles.css">
<meta charset="UTF-8">

<script>
var initDate =new Date();
function myFunction() {
    var date =new Date();
    var y =date.getYear()+1900;
    var mn =date.getMonth()+1;
    var d =date.getDate();
    document.getElementById("dat").innerHTML =" "+y+"年"+mn+"月"+d+"日";
    var h =date.getHours();
    var m =date.getMinutes();
    var s =date.getSeconds();
    document.getElementById("sig").innerHTML =" "+h+"時"+m+"分"+s+"秒";
    //date.setTime(date.getTime()-initDate.getTime());
    date =new Date(date.getYear(), date.getMonth(), date.getDate(), h-initDate.getHours(), m-initDate.getMinutes(), s-initDate.getSeconds());
    h =date.getHours();
    m =date.getMinutes();
    s =date.getSeconds();
    document.getElementById("el").innerHTML =" "+h+"時間"+m+"分"+s+"秒";
    setTimeout(myFunction, 1000);
}
</script>
<style>
</style>

</head>
<body>
<div id="all">
<center>
<h1>My凛華のおうち（幸福安心委員会監修）</h1>
<div>
<div id="time">
<span id="dat"></span> <span id="sig"></span> <span id="el"></span>
</div>
<div id="startdash">
<div id="sdplayer"></div>
<!--
<script type="application/javascript" src="https://embed.nicovideo.jp/watch/sm28492743/script?w=640&h=360"></script><noscript><a href="https://www.nicovideo.jp/watch/sm28492743">【アイカツ！】「START DASH SENSATION」をぬるぬるにしてみた2【HD60fps】</a></noscript>
-->
<br>
<a href=start-dash-diary>スタートダッシュダイアリー（２０１８年夏から２０１９年２月まで）</a>
</div>
<br>
<div id="my">
<!--
<iframe width="560" height="315" src="https://www.youtube.com/embed/kzUmM-uQVOM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
-->
<div id="kfplayer"></div>
<br>
<a href=mydiary>MyDiary</a>
<br>
皆さんここに来てくれて有難うございます！！！
幸福になれるよ〜〜！！
</div>
</div>
<?php

?>
</center>
</div>
</body>
<script>
myFunction();
</script>


<script>
  // Load the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/player_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // Replace the 'kfplayer' element with an <iframe> and
  // YouTube player after the API code downloads.
  var kfplayer;
  function onYouTubePlayerAPIReady() {
    kfplayer = new YT.Player('kfplayer', {
      width: '560',
      height: '315',
      host: 'https://www.youtube.com',
      playerVars: {'origin' : 'https://luckxa.l18.work'},
      videoId: 'kzUmM-uQVOM'
    });
    document.getElementById("my").addEventListener("mouseover", (function() {kfplayer.playVideo();}));
    document.getElementById("my").addEventListener("mouseout", (function() {kfplayer.pauseVideo();}));
  var sdplayer;
    sdplayer = new YT.Player('sdplayer', {
      width: '560',
      height: '315',
      host: 'https://www.youtube.com',
      playerVars: {'origin' : 'https://luckxa.l18.work'},
      videoId: 'fTnJjvYZkdU'
    });
    document.getElementById("startdash").addEventListener("mouseover", (function() {sdplayer.playVideo();}));
    document.getElementById("startdash").addEventListener("mouseout", (function() {sdplayer.pauseVideo();}));
  }

</script>

</html>

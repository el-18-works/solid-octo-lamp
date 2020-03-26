<?php
$SITE =array();
#$SITE["PROTO"] ="http://";
$SITE["PROTO"] ="https://";
$SHELLMAIN =!isset($_SERVER["REMOTE_ADDR"]);
if ($SHELLMAIN) {
	$SITE["SIGNATURE"] =array("de" => "", "ad" => "");
	$SITE["HOST"] ="shell";
} else {
	$addr = $_SERVER["REMOTE_ADDR"];
	$agent = $_SERVER["HTTP_USER_AGENT"];
	$de =$addr."; ".$agent.".";
	$addr = $_SERVER["SERVER_ADDR"];
	$soft = $_SERVER["SERVER_SOFTWARE"];
	$sign =$soft;
	$ad =$addr."; ".$sign." ".php_uname().".";
	$SITE["SIGNATURE"] =array("de" => $de, "ad" => $ad);
	if ($addr == "192.168.11.62") {
		$SITE["HOST"] ="satlin.local";
	} elseif ($addr == "192.168.11.63") {
		$SITE["HOST"] ="akalin.local";
	} elseif ($addr == "192.168.11.64") {
		$SITE["HOST"] ="satlin.local";
	} elseif ($addr == "192.168.11.65") {
		$SITE["HOST"] ="akalin.local";
	} elseif ($addr == "192.168.11.111") {
		$SITE["HOST"] ="aqari.local";
	} else {
		$SITE["HOST"] ="localhost";
	}
	unset($addr,$host,$agent,$de,$ad,$sign,$soft);
}
function local_agent() {
	global $SHELLMAIN;
	if ($SHELLMAIN) return true;
	$addr = $_SERVER["REMOTE_ADDR"];
	if (strstr($addr, "localhost")) {
		return true;
	} else if (! strstr($addr, ".")) {
		return $xaddr[0] == "";
	} else {
		# Clases de Direcciones :
		# A   0.0.0.0 - 127.255.255.255
		# B 128.0.0.0 - 191.255.255.255
		# C 192.0.0.0 - 223.255.255.255
		# D 224.0.0.0 - 239.255.255.255
		# E 240.0.0.0 - 255.255.255.255
		# Direcciones privadas :
		# Clase A : 10.0.0.0 a 10.255.255.255
		# Clase B : 172.16.0.0 a 172.31.255.255
		# Clase C : 192.168.0.0 a 192.168.255.255
		$xaddr = explode(".",$addr) ;
		$x =$xaddr[0];
		$y =$xaddr[1];
		return $x == "" or $x == 192 and $y == 168 or $x == 172 and $y >= 16 and $y <= 31 or $x == 10;
	}
}
$SITE["NAME"]="薩凛";
$SITE["NAME_TEXT"]="<ruby>薩<rt>Sat</rt>凛<rt>lin</rt></ruby>";
$SITE["NAME1_TEXT"]="<ruby>薩<rt>Sat</rt>凛<rt>lin</rt></ruby>";
$SITE["NAME2_TEXT"]="<ruby>瑠<rt>Luc</rt>華<rt>kxa</rt></ruby>";
if (local_agent()) {
	$SITE["AKALI_DOMAIN"]=$SITE["PROTO"].$SITE["HOST"];#"akali.local";
} else {
	$SITE["AKALI_DOMAIN"]=$SITE["PROTO"]."l18.work";
	#$SITE["AKALI_DOMAIN"]=$SITE["PROTO"]."satlin.work";
}
$SITE["HEAD_META"]='<META NAME="Author" CONTENT="Satlin Luckxa" />
<META NAME="Publisher" CONTENT="SeisSergas" />
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8" />
<META HTTP-EQUIV="content-style-type" CONTENT="text/css" />
<META HTTP-EQUIV="content-script-type" CONTENT="text/javascript" />
<link href="'.$SITE["AKALI_DOMAIN"].'/akali/minimum.css" rel="stylesheet" type="text/css" />
';
function foot_minimum() {
	global $SITE;
	return "<div class=copyleft>".
		$SITE["SIGNATURE"]["de"].'<br>'.
		$SITE["SIGNATURE"]["ad"].'<br>'.
		"Powered by <a href=https://www.bodhilinux.com>Bodhi Linux</a>, <a href=http://sagemath.org>SageMath</a>;<br>".
		"Copyleft & License Free;<br>".
		'<a href="'.$SITE["AKALI_DOMAIN"].'/about.php">L18 WORKS</a>.'.
		"</div>";
}
function head_minimum($title =null) {
	global $SITE;
	return $SITE["HEAD_META"].'
<link rel="icon" href="'.$SITE["AKALI_DOMAIN"].'/akali/female-octocoder-120x120.png" type="image/png" />
<title>'.($title ?$title : $SITE["NAME"]).'</title>';
}
function css_minimum($title =null) {
	return '';
}
function css_motto($rgb =null) {
	return '';
}
function json_get_contents($file) {
	$json =file_get_contents($file);
	if (isset($_GET["debug"])) {
		print_r(json_decode($json));
		echo "<br>";
		echo $json;
	}
	$tab =json_decode($json, true);
	if (!$tab) {
		echo "warning : json format error:";
		echo $json;
	} else {
		return $tab;
	}
}
class JsonMotto {
	function __construct($json_file, $name=null) {
		$this->json =json_get_contents($json_file);
		$this->name =$name;
		$this->notes =array();
	}
	function css($rgb) {
		if ($this->name ) {
			$style_unit ="motto-unit-".$this->name;
			$style_text ="motto-text-".$this->name;
			$style_author ="motto-author-".$this->name;
			$style_notes ="motto-notes-".$this->name;
			$style_hr ="motto-hr-".$this->name;
			$style_note ="motto-note-".$this->name;
		} else {
			$style_unit ="motto-unit";
			$style_text ="motto-text";
			$style_author ="motto-author";
			$style_notes ="motto-notes";
			$style_hr ="motto-hr";
			$style_note ="motto-note";
		}
		$text ="";
		foreach(array($style_text, $style_author, $style_notes, $style_note) as $s) {
			$text .=sprintf(".%s {font-family : \"%s\";}\n", $s, "Noto Serif");
		}
		return $text;
		return '';
	}
	function text() {
		if ($this->name ) {
			$style_unit ="motto-unit-".$this->name;
			$style_text ="motto-text-".$this->name;
			$style_author ="motto-author-".$this->name;
			$style_notes ="motto-notes-".$this->name;
			$note_prefix ="motto-note-".$this->name;
			$ref_prefix ="motto-ref-".$this->ref;
		} else {
			$style_unit ="motto-unit";
			$style_text ="motto-text";
			$style_author ="motto-author";
			$style_notes ="motto-notes";
			$note_prefix ="motto-note";
			$ref_prefix ="motto-ref";
		}
		$i =0;
		foreach($this->json as $x) {
			printf("<div class=%s>\n", $style_unit);
			if ($x["lang"]) {
				$lang ="lang=".$x["lang"];
			} else {
				$lang ="";
			}
			foreach($x["text"] as $s) {
				printf(" <p %s class=\"%s\">%s</p>\n", $lang, $style_text, $s);
			}
			if ($x["ref"]) {

				$note =$note_prefix.(++$i);
				$ref =$ref_prefix.($i);
				printf(" <p class=%s>-- %s.<a class=motto-refmark href=#%s name=%s>[%d]</a></p>\n", $style_author, $x["author"], $note, $ref, $i);
				$this->notes[$i] =$x["ref"];
			}
			printf("</div>\n"); // $style_unit
		}
		return '';
	}
	function notes() {
		if ($this->name ) {
			$style_hr ="motto-hr-".$this->name;
			$style_note ="motto-note-".$this->name;
			$note_prefix ="motto-note-".$this->name;
			$ref_prefix ="motto-ref-".$this->name;
		} else {
			$style_hr ="motto-hr";
			$style_note ="motto-note";
			$note_prefix ="motto-note";
			$ref_prefix ="motto-ref";
		}
		printf("<hr class=%s />\n", $style_hr);
		$i =0;
		foreach($this->notes as $s) {
			$note =$note_prefix.(++$i);
			$ref =$ref_prefix.($i);
			printf("<p class=%s><b class=motto-notemark>%d. </b><a name=%s href=#%s>%s</a></p>\n", $style_note, $i, $note, $ref, $s);
		}
		return '';
	}
}
class JsonMirror {
	function __construct($json_file, $name=null) {
		$this->json =json_get_contents($json_file);
		$this->name =$name;
	}
	function css($rgb) {
		return '';
	}
	function link($table) {
		global $SITE;
		echo "<table class=mirror-table>";
		echo "<th><td class=mirror-link>下載</td><td>説明</td><td>大小</td><td class=mirror-type>格式</td></th>";
		foreach ($table as $x) {
			//print_r($x);
			$href=$SITE["AKALI_DOMAIN"]."/localcached.php?q=".$x["href"];
			echo "<tr>";
			printf( "<td></td>");
			printf( "<td class=mirror-link><a href=%s target=_blank>%s</a></td>", $href, $x["title"]);
			printf( "<td>%s</td>", $x["description"]);
			printf( "<td>%d MB</td>", $x["size"]);
			printf( "<td class=mirror-type>%s</td>", $x["type"]);
			echo "</tr>\n";
		}
		echo "</table>";
	}
	function table() {
		if ($this->name ) {
		}
		$this->link($this->json);
	}
}
function ssh_session_local() {
	$session =ssh2_connect('satlin.local', 22);
	ssh2_auth_pubkey_file($session, 'root', '/root/.ssh/id_rsa.pub', '/root/.ssh/id_rsa');
	return $session;
}
function ssh_exec_local($command, $err=false) {
	$ss =ssh_session_local();
	$stream =ssh2_exec($ss, $command);
	unset($ss);
	if ($err) {
		$stream =ssh2_fetch_stream($stream,SSH2_STREAM_STDERR);
	}
	stream_set_blocking($stream, true);
	$res =stream_get_contents($stream);
	fclose($stream);
	return $res;
}
function ssh_recv_local($de, $ad) {
	$ss =ssh_session_local();
	return ssh2_scp_recv($ss, $de, $ad);
}



function login_form() {
	return '
<form name=login method=post onsubmit="return false" >
	<label for="text">昵稱</label>
	<input id="text" name="text" maxlength=12 type="text" placeholder="昵稱" required></input><br/>
	<label for="pass"> 密碼</label>
	<input name="pass" maxlength=10 type="password" pattern="[0-9]{6}" placeholder="密碼" oninput="checkpass()" required></input><br/>
	<button name="door" disabled>註冊</button>
</form>
<script>
with (document.login) {
	function checkpass() {
		if (pass.value.length >= 6 && text.value.length > 0) {
			door.removeAttribute("disabled");
		} else {
			door.setAttribute("disabled", true);
		}	
	}
	door.addEventListener("click", function() {
		with (document.login) {
			if (text.value.toLowerCase() == "nirvana" && pass.value == 0) {
				submit();
				alert("Login OK");
			} else {
				alert("error!");
			}
		}
	});
}
</script>
';
}
function passage() {
	if (isset($_GET["debug"]) && $_GET["debug"] == "pass")
		return false;
	if (isset($_COOKIE["pass"])) {
		return true;
	} elseif (isset($_POST["pass"])) {
		setcookie("pass", "Nirvana", time()+60*60*24*10000, "/", "l18.work");
		setcookie("pass", "Nirvana", time()+60*60*24*10000, "/", "satlin.work");
		setcookie("pass", "Nirvana", time()+60*60*24*10000, "/", "satlin.local");
		return true;
	} else {
		return false;
	}
}

?>


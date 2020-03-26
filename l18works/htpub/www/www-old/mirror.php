<?php
# begin linker
	$prefix='<a href="localmirror.php?q=';
	$infix='">';
	$postfix='</a>';
	function link_odt($file, $description) {
		global $prefix,$infix,$postfix;
		echo $prefix.$file.$infix.$description.$postfix."\n";
	}
	function link_pdf($file, $description) {
		global $prefix,$infix,$postfix;
		echo $prefix.$file.$infix.$description.$postfix."\n";
	}
	function link_tarball($file, $description) {
		global $prefix,$infix,$postfix;
		echo $prefix.$file.$infix.$description.$postfix."\n";
	}
	function link_zip($file, $description) {
		global $prefix,$infix,$postfix;
		echo $prefix.$file.$infix.$description.$postfix."\n";
	}
	function linka($table) {
		echo "<table border=1>";
		foreach ($table as $x) {
			echo "<tr><td>";
			echo "{$x[0]}</td><td>";
			switch($x[0]) {
			case "odt" :
				link_odt($x[1], $x[2]);
				break;
			case "pdf" :
				link_pdf($x[1], $x[2]);
				break;
			case "tarball" :
				link_tarball($x[1], $x[2]);
				break;
			case "zip" :
				link_zip($x[1], $x[2]);
				break;
			}
			echo "</td></tr>";
		}
		echo "</table>";
	}
# end
?>
<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="female-octcoder-120x120.png">
<title>MirroRorriM</title>
</head>
<body>
<?php
	$json =file_get_contents("mirror.json");
	if (isset($_GET["debug"])) {
		print_r(json_decode($json));
		echo "<br>";
		echo $json;
	}
	$tab =json_decode($json);
	if (!$tab) {
		echo "warning : json format error:";
		echo $json;
	} else {
		linka(json_decode($json));
	}
?>
</body>

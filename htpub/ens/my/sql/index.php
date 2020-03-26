<?php
/* 
 * el json header
 */
header("Content-Type: application/json; charset=UTF-8");
/* 
 * descifrar el json interrogante
 */
// argumento de interrogación
$qq = json_decode($_REQUEST["q"], false /*=> object, true => array*/);
// marcadores y parámetros
$pp = isset($_REQUEST["p"]) ? json_decode($_REQUEST["p"], false /*=> object, true => array*/) : null;
// argumento de conexión
//$c = $_REQUEST["c"];
$c = json_decode($_REQUEST["c"], false /*=> object, true => array*/);
// XXX cookie no efectuante.
//setcookie("c", $_REQUEST["c"], time()+60*60*24*90); /*90 días*/

class resclass { var $error; var $res; }
/*
$qq =array("show tables", "select * from verblist_ko0", "show tables;", "show tables");
//$qq =array('INSERT INTO verblist_ja1 (日本語) VALUE("閉める")','SELECT * FROM verblist_ja1');
$qq =array('INSERT INTO verblist_ja1 (s) VALUE("閉める")','SELECT * FROM verblist_ja1');
$pp =null;
print_r($qq);
*/
//$mysqli =new mysqli("localhost", "l18", "@", "l18_try_hangul");
$mysqli =new mysqli("localhost", $c->user, $c->pass, $c->schema);
//echo ($mysqli->characeter_set_name());
$mysqli->set_charset("utf8");
//die($mysqli->character_set_name());
function transactio($q, $p) {
	global $mysqli;
	$r =new resclass();
	if ($p != null) { // preparando con parámetros.
		$fmt =$p[0];
		$param =$p[1];
		if ($mysqli->connect_error) {
			$r->error =array( "connect-error" => array("errno" => $mysqli->connect_errno, "error" => $mysqli->connect_error ) );
		} elseif (!($stmt = $mysqli->prepare($q))) {
			$r->error =array( "prepare-error" => array("errno" => $mysqli->errno, "error" => $mysqli->error ) );
		} elseif (!$stmt->bind_param($fmt, $param)) {
			$r->error =array( "execute-error" => array("errno" => $mysqli->errno, "error" => $mysqli->error ) );
		} elseif (!$stmt->execute()) {
			$r->error =array( "execute-error" => array("errno" => $mysqli->errno, "error" => $mysqli->error ) );
		} else {
			if (!$stmt->bind_result($out)) {
			$r->error =array( "bind_result-error" => array( "errno" => $mysqli->errno, "error" => $mysqli->error ) );
			} else {
				while ($stmt->fetch()) {
					$r->res[] =$out;
				}
			}
		} 
	} else { // sin preparar.
		if (!($res = $mysqli->query($q))) {
			$r->error =array( "querry-error" => array( "errno" => $mysqli->errno, "error" => $mysqli->error, "q" => $q ) );
		} else {
			while ($row = $res->fetch_object())
				$r->res[] =$row;
		}
	}
	return $r;
}


if (gettype($qq) == "string") {
	$r =transactio($qq, null);
} elseif (gettype($qq) == "array") {
	$r =array();
	foreach($qq as $q) {
		$r[] =transactio($q, null);
	}
}
if (gettype($pp) == "string") {
	transactio(null, $pp);
} elseif (gettype($pp) == "array") {
	foreach($pp as $p)
		transactio(null, $p);
}

/* 
 * cifrar la respuesta en json
 */
echo json_encode($r);
?>

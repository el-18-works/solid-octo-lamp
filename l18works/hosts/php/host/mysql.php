<?php
/*
 * Functio datorum conservatrix dataque servans.
 */
function my_data_servatio() {
	/* 
	 * el json header
	 */
	header("Content-Type: application/json; charset=UTF-8");
	/* 
	 * descifrar el json interrogante
	 */
	// argumento de interrogación
	$q = json_decode(urldecode($_REQUEST["q"]), false /*=> object, true => array*/);
	// marcadores y parámetros
	$p = isset($_REQUEST["p"]) ? json_decode(urldecode($_REQUEST["p"]), false /*=> object, true => array*/) : null;
	// argumento de conexión
	$c = json_decode($_REQUEST["c"], false /*=> object, true => array*/);
	// XXX cookie no efectuante.
	//setcookie("c", $_REQUEST["c"], time()+60*60*24*90); /*90 días*/

	class resclass {
		var $error;
		var $res;
	}

	$mysqli =new mysqli("127.0.0.1", $c->user, $c->pass, $c->schema);
	$mysqli->set_charset("utf8");
	//$mysqli =new mysqli("localhost", "luckxa", "my", "sys");
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
			$r->error =array( "querry-error" => array( "errno" => $mysqli->errno, "error" => $mysqli->error, "sql" => $q ) );
		} else {
			while ($row = $res->fetch_object())
				$r->res[] =$row;
		}
	}
	/* 
	 * cifrar la respuesta en json
	 */
	echo json_encode($r);
}
?>

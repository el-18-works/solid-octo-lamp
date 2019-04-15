<?php
/* 
 * el json header
 */
header("Content-Type: application/json; charset=UTF-8");
/* 
 * descifrar el json interrogante
 */
// argumento de interrogación
$q = json_decode($_REQUEST["q"], false /*=> object, true => array*/);
// marcadores y parámetros
$p = isset($_REQUEST["p"]) ? json_decode($_REQUEST["p"], false /*=> object, true => array*/) : null;
// argumento de conexión
$c = json_decode($_REQUEST["c"], false /*=> object, true => array*/);
setcookie("c", $_REQUEST["c"], time()+60*60*24*90); /*90 días*/

function my_describe() {
	if (!($stmt = $mysqli->prepare($q))) {
	//if (!($stmt = $mysqli->prepare("DESCRIBE $table"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if (!$stmt->store_result()) {
		echo "Store failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//printf("Num rows = %d \n", $stmt->num_rows());
	//printf("Field count = %d \n", $stmt->field_count);
	$out =array();
	if (!$stmt->bind_result($out["Field"], $out["Type"], $out["Null"], $out["Key"], $out["Default"], $out["Extra"])) {
		echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	echo "<p class='stmt'><span class='prompt'>mysql&gt; DESCRIBE</span>&nbsp; $table <span class='prompt'>;</span><p>";
	echo "<table class='desc'>";
	echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
	while ($stmt->fetch()) {
		echo "<tr>";
		foreach( $out as $value ) {
			printf("<td>%s</td>", $value);
		}
		echo "</tr>";
	}
	echo "</table>";
}

class resclass {
	var $error;
	var $res;
}

$mysqli =new mysqli("localhost", "luckxa", "my", "menagerie");
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
		$r->error =array( "querry-error" => array( "errno" => $mysqli->errno, "error" => $mysqli->error ) );
	} else {
		while ($row = $res->fetch_object())
			$r->res[] =$row;
	}
}
/* 
 * cifrar la respuesta en json
 */
echo json_encode($r);
?>

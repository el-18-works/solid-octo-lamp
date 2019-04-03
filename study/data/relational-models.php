<?php
require_once("l18data.php");

function my_describe($table) {
	$mysqli =my_connect();
	if (!($stmt = $mysqli->prepare("DESCRIBE $table"))) {
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

//phpinfo();
my_connect("world_x", "luckxa", "my");
?>

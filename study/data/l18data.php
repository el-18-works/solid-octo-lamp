<?php
function my_error($msg) {
	echo $msg."\n";
}

function my_connect($db=NULL, $user="luckxa", $pass="my") {
	static $mysqli =NULL;
	if ($mysqli == NULL) {
		$mysqli =new mysqli("localhost", $user, $pass);
	}
	if ($db != NULL) {
		$mysqli->select_db($db);
	}
	if ($mysqli->connect_errno) {
		my_error( "my_connect : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error );
	}
	return $mysqli;
}
?>

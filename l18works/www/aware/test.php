<?php
require("globals.php");
//require_once("globals.php");
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
		//fclose($stream);
		$stream =ssh2_fetch_stream($stream,SSH2_STREAM_STDERR);
	}
	stream_set_blocking($stream, true);
	$res =stream_get_contents($stream);
	fclose($stream);
	return $res;
}

if ($SHELLMAIN) {
	echo ssh_exec_local("/usr/bin/ps aux");
	echo ssh_exec_local("/usr/bin/openssl xxx", true);
}

?>

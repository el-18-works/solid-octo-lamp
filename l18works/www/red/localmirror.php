<?php

function satlin_local_readfile ($file) {
    $file ="ftp://satlin.local/mirror/".$file;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream; charset=utf-8');
    header('Content-Disposition: attachment; filename="satlin_mirror-'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

$file =$_GET["q"];
satlin_local_readfile ($file);

?>


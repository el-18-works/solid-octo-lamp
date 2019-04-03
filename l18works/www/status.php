<?php
require_once("globals.php");
echo "<pre>";
echo "SITE\n";
print_r($SITE);
echo '<a href="/info.php">PHP Info</a>'."\n";
echo '<a href="/server-status">status.status-url</a>'."\n";
echo '<a href="/server-config">status.config-url</a>'."\n";
echo '<a href="/server-statistics">status.statistics-url</a>'."\n";
echo "</pre>";
?>

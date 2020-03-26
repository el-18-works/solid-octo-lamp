<?php
require_once("globals.php");
echo "<pre>";
echo "INFO :\n";
echo '<a href="/info.php">PHP Info</a>'."\n";
echo '<a href="/server-status">status.status-url</a>'."\n";
echo '<a href="/server-config">status.config-url</a>'."\n";
echo '<a href="/server-statistics">status.statistics-url</a>'."\n";
echo "SITE :\n";
print_r($SITE);
echo "UNAME :\n";
system("uname -a");
echo "LSHW :\n";
system("lshw");
echo "CPUINFO :\n";
system("cat /proc/cpuinfo");
echo "</pre>";
?>

<?php
error_reporting(E_ALL);

/* Добавляем перенаправление, чтобы прочитать stderr. */
$handle = popen('/var/www 2>&1', 'r');
echo "'$handle'; " . gettype($handle) . "\n";
$read = fread($handle, 2096);
echo $read . "\n";;
pclose($handle);


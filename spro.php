<?php
echo '<table width="99%"><tr><td>cron</td></tr>' . "\n";
$croninf="";
$fp=popen("/bin/ps -waux","r");
while (!feof($fp)) {
    $buffer = fgets($fp, 4096);
    $croninf .= '<tr><td>' . $buffer . '</td></tr>' . "\n";
}
pclose($fp);
echo $croninf;
echo '</table><br><br>' . "\n";
?>
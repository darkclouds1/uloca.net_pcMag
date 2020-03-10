<?php
ob_start();
// current time
echo date('h:i:s') . "\n";
echo str_pad(" " , 256);
ob_end_flush();
	flush();
$k = 5;
// sleep for 60 seconds
while ($k>0) {
sleep(20);

// wake up !
echo date('h:i:s') . "\n";

$k --;
}
?>
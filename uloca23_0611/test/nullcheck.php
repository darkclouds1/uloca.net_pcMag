<?php 

$rmrk = '낙찰하한선 미달';  //낙찰하한선 미달
 echo ($rmrk."<br>");
if ($rmrk == 0 ) {
	echo ("$rmrk 는 0입니.");
}
$rmrk = "1";
echo ($rmrk."<br><br>");


$arr = ' ';
 echo ((int)trim($arr)."<br>");
$arr = '';
echo ((int)trim($arr)."<br>");
$arr = '1';
echo ((int)trim($arr)."<br>");
 $arr = '2 ';
 echo ((int)trim($arr)."<br>");
 $arr = '자격미달3           ';
 echo ((int)trim($arr)."<br>");
 
 
?>
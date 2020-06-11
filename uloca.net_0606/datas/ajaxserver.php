<?
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); // '/g2b/classPHP/g2bClass.php'); 
$g2bClass = new g2bClass;

//echo 'ajaxServer....'.$_GET['kwd'];
$userid = 'blueoceans';
$startDate='201807291234';
$ndt = $g2bClass->dateTimeFormat($startDate,'-');
echo $ndt;
//$list = $g2bClass->autoRecList($userid);
//echo $list;
?>
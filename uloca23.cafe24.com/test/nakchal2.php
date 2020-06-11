<?
@extract($_GET);

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn();

if ($bidno == '') $bidNtceNo = '20181102308'; //20181134739';
else $bidNtceNo = $bidno;
if ($bidord == '') $bidNtceOrd = '00'; //20181134739';
else $bidNtceOrd = $bidord;

$item = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd);

echo count($item);
?>
<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;

$numOfRows = 999;
$pageNo = 1;
$inqryDiv = 2;
$inqryBgnDt = '201711090000';
$inqryEndDt = '201711102359';
$pss = '용역';
$bidNtceNo='20180914764'; //'20170927549';
$search= 15;
$startDate='20190201';
$endDate='20190220';
$kwd='부산';
$dminsttNm='';
$response = $g2bClass->getHrcOne($startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$inqryDiv,$search);
var_dump($response);

?>
<?php 

@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); 
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');

$g2bClass = new g2bClass;
$dbConn   = new dbConn;
$conn     = $dbConn->conn();

$bidNtceNo   = $_GET['bidNtceNo'];
$bidNtceOrd     = $_GET['bidNtceOrd'];

$numOfRows = 999;
$pageNo = 1;
$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
$bidNtceNo = '20200351383';
$bidNtceOrd = '00';

$pss = '유찰';
// $response2 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
$response = $g2bClass->getBidRslt2($numOfRows, $pageNo, $inqryDiv, '', '', '유찰', $bidNtceNo, $bidNtceOrd);
$json = json_decode($response, true);
$item = $json['response']['body']['items'];
var_dump($item);
foreach ($item as $arr) {
    $arr['resultCode'];
    $arr['resultMsg'];
    $arr['numOfRows'];
    $arr['pageNo'];
    $arr['totalCount'];
    $arr['opengRsltDivNm']; // 개찰결과구분명
    $arr['bidNtceNo'];
    $arr['bidNtceOrd'];
    $arr['bidClsfcNo'];     // 입찰분류번호
    $arr['rbidNo'];         // 재입찰번호
    $arr['nobidRsn'];       // 유찰사유

	$i++;
}

?>
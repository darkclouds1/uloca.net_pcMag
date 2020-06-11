<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
//$bidNtceNo='20170532764';
//$bidNtceOrd='00';
if(!isset($bidNtceNo)) {
	echo '공고번호가 없습니다.';
	exit;
}
//url = 'http://uloca23.cafe24.com/g2b/bidResult.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd='+bidNtceOrd+'&pss='+pss;
	
$g2bClass = new g2bClass;
$uloca_live_test = $g2bClass->getSystem('2'); 
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
// --------------------------------- log
$rmrk = '입찰결과창';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,'','15');
// --------------------------------- log
// 낙찰 결과 1위
$response1 = $g2bClass->getRsltData1($bidNtceNo,$bidNtceOrd); 
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//var_dump($item1);
$compno = $item1[0]["prcbdrBizno"];

$url = '/g2b/datas/getInfobyComp.php?compno='.$compno.'&id='.$id;
//echo $url;
header("Location: $url"); /* Redirect browser */
exit();
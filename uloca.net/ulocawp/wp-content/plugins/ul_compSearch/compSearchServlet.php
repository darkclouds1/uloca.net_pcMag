<?php

use PhpMyAdmin\Console;

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
header('Content-Type: text/html; charset=UTF-8');


//session_start();
date_default_timezone_set('Asia/Seoul');
//require('./compDAO.php');

// KED(한국기업데이터) 전문 E017 -by jsj 20190824
//$name = $_GET["username"]; //post 파라미터

$bzno = trim($userName); 
if ($bzno == '') {
	
	return ("사업자번호를 입력하세요!") ;
	//$bzno = '6098164815';
}

//$kedUrl_E017 = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?";
$kedUrl_E017 = "https://kedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=".$bzno."&cono=&pid_agr_yn=N&jm_no=E017";
//echo $kedUrl_E017;

// $kedUrl_E017 = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=" .$bzno. "&cono=&pid_agr_yn=N&jm_no=E017";
// $xml = simplexml_load_file($url);
// echo $url."\n";
// print_r($xml);
// exit;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $kedUrl_E017);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_REFERER, $kedUrl_E017);
$res = curl_exec($ch);
curl_close($ch);

//*============================================== DB insert
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
////$xml = new SimpleXMLElement($res);
$xml=simplexml_load_string($res); 
$dbConn->cmpE01701M_1($conn,$xml);	// 기업 정보 메인
$dbConn->cmpCredit01M_1($conn,$xml);// 신용 정보
$dbConn->cmpE01702D_1($conn,$xml);	//	주주 현황
$dbConn->cmpFrsum01M_1($conn,$xml);	//	결산 내역
$dbConn->cmpFssum01M_1($conn,$xml);	//	자산 현황
$dbConn->cmpCfsum01M_1($conn,$xml);	//	요약현금흐름분석 cf_anal_summ //
//==============================================
//$json_string = json_encode($xml);    
print_r($res);

?>
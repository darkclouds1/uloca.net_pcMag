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

$bzno = $userName; 
if ($bzno == '') {
	
	return ("사업자번호를 입력하세요!") ;
	//$bzno = '6098164815';
}

$kedUrl_E017 = "https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?";
//$kedUrl_E017 = "https://kedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?";
$kedUrl_E017 .= "user_id=ulocaonl&process=S&bzno=".$bzno."&cono=&pid_agr_yn=N&jm_no=E017";
//echo $kedUrl_E017;

/*
https://testkedex.cretop.com:6056/invoke/infoInquiry.Service/companySearch2?user_id=ulocaonl&process=S&bzno=1048126067&cono=&pid_agr_yn=N&jm_no=E017
$xml = simplexml_load_file($url);
echo $url."\n";
print_r($xml);
exit;
*/

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
//var_dump($res);
//$xml = new XMLParser($res);
//$xml->Parse();
//echo $xml->CONTENTS->E017->enp_nm . "<br>";
//echo ("=============<br>");
//echo '<br><br>';
//echo $xml->HEADER->user_id;
exit;


//$library = simplexml_load_string($xml) or die ("Error: Cannot creae object");
/*
foreach ($library->children() as $child){
    echo $child->getName();
    // Get attributes of this elemen
    foreach ($child->attributes() as $attr){
        echo ' ' . $attr->getName() . ': ' . $attr;
    }
    // Get children
    foreach ($child->children() as $subchild){
        echo ' ' . $subchild->getName() . ': ' . $subchild;
    }
}
*/


//$user_login = $_POST["user_login"]; //Get 파라미터
//$name = $_POST["name"]; //post 파라미터
//getJSon($name);

function getJSon($name){
	if($name == null) $name = "";

	$result = "";
	$result = "{\"result\":[";
	$compDAO = new classDAO;
	$list = array();
	$list = $compDAO->searchDAO($name);
	
	foreach ($list as $key=>$value) {
		$result .= "[{\"value\":\"" . $value->_idx ."\"},";
		$result .= "{\"value\":\"" . $value->_user_login ."\"},";
		$result .= "{\"value\":\"" . $value->_name ."\"},";
		$result .= "{\"value\":\"" . $value->_user_email ."\"},";
		$result .= "{\"value\":\"" . $value->_payFreeCD ."\"},";
		$result .= "{\"value\":\"" . $value->_modifyDT ."\"}],";
	}
	$result .= "]}";
	
	//$result = json_encode($result);
	echo($result);
}

?>
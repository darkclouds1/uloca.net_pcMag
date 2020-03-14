<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 

$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

// --------------------------------- log
$rmrk = '자동받기실행';
$dbConn->logWrite($userid,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log

//$mobile = $g2bClass->MobileCheck();
//echo '접속 기기='.$mobile;
$timestamp = strtotime("Now");
$endDate = date('Ymd',$timestamp); //'20180725';
$timestamp = strtotime("-1 weeks");
$startDate = date('Ymd',$timestamp); //'20180720';

// getBidOne($startDate,$endDate,$kwd,$dminsttNm,$num=20,$inqryDiv=2,$search=15)
//$numOfRows = 100;
//$pageNo=1;
//$inqryDiv = 1;

//검색키워드 URL 만 보냄 -by jsj 2019801
//if ($search == '') $search = 1;
$SearchURL = "https://uloca.net/ulocawp/?page_id=1134&searchType=1&kwd=".$kwd;

/*
$contents1 = $g2bClass->getBidOne($startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$inqryDiv,$search);
$contents2 = '';
if ($search == 2 || $search == 3 || $search == 6 || $search == 7 || $search == 10 || $search == 11 || $search == 14 || $search == 15 ) { 
		$contents2 = $g2bClass->getHrcOne($startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$inqryDiv,$search);
}
*/

$message = '
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
</head>
<body>
	<p><a href="https://uloca.net">
		<input  type="button" value="유로카 입찰정보" style="width:300px; background-color:#E9602C; height:28px; color:#fff; cursor:pointer; font-size:14px; font-weight: bold; text-align:center; border:solid 0px ;">
	</a></p>
	
	<p><a href="'.$SearchURL.'"> <font size=3> 키워드:</font><font color=blue size=3> <b>'.$kwd.'</b></font> 
	</font></a></p>
	<br><br>';

//$message .= $contents1.'<br><br>'.$contents2.'</body></html>';
//$message .= '<br>Uloca SearchURL: ' . $SearchURL;
$message .= '</body></html>';

header('X-XSS-Protection: 0');
$to  = $email;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";
//echo 'message='.$message;
$subject = '입찰/사전규격 정보 알림';

mail($to, $subject, $message, $headers);

?>
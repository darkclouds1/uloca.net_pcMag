<?php
use PhpMyAdmin\Message;

header("Content-Type:application/json");

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
$userid = $_POST[userid];
$dbConn->logWrite($userid, $_SERVER['REQUEST_URI'],$rmrk);
// ---------------------------------log


//검색키워드 URL 만 보냄 -by jsj 2019801
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
</a></p> 사전 등록한 키워드를 클릭하세요!';

//foreach ($kwd as $value) {
for ($i=0; $i < count($kwd); $i++){
	//수요기관이 없으면 통합검색
	if ($dminsttNm[$i] == '') {
		$SearchURL = "https://uloca.net/ulocawp/?page_id=1134&searchType=1";
	} else {
		$SearchURL = "https://uloca.net/ulocawp/?page_id=1138&searchType=1";
	}
	$SearchURL .= "&kwd=".$kwd[$i];
	$SearchURL .= "&dminsttNm=".$dminsttNm[$i];
	$SearchURL .= "&bidservc=1";
	$SearchURL .= "&bidinfo=1";
	$SearchURL .= "&&bidhrc=bid";

	$message .= '<p><a href="'.$SearchURL.'">';
	$message .= '<font color=black size=2>입찰공고검색=</font><font color=red size=3><b>[ '.$kwd[$i].' ]</b></font>';
	$message .= ' && <font color=black size=2>수요기관</font><font color=red size=3><b>[ '.$dminsttNm[$i].' ]</b></font>';
	$message .= '</a></p>'; 
}
$message .= '</body></html>';

header('X-XSS-Protection: 0');
$to  = $email;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: 나라장터 정보 <uloca@uloca.net>' . "\r\n";

//echo 'message='.$message;
$subject = '키워드별 입찰정보-알림';

if ($to == "enable21@gmail.com"){
	mail($to, $subject, $message, $headers);
}

$o = "count(kwd)=".count($kwd).", Email Send Sucess! email= ".$email.", userid= ****<".$userid.">********, message= ".$message;
echo json_encode($o);

?>
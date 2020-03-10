<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
// nwp/dailyDataHandle12.php

date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
// --------------------------------- log
//$rmrk = '';
//$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log

// 1 : 임시->개찰정보
//if ($func == '1') {
	$sql = 'insert into '.$openBidSeq . ' ( `bidNtceNo`, `bidNtceOrd`, `compno`, `tuchalamt`, `tuchalrate`, `selno`, `tuchaldatetime`, `remark`, `bidIdx` ) ';
	$sql .= ' (Select ';
	$sql .= '`bidNtceNo`, `bidNtceOrd`, `compno`, `tuchalamt`, `tuchalrate`, `selno`, `tuchaldatetime`, `remark`, `bidIdx` ' ;
	$sql .= 'from openBidSeq_tmp12 ';
//	$sql .= "WHERE `remark` in ( '1','2','3','4','5') ";
	$sql .= ') ';

	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("s", $qry);
	$stmt->execute();
	//echo $sql;
	echo '1 임시->개찰정보 ok ';
//}
// 2 : 임시개찰정보 비우기
//if ($func == '2') {
	$sql = 'truncate table  openBidSeq_tmp12';
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("s", $qry);
	$stmt->execute();
	echo $sql;
	echo '2 임시개찰정보 비우기 ok';
//}

?>
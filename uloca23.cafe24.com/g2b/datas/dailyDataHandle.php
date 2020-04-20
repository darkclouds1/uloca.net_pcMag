<?php
//개찰정보 임시테이블에서 개찰정보로 업데이트

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
// g2b/datas/dailyDataHandle.php

date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
$dbConn = new dbConn;
$conn = $dbConn->conn();

// --------------------------------- log
$rmrk = '';
$dbConn->logWrite($_SESSION['current_user']->user_login,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
$openBidSeq = $_GET['openBidSeq'];

// 1 : 임시->개찰정보 :: INSERT IGNORE INTO
$sql = '   REPLACE INTO '.$openBidSeq . ' ( `bidNtceNo`, `bidNtceOrd`, `compno`, `tuchalamt`, `tuchalrate`, `selno`, `tuchaldatetime`, `remark`, `bidIdx` ) ';
$sql .= ' (SELECT `bidNtceNo`, `bidNtceOrd`, `compno`, `tuchalamt`, `tuchalrate`, `selno`, `tuchaldatetime`, `remark`, `bidIdx` ' ;
$sql .= '    FROM openBidSeq_tmp ) ';

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($sql);
$stmt->execute();
echo '1 임시->개찰정보 ok ';

// 2 : 임시개찰정보 비우기
$sql = 'truncate table  openBidSeq_tmp';
$stmt = $conn->stmt_init();
$stmt = $conn->prepare($sql);
$stmt->execute();
echo $sql;
echo '2 임시개찰정보 비우기 ok';

?>
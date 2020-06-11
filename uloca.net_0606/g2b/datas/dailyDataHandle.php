<?php
//개찰정보 임시테이블에서 개찰정보로 업데이트

@extract($_GET);
@extract($_POST);
@extract($_SERVER);
// g2b/datas/dailyDataHandle.php?func='+func+'&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq + '&openBidSeq_tmp='+openBidSeq_tmp;

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

$openBidSeq     = $_GET['openBidSeq'];
$openBidSeq_tmp = $_GET['openBidSeq_tmp'];

// 1 : 임시->개찰정보 :: INSERT IGNORE INTO
$sql = " REPLACE INTO " .$openBidSeq ;
$sql .= "      SELECT * FROM " .$openBidSeq_tmp;
if ($conn->query($sql)) {
    $sql = " TRUNCATE " .$openBidSeq_tmp;
    if ($conn->query($sql) == false) {
        echo "ln32::Error sql=" . $sql;
    }
} else {
    echo "ln359::Error sql=" .$sql;
}

echo "ln::38 임시개찰정보 비우기 openBidSeq=" .$openBidSeq. ", openBidSeq_tmp=" .$openBidSeq_tmp. " , sql=" .$sql;

?>
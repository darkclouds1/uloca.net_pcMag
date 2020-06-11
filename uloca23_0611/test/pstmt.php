<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
session_start();
//echo 'SESSION["ServerAddr"]='.$_SESSION['ServerAddr'];

ob_start();
date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php');
$dbConn = new dbConn;
$conn = $dbConn->conn();

	//prepare로 바꿈 - by jsj 2019-05-29
	//$sql = 'select count(*) as cnt from '."?"  ;
	//$pstmt = $conn->stmt_init();
	//$pstmt = $conn->prepare($sql);
	//$pstmt->bind_param("1",$openBidSeq_tmp); 	//$fields = $g2bClass->bindAll($stmt);
	//$pstmt->execute();
	//$result0 = $stmt->get_result()
	//$stmt->close();

?>
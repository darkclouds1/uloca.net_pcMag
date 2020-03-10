<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

date_default_timezone_set('Asia/Seoul');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();
//echo 'bidNtceNo='.$bidNtceNo;
// 공고번호
if ($bidNtceNo != '') {
	$sql = "select * from openBidInfo where bidNtceNo='".$bidNtceNo."' ";
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("s", $qry, $qry);
	if (!$stmt->execute()) return $stmt->errno;
	//$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json1($stmt, $fields);
	
	echo ($json_string);
}
// 사업자등록번호
if ($compno != '') {
	$sql = "select * from openCompany where compno='".$compno."' ";
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("s", $qry, $qry);
	if (!$stmt->execute()) return $stmt->errno;
	//$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json1($stmt, $fields);
	
	echo ($json_string);
}
// sql
if ($sqls != '') {
	$sql = $sqls; //"select * from openCompany where compno='1010145888' ";
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);

	//$stmt->bind_param("s", $qry, $qry);
	if (!$stmt->execute()) return $stmt->errno;
	//$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json1($stmt, $fields);
	
	echo ($json_string);
}
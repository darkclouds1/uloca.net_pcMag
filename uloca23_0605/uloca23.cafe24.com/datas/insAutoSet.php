<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

//echo $_SERVER['DOCUMENT_ROOT'];

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); // '/g2b/classPHP/g2bClass.php'); //'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/include/util.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;

//echo '1';

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck();
//echo $mobile;

//echo '2';
$util = new util;



$tableDatas = "autoPubDatas";
$conn = $dbConn->conn();
// Check connection
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
} 
;
//$conn = $g2bClass->connDB();

$sql = 'select max(idx) idx from autoPubDatas';
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$idx = $row[idx];
	if ($idx == 'NULL') $idx = 0;
}
$idx   += 1;

$arr = 'idx, id, email, kwd, dminsttnm,searchType, sendType, katalk, cellphone';
$arr2 = $idx.',\''. $userid.'\',\''. $email.'\',\''. $kwd.'\',\''. $dminsttNm.'\',\''. $searchType.'\',\''. $sendType.'\',\''. $katalk.'\',\''. $cellphone.'\'';
$query  = "INSERT INTO $tableDatas ($arr) values ($arr2)";
//echo $query;
//$conn->query($query)
if ($conn->query($query) === TRUE) {
    //echo "저장 되었습니다.";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
	
	return;
}

$list = $dbConn->autoRecList($userid);

$conn->close();
//$g2bClass->closeDB($conn);
//echo '저장 되었습니다.'; //,'http://uloca.net/ulocawp/?page_id=353');
echo $list;
?>

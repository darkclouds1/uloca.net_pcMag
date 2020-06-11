
<?php
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
echo $_SERVER['DOCUMENT_ROOT'];

require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); // '/g2b/classPHP/g2bClass.php'); //'/classphp/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/include/util.php');
//include '/classphp/g2bClass.php'; //'/g2b/classPHP/g2bClass.php'; //
//include '/include/util.php';
echo '1';

//$g2bClass = new g2bClass;
//echo '2';
//$util = new util;
//$mobile = $g2bClass->MobileCheck();
//echo $mobile;

/*

$servername = "localhost";
$username = "uloca22";
$password = "w3m69p21!@";
$dbname = "uloca22";
$tableDatas = "autoPubDatas";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
} 

echo 'ok';
echo 'b4 conn';
$conn = $g2bClass->connDB();
echo 'after';
$sql = 'select max(idx) idx from autoPubDatas';
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$idx = $row[idx];
	if ($idx == 'NULL') $idx = 0;
}
$idx   += 1;
echo 'idx='.$idx;



$arr = 'idx, id, email, kwd, dminsttnm,searchType, sendType, katalk, cellphone';
$arr2 = $idx.',\''. $userid.'\',\''. $email.'\',\''. $kwd.'\',\''. $dminsttnm.'\',\''. $searchType.'\',\''. $sendType.'\',\''. $katalk.'\',\''. $cellphone.'\'';
$query  = "INSERT INTO $tableDatas ($arr) values ($arr2)";
echo $query;
//$conn->query($query)
if ($conn->query($query) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}
//$conn->close();
$g2bClass->closeDB($conn);
$util->sendRedirect('저장 되었습니다.','http://uloca.net/ulocawp/?page_id=353');
*/
?>


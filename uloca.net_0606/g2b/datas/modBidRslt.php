<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
//bidNtceNo, bidNtceOrd

$conn = $dbConn->conn(); 
mysqli_set_charset($conn, 'utf8');
$openBidSeq = 'openBidSeq_2018';
$openBidInfo = 'openBidInfo_2018';
$sql2 = 'select count(idx) as cnt from openBidSeq2 where bidIdx=0';
$result2 = $conn->query($sql2);
while ($row2 = $result2->fetch_assoc()) {
	echo 'remain count='.$row2['cnt'].'<br>';
}
$sql = 'select * from openBidSeq2 where bididx=0 order by idx limit 5000';
$result = $conn->query($sql);
echo $sql.'<br>';
$bidNtceNo = '';
while ($row = $result->fetch_assoc()) {
	$sql1 = "select * from openBidSeq2 where bidNtceNo='".$row['bidNtceNo']."' order by remark";
	//echo $sql1.'<br>';
	$result1 = $conn->query($sql1);
	if ($bidNtceNo != $row['bidNtceNo']) {
		$bidNtceNo = $row['bidNtceNo'];
		while ($row1 = $result1->fetch_assoc()) {
			//echo 'bidNtceNo='.$row1['bidNtceNo'].' tuchalrate='.$row1['tuchalrate'].' remark='.$row1['remark'].' bididx='.$row1['bidIdx'].'<br>';
			if ($row1['bidIdx'] != '0') $bididx = $row1['bidIdx'];
			else {
				$sql2 = "update openBidSeq2 set bidIdx = '".$bididx."' where bidNtceNo='".$row1['bidNtceNo']."'";
				$conn->query($sql2);
			}
		}
	}
}

?>
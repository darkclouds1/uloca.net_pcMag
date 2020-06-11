<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
//bidNtceNo, bidNtceOrd
$bidNtceNo='20181230275'; //20181238306';
$bidNtceOrd = '00';
$item1 = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd);
$cnt = count($item1);
	echo 'insertopenSeqInfo cnt='.$cnt.'/'.$bidNtceNo.'-'.$bidNtceOrd;
	exit;

$conn = new mysqli('localhost', 'uloca22', 'w3m69p21!@', 'uloca22');
mysqli_set_charset($conn, 'utf8');
$openBidSeq = 'openBidSeq_2018';
$openBidInfo = 'openBidInfo_2018';
if ($bidNtceNo == '') $bidNtceNo='2018012506001';
$bidNtceOrd='00';
/* $response1 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd); 
	$json1 = json_decode($response1, true);
	$item1 = $json1['response']['body']['items'];
	var_dump($response1);
*/
$compname = '%부산%';
$sql = "select  b.compname, b.repname from openCompany b where b.compname like ? order by b.compname asc";
//b.compname like \'%?%\'
$stmt = $conn->stmt_init();
$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $compname); //qry);

$stmt->execute();

//$result = $stmt->get_result();
$fields = bindAll($stmt);

while ($row = fetchRowAssoc($stmt, $fields)) {
    echo $row['compname'].'/'.$row['repname'].'<br>';
}
//var_dump($row);
//while ($data = $result->fetch_assoc()) {
//    echo $data['compname'];
//}
//(To get the numbers of returned rows you can use $stmt->num_rows.)
?>
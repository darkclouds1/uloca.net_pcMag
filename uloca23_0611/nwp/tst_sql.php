<?

require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();


$testPost = $_GET['testPost'];
echo "ln11::test=" .$testPost. "<br>";
// --------------------------------------------------------------
// date 비교
// --------------------------------------------------------------
$sql  = "SELECT COUNT(compno) AS CNT FROM openCompany WHERE 1";
$sql .= "   AND DATE(modifyDT) < DATE(now())";
$result = $conn->query($sql);
if ($result) {
	$row = $result->fetch_assoc();
	$sendEmailCnt = $row["CNT"];
}
echo ("ln20::CNT=" .$sendEmailCnt. "<br>");
//-----------------------------------------------



$sql =  " SELECT * FROM openBidInfo WHERE 1";
$sql .= "    AND bidNtceNo = '20200249391' ";
//$sql .= "    AND bidNtceOrd= '01' ";
$sql .= "   ORDER BY bidNtceOrd DESC ";

echo "----------------------------------------<br>";
echo $sql . "<br>";
echo "----------------------------------------<br>";
if ($dbResult = $conn->query($sql)) {
    echo "conn->query:: true<br>";
} else {
    echo "false<br>";
}
echo "----------------------------------------<br>";
$num_rows = $dbResult->num_rows;
echo "num_rows=" .$num_rows. "<br>";
echo "----------------------------------------<br>";
// fetch_assoc()
if ($row = $dbResult->fetch_assoc()) {
    echo "fetch_assoc():: true <br>";
    echo "bidNtceNo= " .$row['bidNtceNo']. "-";
    echo $row['bidNtceOrd'];

    $opengDt = $row['opengDt'];
    $timestamp = strtotime("-0 days"); // 개찰일시 > 오늘날짜-2일 :: 비교해서 날짜 도래하지 않으면 표시하지 않음
    echo ("<br>ln52::timestamp=" .$timestamp. "<br>");
    $timestamp = date("Y-m-d", $timestamp);
    $opengDt   = date("Y-m-d", strtotime($opengDt));
    echo ("<br>ln54::timestamp=" .$timestamp. ",     opengDt=" .$opengDt);

    if ($opengDt > $timestamp ) {
        echo "개찰일시가 도래하지 않았습니다.";
    } else {
    echo " <br>유찰되었거나 개찰이되지 않았습니다.";
    }

} else {
    echo "fetch_assoc():: false <br>";
}





?>
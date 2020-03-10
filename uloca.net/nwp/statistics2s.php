<?
@extract($_GET);
@extract($_POST);
session_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); //

$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
// --------------------------------- log
$rmrk = 'chart보기'; // '조건검색';
$dbConn->logWrite($resudi,$_SERVER['REQUEST_URI'],$rmrk);
// --------------------------------- log
//$frrange, $torange
if ($frrange == '') $frrange =75;
if ($torange == '') $torange =99;
$range = 1;
/* if ($torange - $frrange <= 4) $range = 2;
if ($torange - $frrange <= 0.6) $range = 3;
if ($torange - $frrange <= 0.06) $range = 4;
if ($torange - $frrange <= 0.006) $range = 5; */
$range = $curpos+1;
if ($bidthing == '' && $bidcnstwk == '' && $bidservc == '') $bidall=1;
if ($bidthing == 1) $pss = '입찰물품';
if ($bidcnstwk == 1) $pss = '입찰공사';
if ($bidservc == 1) $pss = '입찰용역';
$today = Date('Y-m-d');

if ($p1w == '' && $p1m == '' && $p6m == '' && $p1y == '') $pall=1;
if ($p1w == '1') $timestamp = strtotime("-7 day");
else if ($p1m == '1') $timestamp = strtotime("-1 month");
else if ($p6m == '1') $timestamp = strtotime("-6 month");
else if ($p1y == '1') $timestamp = strtotime("-1 year");
else if ($pall == '1') $timestamp = strtotime("-10 year");

$startDate = date("Y-m-d", $timestamp);
/*
$sql = "select ROUND(b.tuchalrate1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1)>=? and ROUND(b.tuchalrate1)<=? group by ROUND(b.tuchalrate1);";
//$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) $tr = $row['tr'];
$sql = "select bidNtceNo from openBidInfo where opengDt <= '".$today."' and substr(bidNtceNo,1,2) = '20' order by opengDt desc limit 0,1";
$result = $conn->query($sql);
$bidNtceNo2 = '';
if ($row = $result->fetch_assoc()) $bidNtceNo2 = $row['bidNtceNo'];
echo $today.'/'.$startDate.'/'.$bidNtceNo1.'/'.$bidNtceNo2;
//exit;
*/
$dot = $curpos;
$qry = '';
if ($kwd != '') $qry .= " and bidNtceNm like '%".$kwd."%' ";
if ($dminsttNm != '') $qry .= " and dminsttNm like '%".$dminsttNm."%' ";
if ($bidall == 1) { // 통합
	$sql = "select ROUND(sucsfbidRate,".$dot.") tr, count(idx) cnt  from openBidInfo where substr(opengDt,1,10) >= '".$startDate."' and substr(opengDt,1,10) <= '".$today."' and substr(bidNtceNo,1,2) = '20' ".$qry." and sucsfbidRate>=".$frrange." and sucsfbidRate<=".$torange." group by ROUND(sucsfbidRate,".$dot.")";
/*
select ROUND(a.sucsfbidRate,0) tr, count(a.idx) cnt from openBidInfo a where substr(a.opengDt,1,10) >= '2008-12-31' and substr(a.opengDt,1,10) <= '2018-12-31' and substr(a.bidNtceNo,1,2) = '20' and a.sucsfbidRate>=85.1 and a.sucsfbidRate<=90.2 group by ROUND(a.sucsfbidRate,0)

	if ($range == 1) {
		
		//$sql = "select ROUND(b.tuchalrate1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1)>=? and ROUND(b.tuchalrate1)<=? group by ROUND(b.tuchalrate1)";
		//select ROUND(a.sucsfbidRate) tr, count(b.idx) cnt  from openBidInfo a where substr(a.opengDt,1,10) >= '2018-01-01' and substr(a.opengDt,1,10) <= '2018-12-31' and substr(a.bidNtceNo,1,2) = '20' and ROUND(a.sucsfbidRate)>=80 and ROUND(a.sucsfbidRate)<=90 group by ROUND(a.sucsfbidRate)
		$sql = "select ROUND(a.sucsfbidRate) tr, count(b.idx) cnt  from openBidInfo a where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and ROUND(a.sucsfbidRate)>=? and ROUND(a.sucsfbidRate)<=? group by ROUND(a.sucsfbidRate)";
	} else if ($range == 2) {
		//$sql = 'select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,1)>=? and ROUND(tuchalrate1,1)<=? group by ROUND(tuchalrate1,1)';
		$sql = "select ROUND(b.tuchalrate1,1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,1)>=? and ROUND(b.tuchalrate1,1)<=? group by ROUND(b.tuchalrate1,1)";
	} else if ($range == 3) {
		//$sql = 'select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,2)>=? and ROUND(tuchalrate1,2)<=? group by ROUND(tuchalrate1,2)';
		$sql = "select ROUND(b.tuchalrate1,2) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,2)>=? and ROUND(b.tuchalrate1,2)<=? group by ROUND(b.tuchalrate1,2)";
	} else if ($range == 4) {
		//$sql = 'select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,3)>=? and ROUND(tuchalrate1,3)<=? group by ROUND(tuchalrate1,3)';
		$sql = "select ROUND(b.tuchalrate1,3) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,3)>=? and ROUND(b.tuchalrate1,3)<=? group by ROUND(b.tuchalrate1,3)";
	} else if ($range == 5) {
		//$sql = 'select ROUND(tuchalrate1,4) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,4)>=? and ROUND(tuchalrate1,4)<=? group by ROUND(tuchalrate1,4)';
		$sql = "select ROUND(b.tuchalrate1,4) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,4)>=? and ROUND(b.tuchalrate1,4)<=? group by ROUND(b.tuchalrate1,4)";
	} else if ($range == 6) {
		//$sql = 'select ROUND(tuchalrate1,5) tr, count(*) cnt from forecastData where ROUND(tuchalrate1,5)>=? and ROUND(tuchalrate1,5)<=? group by ROUND(tuchalrate1,5)';
		$sql = "select ROUND(b.tuchalrate1,5) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,5)>=? and ROUND(b.tuchalrate1,5)<=? group by ROUND(b.tuchalrate1,5)";
	}
	*/
	//echo $sql.$frrange. $torange;
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	//$stmt->bind_param("dd", $frrange, $torange);
}

else { // 물품,공사,용역
	$sql = "select ROUND(sucsfbidRate,".$dot.") tr, count(idx) cnt  from openBidInfo where bidtype = '".$pss."' and substr(opengDt,1,10) >= '".$startDate."' and substr(opengDt,1,10) <= '".$today."' and substr(bidNtceNo,1,2) = '20' ".$qry." and sucsfbidRate>=".$frrange." and sucsfbidRate<=".$torange." group by ROUND(sucsfbidRate,".$dot.")";
	/*if ($range == 1) {
		//$sql = 'select ROUND(tuchalrate1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1)>=? and ROUND(tuchalrate1)<=? group by ROUND(tuchalrate1)';
		$sql = "select ROUND(b.tuchalrate1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1)>=? and ROUND(b.tuchalrate1)<=? group by ROUND(b.tuchalrate1)";
	} else if ($range == 2) {
		//$sql = 'select ROUND(tuchalrate1,1) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,1)>=? and ROUND(tuchalrate1,1)<=? group by ROUND(tuchalrate1,1)';
		$sql = "select ROUND(b.tuchalrate1,1) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,1)>=? and ROUND(b.tuchalrate1,1)<=? group by ROUND(b.tuchalrate1,1)";
	} else if ($range == 3) {
		//$sql = 'select ROUND(tuchalrate1,2) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,2)>=? and ROUND(tuchalrate1,2)<=? group by ROUND(tuchalrate1,2)';
		$sql = "select ROUND(b.tuchalrate1,2) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,2)>=? and ROUND(b.tuchalrate1,2)<=? group by ROUND(b.tuchalrate1,2)";
	} else if ($range == 4) {
		//$sql = 'select ROUND(tuchalrate1,3) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,3)>=? and ROUND(tuchalrate1,3)<=? group by ROUND(tuchalrate1,3)';
		$sql = "select ROUND(b.tuchalrate1,3) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,3)>=? and ROUND(b.tuchalrate1,3)<=? group by ROUND(b.tuchalrate1,3)";
	} else if ($range == 5) {
		//$sql = 'select ROUND(tuchalrate1,4) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,4)>=? and ROUND(tuchalrate1,4)<=? group by ROUND(tuchalrate1,4)';
		$sql = "select ROUND(b.tuchalrate1,4) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,4)>=? and ROUND(b.tuchalrate1,4)<=? group by ROUND(b.tuchalrate1,4)";
	} else if ($range == 6) {
		//$sql = 'select ROUND(tuchalrate1,5) tr, count(*) cnt from forecastData where pss = ? and ROUND(tuchalrate1,5)>=? and ROUND(tuchalrate1,5)<=? group by ROUND(tuchalrate1,5)';
		$sql = "select ROUND(b.tuchalrate1,5) tr, count(b.idx) cnt  from openBidInfo a, forecastData b where pss = ? and substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and a.bidNtceNo=b.bidNtceNo and ROUND(b.tuchalrate1,5)>=? and ROUND(b.tuchalrate1,5)<=? group by ROUND(b.tuchalrate1,5)";
	} */
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	//$stmt->bind_param("sdd", $pss,$frrange, $torange);
}

//echo $sql;
	if (!$stmt->execute()) return $stmt->errno;
	$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json11($stmt, $fields);
	//$json = json_decode($json_string, true);
	echo $json_string;

?>
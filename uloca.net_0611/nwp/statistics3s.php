<?
@extract($_GET);
@extract($_POST);
// -----------------------------------------------------------------
// statistics3s.php	2018-12-20
// -----------------------------------------------------------------
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); //

$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"
	$key = '05';
	$pss = '';
// --------------------------------- log
$rmrk = 'chart보기'; // '조건검색';
$dbConn->logWrite2($id,$_SERVER['REQUEST_URI'],$rmrk,$pss,$key);
// --------------------------------- log
//$frrange, $torange
if ($frrange == '') $frrange =75;
if ($torange == '') $torange =99;
$range = 1;
/* if ($torange - $frrange <= 4) $range = 2;
if ($torange - $frrange <= 0.6) $range = 3;
if ($torange - $frrange <= 0.06) $range = 4;
if ($torange - $frrange <= 0.006) $range = 5; */
if ($curpos == '') $range = 1;
else $range = $curpos+1;
if ($bidthing == '' && $bidcnstwk == '' && $bidservc == '') $bidall=1;
$pss = '';
if ($bidthing == 1) $pss = '입찰물품';
if ($bidcnstwk == 1) $pss = '입찰공사';
if ($bidservc == 1) $pss = '입찰용역';
$today = Date('Y-m-d');

if ($p1w == '' && $p1m == '' && $p6m == '' && $p1y == '' && $pall == '') $pall=1;
if ($p1w == '1') $timestamp = strtotime("-7 day");
else if ($p1m == '1') $timestamp = strtotime("-1 month");
else if ($p6m == '1') $timestamp = strtotime("-6 month");
else if ($p1y == '1') $timestamp = strtotime("-1 year");
else if ($pall == '1') $timestamp = strtotime("-10 year");

$startDate = date("Y-m-d", $timestamp);

$sql2 = '1=1 ';
	if ($pss != '') $sql2 .= "and bidtype = '".$pss."' ";
	if ($kwd != '') $sql2 .= "and bidNtceNm like '%".$kwd."%' ";
	if ($dminsttNm != '') $sql2 .= "and dminsttNm like '%".$dminsttNm."%' ";


if ($bidall == 1) { // 통합

// 723469
$sql = "SELECT ROUND(sucsfbidRate) tr, count(idx) cnt FROM `openBidInfo` WHERE ".$sql2." and opengDt != '' and sucsfbidRate >=75 and sucsfbidRate <=99 group by ROUND(sucsfbidRate) ";
//and opengDt like '2018%';
/*
$sql = "select ROUND(a.sucsfbidRate,".$dot.") tr, count(a.idx) cnt  from openBidInfo a where substr(a.opengDt,1,10) >= '".$startDate."' and substr(a.opengDt,1,10) <= '".$today."' and substr(a.bidNtceNo,1,2) = '20' and ROUND(a.sucsfbidRate)>=".$frrange." and ROUND(a.sucsfbidRate)<=".$torange." group by ROUND(a.sucsfbidRate,".$dot.")";

$sql = "select tr, sum(cnt) cnt from ( ";
$sql .= "select 2019 yr, ROUND(b.tuchalrate) tr, count(b.idx) cnt  from  openBidSeq_2019 b where b.remark='1' and  ROUND(b.tuchalrate)>=80 and ROUND(b.tuchalrate)<=95 group by ROUND(b.tuchalrate) ";
$sql .= "union ";
$sql .= "select 2018 yr, ROUND(b.tuchalrate) tr, count(b.idx) cnt  from  openBidSeq_2018 b where b.remark='1' and  ROUND(b.tuchalrate)>=80 and ROUND(b.tuchalrate)<=95 group by ROUND(b.tuchalrate) ";
$sql .= "union ";
$sql .= "select 2017 yr, ROUND(b.tuchalrate) tr, count(b.idx) cnt  from  openBidSeq_2017 b where b.remark='1' and  ROUND(b.tuchalrate)>=80 and ROUND(b.tuchalrate)<=95 group by ROUND(b.tuchalrate) ";
$sql .= "union ";
$sql .= "select 2016 yr, ROUND(b.tuchalrate) tr, count(b.idx) cnt  from  openBidSeq_2016 b where b.remark='1' and  ROUND(b.tuchalrate)>=80 and ROUND(b.tuchalrate)<=95 group by ROUND(b.tuchalrate) ";
$sql .= ") x group by tr "; */
} else { // 물품,공사,용역	kwd=bidNtceNm 입찰공고명 dminsttNm 수요기관명
	/* $sql2 = '1=1 ';
	if ($pss != '') $sql2 .= "and bidtype = '".$pss."' ";
	if ($kwd != '') $sql2 .= "and bidNtceNm like '%".$kwd."%' ";
	if ($dminsttNm != '') $sql2 .= "and dminsttNm like '%".$dminsttNm."%' "; */

$sql = 'SELECT ROUND(sucsfbidRate) tr, count(idx) cnt FROM `openBidInfo` WHERE '.$sql2.' and sucsfbidRate >=80 and sucsfbidRate <=95 group by ROUND(sucsfbidRate) ';
/*
SELECT ROUND(sucsfbidRate) tr, count(idx) cnt FROM `openBidInfo` WHERE '.$sql2.' and sucsfbidRate >=86 and sucsfbidRate <=89 group by ROUND(sucsfbidRate) 

$sql = "select tr, sum(cnt) cnt from ( ";
$sql .= "select 2019 yr, ROUND(b.tuchalrate,1) tr, count(b.idx) cnt  from openBidInfo a, openBidSeq_2019 b where ".$sql2." and b.remark='1' and a.bidNtceNo=b.bidNtceNo and  ROUND(b.tuchalrate,1)>=86 and ROUND(b.tuchalrate,1)<=89 group by ROUND(b.tuchalrate,1) ";
$sql .= "union ";
$sql .= "select 2018 yr, ROUND(b.tuchalrate,1) tr, count(b.idx) cnt  from openBidInfo a, openBidSeq_2018 b where ".$sql2." and b.remark='1' and a.bidNtceNo=b.bidNtceNo and  ROUND(b.tuchalrate,1)>=86 and ROUND(b.tuchalrate,1)<=89 group by ROUND(b.tuchalrate,1) ";
$sql .= "union ";
$sql .= "select 2017 yr, ROUND(b.tuchalrate,1) tr, count(b.idx) cnt  from openBidInfo a, openBidSeq_2017 b where ".$sql2." and b.remark='1' and a.bidNtceNo=b.bidNtceNo and  ROUND(b.tuchalrate,1)>=86 and ROUND(b.tuchalrate,1)<=89 group by ROUND(b.tuchalrate) ";
$sql .= "union ";
$sql .= "select 2016 yr, ROUND(b.tuchalrate,1) tr, count(b.idx) cnt  from openBidInfo a, openBidSeq_2016 b where ".$sql2." and b.remark='1' and a.bidNtceNo=b.bidNtceNo and  ROUND(b.tuchalrate,1)>=86 and ROUND(b.tuchalrate,1)<=89 group by ROUND(b.tuchalrate) ";
$sql .= ") x group by tr "; */



}
	//echo $sql;
	$stmt = $conn->stmt_init();
	$stmt = $conn->prepare($sql);
	//$stmt->bind_param("dd", $frrange, $torange);

	//echo $sql;
	//exit;
	if (!$stmt->execute()) return $stmt->errno;
	//$rowCount = $stmt->num_rows;
	$fields = $g2bClass->bindAll($stmt);
	
	$json_string = $g2bClass->rs2Json11($stmt, $fields);
	//$json = json_decode($json_string, true);
	echo $json_string;

?>
<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn(); 

mysqli_set_charset($conn, 'utf8');
$openBidInfo = 'openBidInfo';
//if ($bidNtceNo == '') $bidNtceNo='2018012506001';
//$bidNtceOrd='00';
// ALTER TABLE forecastData ADD UNIQUE KEY (bidNtceNo) select * from forecastData where bidNtceNo='20180905461'

$lastCnt = '212805';
$start = 0;
$cntonce= 200;
$sql = "select last from workdate where workname='forecastDataCount' ";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
	$start = $row['last'];
}
if ($start>=$lastCnt) exit;
?>
<body onLoad='doagain()'>
<br>
<center><a onclick='doagain();'><input type=button value=' 실 행 '></a>
<input type="checkbox" name="cont" id="cont" <? if ($cont == 1) echo 'checked="checked"' ?> >계속
<input type="text" id="rowCnt" size="10" style='text-align:right' value='<?=number_format($lastCnt)?>'> 건 info data
</center><br><br>
<script>
var cont = document.getElementById('cont');
var rowCnt = <?=$lastCnt?>;
var startC = <?=$start?>;
function doagain() {
	if ( rowCnt < startC) return;
	if (cont.checked) location.href='getForecastCount.php?cont=1';
	
	//else location.href='getForecast.php';
}
//if (document.getElementById('cont').checked) doagain();
</script>
<?
function updateForecastCount($conn,$bidNtceNo, $tuchalcnt) {
	
		$sql = "update forecastData set tuchalcnt='".$tuchalcnt."' where bidNtceNo='".$bidNtceNo."' ";
		$conn->query($sql);

		return true;
	
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}
// --------------------------------------- function insertopenBidInfo


echo "start=".$start." / lastCnt=".$lastCnt." / cntonce=".$cntonce." ";

$sql = "select a.bidNtceNo,a.prtcptCnum,b.tuchalCnt from openBidInfo a,forecastData b where b.tuchalCnt=0 ";
$sql .= "and a.opengDt<='2018-12-07' and b.bidNtceNo=a.bidNtceNo and b.idx<=296907 order by b.idx limit ".$start.", ".$cntonce;
//select a.bidNtceNo,a.prtcptCnum,b.tuchalCnt from openBidInfo a,forecastData b where b.tuchalCnt=0 
//and a.opengDt<='2018-12-07' and b.bidNtceNo=a.bidNtceNo and b.idx<=212805 limit 0,200
echo $sql.'<br>';
exit;
$result = $conn->query($sql);
$num_rows = mysqli_num_rows($result);
echo ' num_rows='.$num_rows.'<br>';
$numOfRows=999;
$pageNo='';
$inqDiv = 3;
$inqryBgnDt='';
$inqryEndDt='';
$bidNtceOrd='';
while ($row = $result->fetch_assoc()) {
	$bidNtceNo = $row['bidNtceNo'];
	if ($prtcptCnum > 0) {
		$tuchalcnt = $prtcptCnum;
	} else {
	$tuchalcnt = $g2bClass->getRsltDataTotalCount($bidNtceNo,$bidNtceOrd); //$json1['response']['body']['totalCount']; //count($item1); 714620 이하 999 체크
	}
	if ($tuchalcnt == 0) continue;
	updateForecastCount($conn,$bidNtceNo,$tuchalcnt);
	
	echo 'bidNtceNo='.$bidNtceNo.' tuchalcnt='.$tuchalcnt.'<br>';
}
$start += $num_rows; 
$sql = "update workdate set last='".$start."' where workname='forecastDataCount' ";
$conn->query($sql);

?>

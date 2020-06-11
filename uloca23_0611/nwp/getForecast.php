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
$sql = "select count(*) cnt from openBidInfo ";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$rowCnt = $row['cnt'];

$start = 0;
$cntonce= 200;
$sql = "select last from workdate where workname='forecastData' ";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
	$start = $row['last'];
}
?>
<body onLoad='doagain()'>
<br>
<center><a onclick='doagain();'><input type=button value=' 실 행 '></a>
<input type="checkbox" name="cont" id="cont" <? if ($cont == 1) echo 'checked="checked"' ?> >계속
<input type="text" id="rowCnt" size="10" style='text-align:right' value='<?=number_format($rowCnt)?>'> 건 info data
</center><br><br>
<script>
var cont = document.getElementById('cont');
var rowCnt = <?=$rowCnt?>;
var startC = <?=$start?>;
function doagain() {
	if ( rowCnt < startC) return;
	if (cont.checked) location.href='getForecast.php?cont=1';
	
	//else location.href='getForecast.php';
}
//if (document.getElementById('cont').checked) doagain();
</script>
<?
function insertForecastInfo($conn,$bidNtceNo, $compno1, $tuchalrate1,$tuchalamt1,$compno2, $tuchalrate2,$tuchalamt2,$tuchalcnt,$pss) {
	
		//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql = 'insert into forecastData ( bidNtceNo, compno1, tuchalrate1,tuchalamt1,compno2, tuchalrate2,tuchalamt2,tuchalcnt,pss)';

		$sql .= "VALUES ( '".$bidNtceNo."', '". $compno1 ."', '" .$tuchalrate1. "','" . $tuchalamt1 ."', '". $compno2 ."', '" .$tuchalrate2. "','" . $tuchalamt2 .  "','" . $tuchalcnt .  "','" . $pss . "')";

		//	echo($sql);
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else echo 'error sql='.$sql.'<br>';
		}
		return true;
	
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}
// --------------------------------------- function insertopenBidInfo


echo "start=".$start." / rowCnt=".$rowCnt." / cntonce=".$cntonce." ";
if ($start >= $rowCnt) exit;
$sql = "select bidNtceNo,bidtype from openBidInfo order by idx asc limit ".$start.", ".$cntonce;

//echo $sql.'<br>';
//exit;
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
	$t = 'alpha';
	if (is_numeric($bidNtceNo)) $t = 'num';
	$lth = strlen($bidNtceNo);
	if ($t != 'num' || $lth != 11) continue;
	//echo $row['bidNtceNo'].'/'.$t.'/'.$lth.'/pss='.$row['bidtype'].'<br>'; // lth = 11 $t = num
	//$bidNtceNo = '20180509427';
	$pss = $row['bidtype'];
	//$response = $g2bClass->getBidRslt2($numOfRows,$pageNo,$inqryDiv,$inqryBgnDt,$inqryEndDt,$pss,$bidNtceNo);
	//$item1 = $json1['response']['body']['items'];
	//$totalCnt = $g2bClass->getRsltDataTotalCount($bidNtceNo,$bidNtceOrd) ;
	//select count(*) from forecastData where tuchalcnt = 999
	
	//var_dump($response);
	//bidNtceNo, compno1, tuchalrate1,tuchalamt1,compno1, tuchalrate1,tuchalamt1,pss
	$compno1='';
	$tuchalrate1='';
	$tuchalamt1='';
	$compno2='';
	$tuchalrate2='';
	$tuchalamt2='';
	$tuchalcnt = $g2bClass->getRsltDataTotalCount($bidNtceNo,$bidNtceOrd); //$json1['response']['body']['totalCount']; //count($item1); 714620 이하 999 체크
	if ($tuchalcnt == 0) continue;
	$item1 = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd);
	//$json1 = json_decode($response, true);
	//$item1 = $json1['items']; // $json1['response']['body']['items'];
	if (count($item1) == 0) continue;
	foreach($item1 as $arr ) {
		if ($arr['opengRank'] == '1') {
			$compno1 = $arr['prcbdrBizno'];
			$tuchalrate1 = $arr['bidprcrt'];
			$tuchalamt1 = $arr['bidprcAmt'];
		}
		if ($arr['rmrk'] == '낙찰하한선 미달' && (int)$tuchalamt2 < (int)$arr['bidprcAmt']) {
			$compno2 = $arr['prcbdrBizno'];
			$tuchalrate2 = $arr['bidprcrt'];
			$tuchalamt2 = $arr['bidprcAmt'];
		}
		//$i++;
	} 
	insertForecastInfo($conn,$bidNtceNo, $compno1, $tuchalrate1,$tuchalamt1,$compno2, $tuchalrate2,$tuchalamt2,$tuchalcnt,$pss);
	echo 'bidNtceNo='.$bidNtceNo.' compno1='. $compno1.' tuchalrate1='. $tuchalrate1.' tuchalamt1='.$tuchalamt1.' compno2='. $compno2.' tuchalrate2='. $tuchalrate2.' tuchalamt2='.$tuchalamt2.' tuchalcnt='.$tuchalcnt.' count='.count($item1).' pss='.$pss.'<br>';
}
$start += $num_rows; 
$sql = "update workdate set last='".$start."' where workname='forecastData' ";
$conn->query($sql);

?>

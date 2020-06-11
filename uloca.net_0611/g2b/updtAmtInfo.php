<?
@extract($_GET);
@extract($_POST);
// updtAmtInfo.php
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
// uloca23.cafe24.com/g2b/updtAmtInfo.php
$g2bClass = new g2bClass;
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 

$dbConn = new dbConn;

$conn = $dbConn->conn();

function updateopenBidInfo($conn,$openBidInfo,$idx,$arr,$bidNtceNo) {
	//echo '<br>'.$bidNtceNo .'/'. $arr['bidNtceNo'];
	//if ($bidNtceNo == $arr['bidNtceNo']) return $bidNtceNo;
	//$bidNtceNo = $arr['bidNtceNo'];
	
	$sql = "update openBidInfo set rsrvtnPrce = '". $arr['plnprc'] . "', ";
	$sql .= "bssAmt = '". $arr['bssamt'] . "', ";
	$sql .= "ModifyDT = now() ";
	$sql .=" where bidNtceNo='".$bidNtceNo."';";
	echo $sql.'<br>';
	$conn->query($sql);
		
	return $bidNtceNo; // update
}
function updateInfo($conn,$g2bClass,$bidNtceNo,$pss) {
	//$bidNtceNo='20190222287';
	//$pss='물품';
	if ($pss == '입찰물품') $bidrdo = 'opnbidThng'; //$bsnsDivCd = '1'; // 물품
	else if ($pss == '입찰공사') $bidrdo = 'opnbidCnstwk'; //$bsnsDivCd = '1'; // 물품
	else if ($pss == '입찰용역') $bidrdo = 'opnbidservc'; //$bsnsDivCd = '1'; // 물품
	$itemr = $g2bClass->getSvrDataOpn($bidrdo,$bidNtceNo); 
	echo ($itemr.'<br>');
	$json1 = json_decode($itemr, true);
			//$iRowCnt = $json1['response']['body']['totalCount'];
	$items = $json1['response']['body']['items'];
	if (count($items)>0) {
		foreach($items as $arr ) {
			// ------------------------------ update
			$bidNtceNo1 = updateopenBidInfo($conn,$openBidInfo,$idx,$arr,$bidNtceNo);
		}
	}
}
$sql = "select last from workdate where workname='updateamt' ";
		$result = $conn->query($sql);
		//echo $sql;
		$cnt = 0;
		if ($row = $result->fetch_assoc()) $last = $row['last'];

// idx = 1801160
echo '<br>last index='.$last.'<br>';
$sql = "select idx,bidNtceNo,bidtype pss, rsrvtnPrce,bssAmt from openBidInfo where idx < '".$last."' and substr(bidtype,1,2)='입찰' and (rsrvtnPrce = '' or bssAmt = '') order by idx desc limit 0,500";
$result = $conn->query($sql);
$idx = 0;
$i = 0;
while ($row = $result->fetch_assoc()) {
	$idx = $row['idx'];
	updateInfo($conn,$g2bClass,$row['bidNtceNo'],$row['pss']);
}
$sql = "update workdate set last='".$idx."' where workname='updateamt' ";
$conn->query($sql);

?>
<body onload='refresh();'>
<script>
function refresh() {
	console.log('끝났습니다. 다시 반복합니다. lastidx='+<?=$idx?>);
	location.href='/g2b/updtAmtInfo.php';
}
</script>
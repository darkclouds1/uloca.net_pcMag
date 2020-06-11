<?
@extract($_GET);
@extract($_POST);
//info 읽고 seq-api 에서 보완



require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$conn = $dbConn->conn(); 


if ($openBidInfo == '') $openBidInfo = 'openBidInfo';
if ($openBidSeq == '') $openBidSeq = 'openBidSeq_2018';
//if ($lastdt == '') $lastdt= '2018-07-01 00:00:00';
/*
if ($startDate == '') {
	$sql = 'select max(opengDt) as startDate from '.$openBidInfo  ;
	$result0 = $conn->query($sql);
	if ($row = $result0->fetch_assoc()) {
		$startDate = $row['startDate'];
		if ($startDate == 'NULL' || $startDate == '') $startDate = '2018-07-01 00:00';
	} else $startDate = '2018-07-01 00:00';
}
$timestamp = strtotime($startDate);
$timestamp = strtotime("+0 days",$timestamp);

$endDate = date('YmdHi',$timestamp); //'20180720';
$endDate = substr($endDate,0,4).'-'.substr($endDate,4,2).'-'.substr($endDate,6,2).' 23:59';
//echo 'startDate='.$startDate.' endDate='.$endDate;
$startDate = substr($startDate,0,16);
*/
$dur = $g2bClass->dt2duration($startDate);
$openBidInfo = 'openBidInfo'; //.$dur;
$openBidSeq = 'openBidSeq_'.$dur;
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="http://uloca.net/g2b/css/g2b.css" />
<link rel="stylesheet" href="http://uloca.net/jquery/jquery-ui.css">
<script src="http://uloca.net/jquery/jquery.min.js"></script>
<script src="http://uloca.net/jquery/jquery-ui.min.js"></script>
<script src="http://uloca.net/g2b/g2b.js"></script>
<script>
function doit() {
	frm = document.myForm;
	if (frm.startDate.value == '' || frm.endDate.value == '')
	{
		alert('기간을 입력하세요.');
		return;
	}
	frm.dodo.value="do";
	frm.submit();
}
function donextday() {
	frm = document.myForm;
	if (frm.startDate.value >= '<?=$lastdt?>') return;
	//alert(frm.contn.checked);
	if (frm.contn.checked) {
		dts=dateAddDel(frm.startDate.value, 1, 'd');
		frm.startDate.value = dts;
		frm.endDate.value = dts;
		frm.submit();
	} else {frm.contn.checked = false;
		return;
	}
}
</script>
<body  onload="javascript:donextday();">
<center>info 읽고 seq-api 에서 보완</center>
<form action="dailySeqFill.php" name="myForm" id="myform" method="post" >
<input type="hidden" name="dodo" id="dodo" value="">
<div id="contents">
<div class="detail_search" >


<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>

		<tr>
				<th>기간</th>
				<td>
					<div class="calendar">
						
						<input autocomplete="off" type="text" maxlength="20" name="startDate" id="startDate" value="<?=$startDate?>" style="width:120px;" onchange='document.getElementById("endDate").value=this.value'/>
						
						~
						<input autocomplete="off" type="text" maxlength="20" name="endDate" id="endDate" value="<?=$endDate?>" style="width:120px;" /> 
						<!-- input type="checkbox" name="contn" id="contn" <? if ($contn=='on') {?>checked=checked <? } ?> >계속 -->
						<div id="datepicker"></div>	
				</div> 
				</td>
			</tr>
		</table>
		<div class="btn_area">
		<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
		<a onclick="doit();" class="search">실행</a>
	</div>	
	</div>
	</div>
</form>

<?
//echo 'do='.$dodo;
if($dodo != 'do')	exit;

if ($idx0 == '') {
	$sql = 'select max(bidIdx) as idx from '.$openBidSeq  ;
	$result0 = $conn->query($sql);
	if ($row = $result0->fetch_assoc()) {
		$idx0 = $row['idx'];
		if ($idx0 == 'NULL') $idx0 = 0;
	}
}
$idx0 ++;
//$idx0=58667; //45478; //34473; //34290;
$sql = 'select max(idx) as idx from '.$openBidSeq  ;
$result0 = $conn->query($sql);
if ($row = $result0->fetch_assoc()) {
	$idx = $row['idx'];
	if ($idx == 'NULL') $idx = 0;
}

$idx ++;
//$idx ++;



$start = 0;
//$idx = 0;
$cnt=0;
$startDate1 = $startDate . ' 00:00';
$endDate1 = $endDate . ' 23:59';
$sql = 'select idx, opengDt, bidNtceNo,bidNtceOrd , bidwinnrNm ,prtcptCnum from '.$openBidInfo.' where opengDt>=\''.$startDate1.'\' and opengDt<=\'' . $endDate1. '\' order by idx'; // limit '.$norec ;
echo $sql.'<br>';
//exit;
$result = $conn->query($sql);
$tCnt=0;
$i=1;
?>
<div id='totalRecords'>total records=</div>

<table class="type10" id="specData">
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="15%;">공고번호</th>
        <th scope="cols" width="25%;">낙찰업체명</th>
        <th scope="cols" width="10%;">참가업체수1</th>
        <th scope="cols" width="10%;">참가업체수2</th>
		<th scope="cols" width="10%;">비고</th>
    </tr>
<?

while ($row = $result->fetch_assoc()) {
	$bidNtceNo = $row['bidNtceNo'];
	$bidNtceOrd = ''; //$row['bidNtceOrd'];
	//$bididx = $bididx;
	
	// 입찰 결과
	$response1 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd); 
	$json1 = json_decode($response1, true);
	$item1 = $json1['response']['body']['items'];
	$cnt = count($item1);


	foreach ($item1 as $key => $rowk) {
		//$rmrk[$key]  = $rowk['rmrk'];
		$tuchalamt[$key]  = $rowk['tuchalamt'];
	} 
	if ($cnt>2) array_multisort( $tuchalamt, SORT_ASC, $item1); //$rmrk, SORT_ASC, SORT_STRING, $tuchalamt, SORT_ASC,SORT_NUMERIC, $item1);

/*	$rmrk  = array_column($item1, 'rmrk');
	$tuchalamt = array_column($item1, 'tuchalamt');
	array_multisort($rmrk, SORT_ASC, SORT_STRING, $tuchalamt, SORT_ASC,SORT_NUMERIC, $item1); // SORT_DESC
*/
	//var_dump($response1);
	//echo 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' bidNtceOrd='.$bidNtceOrd.' count='.$cnt.'<br>';
	$msg .= 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' count='.$cnt.'<br>';
	
	$ii=1;
	$tcnt += $cnt;
	//echo $cnt;
	$tr = '<tr>';
	$tr .= '<td>'.$i.'</td>';
	$tr .= '<td style="text-align:center;">'.$row['bidNtceNo'].'</td>';
	$tr .= '<td>'.$row['bidwinnrNm'].'</td>';
	$tr .= '<td style="text-align:right;">'.$row['prtcptCnum'].'</td>';
	$tr .= '<td style="text-align:right;">'.$cnt.'</td>';
	$tr .= '<td > </td>';
	$tr . '</tr>';
	echo $tr;
	if ($cnt>0) {
	foreach($item1 as $arr ) {
		$idx   += 1;
		$rmrk = addslashes($arr['rmrk']);
		if (trim($rmrk) == '') $rmrk = $ii; // 1순위 낙찰

		// $openBidInfo 에 1순위 정보 추가 
		if ($ii == 1) {
			$sql = "select bidwinnrBizno  from ".$openBidInfo." where bidNtceNo = '".$arr['bidNtceNo']."' ";
			$result0 = $conn->query($sql);
			//echo $sql;
			if ($row0 = $result0->fetch_assoc()) {
				if ($row0['bidwinnrBizno'] == '') {
					$sql = "update ".$openBidInfo." set ";
					$sql .= " prtcptCnum = '". $cnt . "', ";
					$sql .= "bidwinnrNm = '". addslashes($arr['prcbdrNm']) . "', bidwinnrBizno = '". $arr['prcbdrBizno'] . "', ";
					$sql .= "sucsfbidAmt = '". $arr['tuchalamt'] . "', sucsfbidRate = '". $arr['tuchalrate'] . "', ";
					$sql .= "rlOpengDt = '". $arr['tuchaldatetime'] . "', bidwinnrCeoNm = '". $arr['prcbdrCeoNm'] . "' ";

					$sql .=" where bidNtceNo='".$arr['bidNtceNo']."';";
					$conn->query($sql);
					//echo $sql;
				}
			}
		}
		$bididx=-1;
		//$sql = "select count(*) as cnt  from ".$openBidSeq." where bidNtceNo = '".$arr['bidNtceNo']."' ";
		//	$result0 = $conn->query($sql);
			//echo $sql;
		//	if ($row0 = $result0->fetch_assoc()) {
				//$ccnt = $row0['cnt'];
				//if ($ccnt >0) {

				//} else {

				$sql = 'insert into '.$openBidSeq.' (idx, bidNtceNo,bidNtceOrd, compno, tuchalamt,tuchalrate, tuchaldatetime	,remark, bidIdx)';
					$sql .= " VALUES ('" . $idx . "', '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .$arr['prcbdrBizno']. "','" . $arr['bidprcAmt'] . "','" . $arr['bidprcrt'] . "',";
					$sql .= "'".$arr['bidprcDt']."', '".$rmrk."','".$bididx."')";

					//echo '<br>'.$sql.'<br>';
				//$conn->query($sql);
				if ($conn->query($sql) === TRUE) {}
				else {
					//echo 'error sql='.$sql.'<br>';
					$msg .= 'error sql='.$sql.'<br>';
				}
				if ($ii >5) continue;
				$sql = "select compno from openCompany where compno ='". $arr['prcbdrBizno'] . "'";
				$result3 = $conn->query($sql);
				if ($row3 = $result3->fetch_assoc()) {
					
				} else {
					$sql = 'insert into openCompany (compno,compname, repname, phone)';
					$sql .= " VALUES ('" . $arr['prcbdrBizno'] . "', '".addslashes($arr['prcbdrNm'])."', '". addslashes($arr['prcbdrCeoNm']) ."', '')";
					if ($conn->query($sql) === TRUE) {}
					else {
						//echo 'error Company sql='.$sql.'<br>';
						$msg .= 'error Company sql='.$sql.'<br>';
					}
				}
			$ii ++;	
			}
		
		//var_dump($arr);
	//}
	}
	//echo '<br>';
	$i++;

} // end while ($row = $result->fetch_assoc())
//echo ($idx-$cnt);
//echo $msg;
$i--;
?>
<script>
document.getElementById('totalRecords').innerHTML = 'totalRecords = '+'<?=$i?>';
</script>
</body>
</html>
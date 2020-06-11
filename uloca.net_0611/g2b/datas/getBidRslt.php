<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;

// 한번에 처리할 레코드
if ($nosrec == '') $nosrec = 50;
//else $norec = $nosrec;
$norec = $nosrec;
?>
<!DOCTYPE html>
<html>
<head>
<title>ULOCA 개찰 결과 모으기</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
</head>
<body>
데이타 수집		data.co.kr	-><font color=blue>openBidSeq_2016(201601-201606) 199,709</font><br>
openBidSeq_2016-2(201607-201612) 199,709<br>
openBidSeq_2017(201701-201706) 254,916<br>
openBidSeq_2017_2(201707-201712) 216,098건<br>
openBidSeq_2018(201801-201806) 262,376건<br>
openBidSeq_2018_2(201807-201812) 73616<br>
<form action="http://uloca.net/g2b/datas/getBidRslt.php" name="myForm" id="myform" method="post" >
bidIdx <input type=text id=idx0 name=idx0 value='<?=$idx0?>'/>
레코드수 <input type=text id=nosrec name=nosrec1 value='<?=$norec?>'/> <a onclick="gos();" >
<input type="radio" name="nosrec" value="10" onclick='clearIdx0()' <? if ($nosrec== '10') { ?> checked="checked" <? } ?> /> 10
					<input type="radio" name="nosrec" value="20" onclick='clearIdx0()' <? if ($nosrec== '20') { ?> checked="checked" <? } ?>/> 20
					<input type="radio" name="nosrec" value="30" onclick='clearIdx0()' <? if ($nosrec== '30') { ?> checked="checked" <? } ?>/> 30
					<input type="radio" name="nosrec" value="50" onclick='clearIdx0()' <? if ($nosrec== '50') { ?> checked="checked" <? } ?>/> 50
					<input type="radio" name="nosrec" value="100" onclick='clearIdx0()' <? if ($nosrec== '100') { ?> checked="checked" <? } ?>/> 100
<button type="button">검색</button></a>
</form>
<?
$g2bClass = new g2bClass;
//bidNtceNo, bidNtceOrd
$conn = $dbConn->conn();
mysqli_set_charset($conn, 'utf8');
$openBidSeq = 'openBidSeq_2018_2';
$openBidInfo = 'openBidInfo_2018_2';
if ($idx0 == '') {
	$sql = 'select max(bidIdx) as idx from '.$openBidSeq  ;
	$result0 = $conn->query($sql);
	if ($row = $result0->fetch_assoc()) {
		$idx0 = $row['idx'];
		if ($idx0 == 'NULL') $idx0 = 0;
	}
}
//$idx0 ++;
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
$sql = 'select idx, opengDt, bidNtceNo,bidNtceOrd from '.$openBidInfo.' where idx>\''.$idx0.'\' order by idx limit '.$norec ;
$msg = $sql.'<br>';
$result = $conn->query($sql);
$tCnt=0;
$i=1;
while ($row = $result->fetch_assoc()) {
	$bidNtceNo = $row['bidNtceNo'];
	$bidNtceOrd = $row['bidNtceOrd'];
	$bididx = $row['idx'];
	
	// 입찰 결과
	$response1 = $g2bClass->getRsltDataAll($bidNtceNo,$bidNtceOrd);
	$item1 = $response1;
	// $response1 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd); 
	// $json1 = json_decode($response1, true);
	// $item1 = $json1['response']['body']['items'];
	$cnt = count($item1);
	//var_dump($response1);
	//echo 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' bidNtceOrd='.$bidNtceOrd.' count='.$cnt.'<br>';
	$msg .= 'idx='.$row['idx'].' 개찰일시='.$row['opengDt'].' bidNtceNo='.$bidNtceNo.' count='.$cnt.'<br>';
	$ii=1;
	$tcnt += $cnt;
	if ($cnt>0) {
	foreach($item1 as $arr ) {
		$idx   += 1;
		$rmrk = $arr['rmrk'];
		if (trim($rmrk) == '') $rmrk = $ii; // 1순위 낙찰
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
		$sql = "select compno from openCompany where compno ='". $arr['prcbdrBizno'] . "'";
		$result0 = $conn->query($sql);
		if ($row = $result0->fetch_assoc()) {
			
		} else {
			$sql = 'insert into openCompany (compno,compname, repname, phone)';
			$sql .= " VALUES ('" . $arr['prcbdrBizno'] . "', '".$arr['prcbdrNm']."', '". $arr['prcbdrCeoNm'] ."', '')";
			if ($conn->query($sql) === TRUE) {}
			else {
				//echo 'error Company sql='.$sql.'<br>';
				$msg .= 'error Company sql='.$sql.'<br>';
			}
		}
		$ii ++;
		//var_dump($arr);
	}
	}
	//echo '<br>';
	$i++;
}
//echo ($idx-$cnt);
echo $msg;
?>

<script>
function gos() {
	//url = 'http://uloca.net/g2b/datas/getBidRslt.php?nosrec='+document.getElementsById('nosrec').value;
	if (document.myForm.idx0.value>73616) return false; // 223,406
	
	document.myForm.idx0.value= <?=$bididx?>;
	document.myForm.submit();
}
function clearIdx0() {
	alert('clearIdx0() '+document.getElementById("idx0").value);
	document.getElementById("idx0").value="";
	exit();
}
<?
$findme = 'error';
$pos = strpos($msg, $findme);
if ($pos  === true) { // || $tcnt == 0) {
	exit();
} else {
?>
setTimeout(function() { if ( gos() == false) exit();}, 2000);

</script>
<? } ?>
</body>
</html>
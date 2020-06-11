<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
if ($startDate == "") {
	$timestamp = strtotime("2018-01-01");
	$startDate1 = date("Ymd", $timestamp);
	$startDate = date("Y-m-d", $timestamp);
} else {
	$timestamp = strtotime($startDate);
	$startDate1 = str_replace("-","",$startDate);
}
//echo $timestamp;	// 1498834800
if ($endDate == "") {
	$timestamp = strtotime("1 months", $timestamp);
	$timestamp = strtotime("-1 days", $timestamp);
	$endDate1 = date("Ymd", $timestamp);
	$endDate = date("Y-m-d", $timestamp);
} else {
	$endDate1 = str_replace("-","",$endDate);
}
//echo 'startDate1='.$startDate1.' endDate1='.$endDate1;
$kwd='';
$dminsttNm='';
//$response = $g2bClass->getBidOne($startDate,$endDate,$kwd,$dminsttNm);
//$response = $g2bClass->getBidAllJson($startDate,$endDate,$kwd,$dminsttNm,10);
//getBidInfo($bidNtceNo,$bidNtceOrd,$pss) {
$numOfRows = 9000;
$pageNo=1;
$inqryDiv=2; //  1. 공고게시일시 : 공고일자(pblancDate) 2. 개찰일시 : 개찰일시(opengDt)
if ($bidrdo != '') {
	if ($bidrdo == 'bidthing') $bidrdo2 = '물품';
	if ($bidrdo == 'bidcnstwk') $bidrdo2 = '공사';
	if ($bidrdo == 'bidservc') $bidrdo2 = '용역';
	//echo $bidrdo2.'/'.$bidrdo;
	$response = $g2bClass->getSvrData($bidrdo,$startDate1,$endDate1,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
	//var_dump($response);

	$json1 = json_decode($response, true);
	$item1 = $json1['response']['body']['items'];
	//echo '<br>'.'물품입찰<br>';
	//var_dump($item1);
	
}
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
<!-- script src="http://uloca.net/include/JavaScript/Ajax.js"></script -->
</head>

<body>
데이타 수집	-입찰 정보	data.co.kr	->openBidInfo_2018(201801-201806)
<!-- ------------------ 검색창 ---------------------------------------------------------- -->

<form action="verifyBidInfo.php" name="myForm" id="myform" method="post" >
<div id="contents">
<div class="detail_search" >



	<table align=center cellpadding="0" cellspacing="0" width="900px">
		<colgroup>
			<col style="width:15%;" /><col style="width:85%;" />
		</colgroup>
		<tbody>
			
			
			<tr>
				<th>기간</th>
				<td>
					
					<div class="calendar">
						
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" readonly='readonly'/>
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/>
						
						<div id="datepicker"></div>	
						
					</div>
					<select id=dt name=dt >
<?
			$timestamp = strtotime("2018-01-01");
			$timestamp = strtotime("-1 months", $timestamp);
			for ($i=1;$i<14;$i++) {
				$dt1 = date("Y-m", $timestamp);
				echo '<option value="'.$dt1.'"';
				if ($dt == $dt1) echo ' selected="selected"';
				echo '>'.$dt1.'</option>';
				$timestamp = strtotime("-1 months", $timestamp);
			}
?>
				</select>
					<a onclick='weeks(1)'>-5</a> &nbsp;&nbsp;
					<a onclick='weeks(2)'>-10</a> &nbsp;&nbsp;
					<a onclick='weeks(3)'>-15</a> &nbsp;&nbsp;
					<a onclick='weeks(4)'>-20</a> &nbsp;&nbsp;
					<a onclick='weeks(5)'>-25</a> &nbsp;&nbsp;
					<a onclick='weeks(6)'>-마지막일</a> 
				</td>
			</tr>
			
			<tr>
				<th>검색종류</th>
				<td>
				
					<input type="radio" name="bidrdo" value="bidthing" <? if ($bidrdo== 'bidthing') { ?> checked="checked" <? } ?> /> 물품
					<input type="radio" name="bidrdo" value="bidcnstwk" <? if ($bidrdo== 'bidcnstwk') { ?> checked="checked" <? } ?>/> 공사
					<input type="radio" name="bidrdo" value="bidservc" <? if ($bidrdo== 'bidservc') { ?> checked="checked" <? } ?>/> 용역
					
				</td>
			</tr>
			
		</table>
		<div class="btn_area">
		<a onclick="doGetBidInfo();" class="search">실행</a>
		
		</div>	
	</div>
	</div>
</form>
<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
  <img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
</div>
<div style='font-size:14px;'><font color='blue'><strong>- 입찰 등록 -</strong></font></center>
<div id=totalrec>total record=<?=count($item1)?>/<?=$json1['response']['body']['totalCount']?></div>

<table class="type10" id="specData">
    <tr>
        
		<th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="12%;">공고번호</th>
        <th scope="cols" width="20%;">공고명</th>
        <th scope="cols" width="15%;">공고기관</th>
        <th scope="cols" width="12%;">수요기관</th>
        <th scope="cols" width="10%;">개찰일시</th>
        <th scope="cols" width="10%;">구분</th>
        <th scope="cols" width="8%;">공고Index</th>
        <th scope="cols" width="8%;">응찰건수</th>
	</tr>
<?
/* 정렬
foreach ($item as $key => $row) {
    $opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
} 
array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시
*/
function getMaxInfoIdx($conn,$openBidInfo) {
	$maxIdx = 1;
	$sql ='select max(idx) as idx from '. $openBidInfo;
	$result = $conn->query($sql);
	if ($row = $result->fetch_assoc()) {
		$maxIdx = $row[idx] + 1;
	}
	return $maxIdx;
}
$conn = $dbConn->conn();
mysqli_set_charset($conn, 'utf8');
$openBidInfo = 'openBidInfo_2018';
$sql = 'select max(idx) idx from '.$openBidInfo;
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$idx = $row[idx];
	if ($idx == 'NULL') $idx = 0;
}
$idx0=$idx;
$ii=1;
$countItem = count($item1);
//foreach($item1 as $arr ) { //foreach element in $arr
for ($i=0;$i<$countItem;$i++) {
	$arr = $item1[$i];
	while ($arr['bidNtceNo'] == $item1[$i+1]['bidNtceNo']) $i++;

	$opdt = substr($arr['opengDt'],0,7);
	if ($opdt>='2016-01' && $opdt < '2016-07') $openBidInfoTable = 'openBidInfo_2016';
	if ($opdt>='2016-07' && $opdt < '2017-01') $openBidInfoTable = 'openBidInfo_2016_2';
	if ($opdt>='2017-01' && $opdt < '2017-07') $openBidInfoTable = 'openBidInfo_2017';
	if ($opdt>='2017-07' && $opdt < '2018-01') $openBidInfoTable = 'openBidInfo_2017_2';
	if ($opdt>='2018-01' && $opdt < '2018-07') $openBidInfoTable = 'openBidInfo_2018';
	if ($opdt>='2018-07' && $opdt < '2019-01') $openBidInfoTable = 'openBidInfo_2018_2';
	$maxidx = getMaxInfoIdx($conn,$openBidInfoTable);

	$sql = "select bidNtceNo from ".$openBidInfoTable." where bidNtceNo = '".$arr['bidNtceNo']."' ";
	$result = $conn->query($sql);
	//if ($conn->query($sql) === TRUE) { 
	if ($row = $result->fetch_assoc()) {
			continue; 
		}
	/*else {
		echo 'error sql='.$sql.'<br>';
		continue;
	} */

	
	$arr = $item1[$i];
    
	$k = $i+1;
	if ($ii % 2 == 1) {
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$k.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'</td>';
		//$tr .= '<td>'.$arr['bidNtceOrd'].'</td>';
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td>'.$arr['opengDt'].'</td>';
		$tr .= '<td  style="text-align: center;">'.$bidrdo2.'</td>';
		$tr .= '<td style="text-align: center;">'.$maxidx.'</td>';
		$tr .= '<td style="text-align: center;"></td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td class="even" style="text-align: center;">'.$k.'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$arr['bidNtceNo'].'</td>';
		//$tr .= '<td class="even">'.$arr['bidNtceOrd'].'</td>';
		$tr .= '<td class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td class="even">'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td class="even" >'.$arr['dminsttNm'].'</td>';
		$tr .= '<td class="even" >'.$arr['opengDt'].'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$bidrdo2.'</td>';
		$tr .= '<td style="text-align: center;">'.$maxidx.'</td>';
		$tr .= '<td style="text-align: center;"></td>';
		$tr .= '</tr>';
	}
	echo $tr;
	$ii ++;
	//$i += 1;
	// db insert ===================================================
	
	//$idx =  getMaxInfoIdx($openBidInfo); //+= 1;
	$sql = 'insert into '.$openBidInfoTable.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype)';
			$sql .= "VALUES ('" . $maxidx . "', '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .addslashes($arr['bidNtceNm']). "','" . addslashes($arr['ntceInsttNm']) . "','" . addslashes($arr['dminsttNm']) . "',";
			$sql .= "'".$arr['opengDt']."', '".$bidrdo2."')";

			//echo '<br>'.$sql.'<br>';
	//$sql = addslashes($sql);
		if ($conn->query($sql) === TRUE) {}
		else echo 'error sql='.$sql.'<br>';
	/*
	// 입찰 결과
	$response2 = $g2bClass->getRsltData($bidNtceNo,$bidNtceOrd); 
	$json2 = json_decode($response2, true);
	$item2 = $json2['response']['body']['items'];
	$cnt = count($item2);

	*/

	$maxidx ++;
	








}
$idx0=$idx-$idx0;
$ii --;
?>
<script>
//alert('<?=$ii?> 건을 추가했습니다.');
function doGetBidInfo() {
	var form = document.myForm;
	//form.startDate.value = form.startDate.value.replace(/-/g,'');
	//form.endDate.value = form.endDate.value.replace(/-/g,'');
	if (form.bidrdo.value=='')
	{
		alert('검색종류를 선택하세요');
		return;
	}
	form.submit();
}
function weeks(week) {
	var form = document.myForm;
	//alert(form.dt.value);
	if (week == '1')
	{
		form.startDate.value = form.dt.value+'-01';
		form.endDate.value = form.dt.value+'-05';
	} else if (week == '2')
	{
		form.startDate.value = form.dt.value+'-06';
		form.endDate.value = form.dt.value+'-10';
	} else if (week == '3')
	{
		form.startDate.value = form.dt.value+'-11';
		form.endDate.value = form.dt.value+'-15';
	} else if (week == '4')
	{
		form.startDate.value = form.dt.value+'-16';
		form.endDate.value = form.dt.value+'-20';
	} else if (week == '5')
	{
		form.startDate.value = form.dt.value+'-21';
		form.endDate.value = form.dt.value+'-25';
	} else if (week == '6')
	{
		form.startDate.value = form.dt.value+'-26';
		tmp = dateAddDel(form.dt.value+'-01', 1, 'm');
		tmp = dateAddDel(tmp, -1, 'd');
		form.endDate.value = tmp;
	}

}
var insIdx=0;
var insTable = 'specData';
var noRow = 0;
var noSeq = 0;
function insertSeqX() {
	insTable = document.getElementById('specData');
	noRow = insTable.rows.length;
	if (noRow<2)
	{
		//alert("data가 없습니다.");
		move_stop();
		return;
	}
	insIdx=1;
	insertSeq2X();
	//console.log('lth='+lth);
	
	
}
function insertSeq2X() {
	if (insIdx < noRow) // noRow
	{
		bidNtceNo = insTable.rows[insIdx].cells[1].innerHTML.split('-')[0];
			bididx = insTable.rows[insIdx].cells[7].innerHTML;
			//console.log('noRow='+noRow+'/'+bidNtceNo+'/'+bididx+'/'+insIdx);
			url = 'http://uloca.net/g2b/datas/dailyDataInsSeq.php?bidNtceNo='+bidNtceNo+'&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq+'&bididx='+bididx;
			//alert(url);
			getAjax(url,searchDaily3X);
	}
}
function searchDaily3X(data) {
	// 응찰건수 컬럼
	//console.log('searchDaily3 /'+data+'/'+insIdx);
	insTable.rows[insIdx].cells[8].innerHTML = data;
	insIdx ++;
	noSeq += eval(data);
	if (insIdx < noRow) insertSeq2X();
	else { 
		move_stop();
		noRow --;
		alert('입찰정보= ' + noRow + '건 개찰정보= ' + noSeq + ' 건이 추가 수집되었습니다.'); 
		return; 
	}
}
openBidInfo = 'openBidInfo_2018';
openBidSeq  = 'openBidSeq_2018';
//<div id=totalrec>total record=<?=count($item1)?>/<?=$json1['response']['body']['totalCount']?></div>
<?
if ($json1['response']['body']['totalCount'] != '')
{
?>
document.getElementById('totalrec').innerHTML = ''+<?=$ii?>+'/'+<?=$json1['response']['body']['totalCount']?>;
<? } ?>
insertSeqX();
</script>
</body>
</html>
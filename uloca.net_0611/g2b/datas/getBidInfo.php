<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
if ($startDate == "") {
	$timestamp = strtotime("2018-07-01");
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
데이타 수집	-입찰 정보	data.co.kr	->openBidInfo_2018_2(201807-201812)
<!-- ------------------ 검색창 ---------------------------------------------------------- -->

<form action="getBidInfo.php" name="myForm" id="myform" method="post" >
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
			$timestamp = strtotime("2018-12-31");
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
<div style='font-size:14px;'><font color='blue'><strong>- 입찰 등록 -</strong></font></center>
<div id=totalrechrc>total record=<?=count($item1)?>/<?=$json1['response']['body']['totalCount']?></div>

<table class="type10" id="specData">
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="15%;">공고번호</th>
        <th scope="cols" width="5%;">공고차수</th>
        <th scope="cols" width="20%;">입찰공고명</th>
        <th scope="cols" width="10%;">공고기관명</th>
        <th scope="cols" width="10%;">수요기관명</th>
        <th scope="cols" width="15%;">개찰일시</th>
		<th scope="cols" width="5%;">종류</th>
    </tr>
<?
/* 정렬
foreach ($item as $key => $row) {
    $opninRgstClseDt[$key]  = $row['opninRgstClseDt'];
} 
array_multisort($opninRgstClseDt, SORT_DESC, $item); // 등록일시
*/

$conn = $dbConn->conn();
mysqli_set_charset($conn, 'utf8');
$openBidInfo = 'openBidInfo_2018_2';
$sql = 'select max(idx) idx from '.$openBidInfo;
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$idx = $row[idx];
	if ($idx == 'NULL') $idx = 0;
}
$idx0=$idx;
$i=0;
$countItem = count($item1);
//foreach($item1 as $arr ) { //foreach element in $arr
for ($i=0;$i<$countItem;$i++) {
	$arr = $item1[$i];
	while ($arr['bidNtceNo'] == $item1[$i+1]['bidNtceNo']) $i++;
	$arr = $item1[$i];
    if ($i<5) {
	$k = $i+1;
	if ($i % 2 == 0) {
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$k.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'</td>';
		$tr .= '<td>'.$arr['bidNtceOrd'].'</td>';
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td>'.$arr['opengDt'].'</td>';
		$tr .= '<td>'.$bidrdo2.'</td>';
		$tr .= '</tr>';
	} else {
		$tr = "<tr>";
		$tr .= '<td class="even" style="text-align: center;">'.$k.'</td>';
		$tr .= '<td class="even" style="text-align: center;">'.$arr['bidNtceNo'].'</td>';
		$tr .= '<td class="even">'.$arr['bidNtceOrd'].'</td>';
		$tr .= '<td class="even">'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td class="even">'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td class="even" >'.$arr['dminsttNm'].'</td>';
		$tr .= '<td class="even" >'.$arr['opengDt'].'</td>';
		$tr .= '<td class="even">'.$bidrdo2.'</td>';
		$tr .= '</tr>';
	}
	echo $tr;
	}
	//$i += 1;
	// db insert ===================================================

	$idx   += 1;
	$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype)';
			$sql .= "VALUES ('" . $idx . "', '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .$arr['bidNtceNm']. "','" . $arr['ntceInsttNm'] . "','" . $arr['dminsttNm'] . "',";
			$sql .= "'".$arr['opengDt']."', '".$bidrdo2."')";

			//echo '<br>'.$sql.'<br>';

		if ($conn->query($sql) === TRUE) {}
		else echo 'error sql='.$sql.'<br>';
}
$idx0=$idx-$idx0;
?>
<script>
alert('<?=$idx0?> 건을 추가했습니다.');
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
</script>
</body>
</html>
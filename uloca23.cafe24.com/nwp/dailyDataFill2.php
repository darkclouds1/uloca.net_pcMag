<?
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

//$dur = $g2bClass->countDuration($startDate); //'_2018_2';
$dur = $g2bClass->dt2duration($startDate);
/*if ($startDate != '') {
	$startDate1 = str_replace($startDate,'-','');
	$startDate1 = substr($startDate1,0,6);
	if ($startDate1 >= '201601' && $startDate1 < '201607') {
		$dur = '_2016';
	} else if ($startDate1 >= '201607' && $startDate1 < '201701') {
		$dur = '_2016_2';
	} else if ($startDate1 >= '201701' && $startDate1 < '201707') {
		$dur = '_2017';
	} else if ($startDate1 >= '201707' && $startDate1 < '201801') {
		$dur = '_2017_2';
	} else if ($startDate1 >= '201801' && $startDate1 < '201807') {
		$dur = '_2018';
	} else if ($startDate1 >= '201807' && $startDate1 < '201901') {
		$dur = '_2018_2';
	} else if ($startDate1 >= '201901' && $startDate1 < '201907') {
		$dur = '_2019';
	} else if ($startDate1 >= '201907' && $startDate1 < '202001') {
		$dur = '_2019_2';
	} else $dur = '_2018_2';
} */
$openBidInfo = 'openBidInfo_'.$dur;
$openBidSeq = 'openBidSeq_'.$dur;
echo $openBidInfo;

if ($startRec == '') $startRec=0;
if ($once == '') $once=200;
$startRec1 = $startRec + $once;
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
	frm.submit();
}
</script>
<form action="dailyDataFill2.php" name="myForm" id="myform" method="post" >
<p style='text-align:center; font-weight:bold;'>개찰 1등정보 db에 추가</p>
<div id="contents">
<div class="detail_search" >

<table align=center cellpadding="0" cellspacing="0" width="850px">
		<colgroup>
			<col style="width:20%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>

		<tr>
				<th>기간</th>
				<td>
					<div class="calendar">
						
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" readonly='readonly'/>
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/> 기간을 다 입력해야함
						
						<div id="datepicker"></div>	
				</div>
				
				</td>
			</tr>
			<!-- tr>
				<th>시작레코드/갯수</th>
				<td>
					
						<input autocomplete="off" type="text" maxlength="10" name="startRec" id="startRec" value="<?=$startRec1?>" style="width:76px;" />
						<input autocomplete="off" type="text" maxlength="10" name="once" id="once" value="<?=$once?>" style="width:76px;" />
				
				</td>
			</tr -->
		</table>
		<div class="btn_area">
		<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
		<a onclick="doit();" class="search">실행</a>
	</div>	
	</div>
	</div>
</form>

<center><div style='font-size:14px; color:blue;font-weight:bold'>- 입낙찰 정보 -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>

<table class="type10" id="specData">
<thead>
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="12%;">공고번호</th>
        <th scope="cols" width="20%;">공고명</th>
        <th scope="cols" width="15%;">공고기관</th>
        <th scope="cols" width="12%;">수요기관</th>
        <th scope="cols" width="10%;">개찰일시</th>
        <th scope="cols" width="6%;">구분</th>
        <th scope="cols" width="6%;">공고Index</th>
        <th scope="cols" width="6%;">응찰건수</th>
        <th scope="cols" width="10%;">낙찰업체</th>
        
    </tr>
</thead>
<tbody>
<?
//exit;
// --------------------------------------- function insertopenBidInfo '20180912933'
function insertopenBidInfo2($conn,$openBidInfo, $row,$item2) {
	//$sql = "select bidNtceOrd from ".$openBidInfo." where bidNtceNo = '".$row['bidNtceNo']."'; ";
	//echo $sql;
	//$result = $conn->query($sql);
	//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
	
			$sql = "update ".$openBidInfo." set "; // bidNtceOrd ='". $row['bidNtceOrd'] . "', ";
			/*$sql .= "reNtceYn = '". $row['reNtceYn'] . "', rgstTyNm = '". $row['rgstTyNm'] . "', ";
			$sql .= "ntceKindNm = '". $row['ntceKindNm'] . "', bidNtceDt = '". $row['bidNtceDt'] . "', ";
			$sql .= "ntceInsttCd = '". $row['ntceInsttCd'] . "', dminsttCd = '". $row['dminsttCd'] . "', ";
			$sql .= "bidBeginDt = '". $row['bidBeginDt'] . "', bidClseDt = '". $row['bidClseDt'] . "', ";
			$sql .= "presmptPrce = '". $row['presmptPrce'] . "', bidNtceDtlUrl = '". $row['bidNtceDtlUrl'] . "', ";
			$sql .= "bidNtceUrl = '". $row['bidNtceUrl'] . "', sucsfbidLwltRate = '". $row['sucsfbidLwltRate'] . "', ";
			$sql .= "bfSpecRgstNo = '". $row['bfSpecRgstNo'] . "' ";
			$ri = findjson($row,'bidNtceNo',$row['bidNtceNo'],$item2);
			//echo 'ri='.$ri.'bidNtceNo='.$arr['bidNtceNo']; */
		if (count($item2) >0) {
			$ri=0;
			$sql .= " prtcptCnum = '". $item2[$ri]['prtcptCnum'] . "', ";
			$sql .= "bidwinnrNm = '". $item2[$ri]['bidwinnrNm'] . "', bidwinnrBizno = '". $item2[$ri]['bidwinnrBizno'] . "', ";
			$sql .= "sucsfbidAmt = '". $item2[$ri]['sucsfbidAmt'] . "', sucsfbidRate = '". $item2[$ri]['sucsfbidRate'] . "', ";
			$sql .= "rlOpengDt = '". $item2[$ri]['rlOpengDt'] . "', bidwinnrCeoNm = '". $item2[$ri]['bidwinnrCeoNm'] . "' ";
			$sql .=" where bidNtceNo='".$row['bidNtceNo']."';";
			$conn->query($sql);
			echo '<br>'.$sql;
		} 
			
		//}
		//echo $sql.'<br>';
		return false; // update
	
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}

// --------------------------------------- function 
function findjson($arr,$col,$val,$item) {
	$i = 0;
	//echo 'findjson col='.$col.' val='.$val;
	foreach($item as $arr2 ) {	// $arr['bidNtceNo']
		if ($arr2['bidNtceNo'] == $val) {
			//echo 'findjson i='.$i.'<br>';
			return $i; // if ($arr2[$col] == $val) return $i;
		}
		$i ++;
	}
	return -1;
}

// --------------------------------------- function insertTableBidInfo
function insertTableBidInfo2($i,$idx,$arr,$item2) { //($i, $idx,$arr,$pss,$item2,$ri,$prtcptCnum,$bidwinnrNm) {
	
		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['opengDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidtype'].'</td>';
		$tr .= '<td style="text-align: center;"> </td>';
		$tr .= '<td style="text-align: right;">'.$item2[0]['prtcptCnum'].'</td>';
		$tr .= '<td style="text-align: left;">'.$item2[0]['bidwinnrNm'].'</td>';
		$tr .= '</tr>';

	echo $tr;
}

if ($startDate == '' ) {
	exit;
}
/*
if ($pss == '' ) {
	//echo '물품,공사,용역 구분이 없습니다.'; exit;
} */
if (strlen($startDate) == 10) $startDatex = substr($startDate,0,4).substr($startDate,5,2).substr($startDate,8,2);
if (strlen($endDate) == 10) $endDatex = substr($endDate,0,4).substr($endDate,5,2).substr($endDate,8,2);
$startDatex.= '0000'; //=$g2bClass->changeDateFormat($startDate);
$endDatex  .= '2359'; //=$g2bClass->changeDateFormat($endDate);

/*
2018-10-03	count=48366
2018	111412
*/
echo 'startDate='.$startDate.' endDate='.$endDate; //.' count=111412<br>';
if ($startDate != '' && $endDate != '') {
	$sql = "select * from ".$openBidInfo." where bidwinnrNm='' and opengDt>='".$startDate."' and opengDt <='".$endDate." 23:59:59'  order by idx ";
} else {
	$sql = "select * from ".$openBidInfo." where bidwinnrNm=''  order by idx limit ".$startRec.",".$once;
}
echo $sql;
$result = $conn->query($sql);
$i=0;
$idx = 0;
$numOfRows = 10;
$pageNo = 1;
$row='';
$bidNtceNo='';
while ($row = $result->fetch_assoc()) {
	$i++;
	$bidNtceNo = $row['bidNtceNo'];
	$inqryDiv = 2; 
	$pss = $row['bidtype'];
	$response2 = $g2bClass->getBidRslt2($numOfRows,$pageNo,$inqryDiv,$startDatex,$endDatex,$pss,$bidNtceNo);
	$json2 = json_decode($response2, true);
	$item2 = $json2['response']['body']['items'];
	if (count($item2) == 0) continue;
	// ------------------------------ insert
	$tf = insertopenBidInfo2($conn,$openBidInfo,$row,$item2);
	//if ($tf == false) continue;
	$idx ++;
	// ------------------------------ insert
	insertTableBidInfo2($i,$idx,$row,$item2);
	
	
}
echo '<br>i='.$i.'/'.$bidNtceNo.'/<br>';
	
/*$kwd = '';
$dminsttNm= '';
$numOfRows= 8000;
$pageNo= 1;
$inqryDiv= 2; // 2. 개찰일시 : 개찰일시(opengDt)
$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
$response11 = $g2bClass->getSvrData($bidrdo1,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
$json11 = json_decode($response11, true);
$item11 = $json11['response']['body']['items'];
$bidrdo1 = 'bidcnstwk'; //$bidrdo2 = '공사';
$response12 = $g2bClass->getSvrData($bidrdo1,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
$json12 = json_decode($response12, true);
$item12 = $json12['response']['body']['items'];
$bidrdo1 = 'bidservc'; //$bidrdo2 = '용역';
$response13 = $g2bClass->getSvrData($bidrdo1,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
$json13 = json_decode($response13, true);
$item13 = $json13['response']['body']['items'];
$item10 = array_merge($item11,$item12,$item13);
$countItem = count($item10);
*/



echo '<br>item1='.$i;
echo 'openBidInfo='.$openBidInfo;
//var_dump ($item2[0]);
//echo '<br'.'-------------------------------------------------------------<br>';


?>
<?
@extract($_GET);
@extract($_POST);
ob_start();
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();
//echo $_POST['startDate'].'/'.$startDate;
$dur = '2018';
if ($_POST['startDate'] != '') {
	$dur = $g2bClass->countDuration($startDate);
	
}
$openBidInfo = 'openBidInfo'; //.$dur;
$openBidSeq = 'openBidSeq'.'_'.$dur;
echo $openBidSeq.'/'.$startDate;

$contn1 = 1;
if (isset($_POST['contn'])) {
	$contn1 = 0;
    // Checkbox is selected
} else $contn1 = 1;
$sql = "select lastdt from workdate where workname = 'dailyDataFill'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc() ) {
	$lastdt = $row['lastdt'];
}
echo ' lastdt='.$lastdt.' contn='.$contn;

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
<form action="dailyDataFill.php" name="myForm" id="myform" method="post" >
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
						
						<input autocomplete="off" type="text" maxlength="10" name="startDate" id="startDate" value="<?=$startDate?>" style="width:76px;" readonly='readonly' onchange='document.getElementById("endDate").value=this.value'/>
						
						~
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/> 1주일 이내-하루
						<input type="checkbox" name="contn" id="contn" <? if ($contn=='on') {?>checked=checked <? } ?> >계속
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

<center><div style='font-size:14px; color:blue;font-weight:bold'>- 입낙찰 정보 / 입찰정보만 보완 1등 정보-</div></center>
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

$newRecord = 0;
// --------------------------------------- function insertopenBidInfo '20180912933'
function insertopenBidInfo($conn,$openBidInfo, $idx,$arr,$pss,$item2,$ri) {
	$sql = "select bidNtceOrd from ".$openBidInfo." where bidNtceNo = '".$arr['bidNtceNo']."'; ";
	//echo $sql;
	$result = $conn->query($sql);
	//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
	if ($row = $result->fetch_assoc()  ) {
		//var_dump($row);
		//echo "<br>row['bidNtceOrd']=".$row['bidNtceOrd']." arr['bidNtceOrd']'=".  $arr['bidNtceOrd'] ." arr['bidClseDt']=". $arr['bidClseDt'];
		//if ($row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
			$sql = "update ".$openBidInfo." set "; // bidNtceOrd ='". $arr['bidNtceOrd'] . "', ";
			$sql .= "reNtceYn = '". $arr['reNtceYn'] . "', rgstTyNm = '". $arr['rgstTyNm'] . "', ";
			$sql .= "ntceKindNm = '". $arr['ntceKindNm'] . "', bidNtceDt = '". $arr['bidNtceDt'] . "', ";
			$sql .= "ntceInsttCd = '". $arr['ntceInsttCd'] . "', dminsttCd = '". $arr['dminsttCd'] . "', ";
			$sql .= "bidBeginDt = '". $arr['bidBeginDt'] . "', bidClseDt = '". $arr['bidClseDt'] . "', ";
			$sql .= "presmptPrce = '". $arr['presmptPrce'] . "', bidNtceDtlUrl = '". $arr['bidNtceDtlUrl'] . "', ";
			$sql .= "bidNtceUrl = '". $arr['bidNtceUrl'] . "', sucsfbidLwltRate = '". $arr['sucsfbidLwltRate'] . "', ";
			$sql .= "bfSpecRgstNo = '". $arr['bfSpecRgstNo'] . "' ";
			$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item2);
			//echo 'ri='.$ri.'bidNtceNo='.$arr['bidNtceNo'];
		if ($ri>=0) {
			$sql .= ", prtcptCnum = '". $item2[$ri]['prtcptCnum'] . "', ";
			$sql .= "bidwinnrNm = '". $item2[$ri]['bidwinnrNm'] . "', bidwinnrBizno = '". $item2[$ri]['bidwinnrBizno'] . "', ";
			$sql .= "sucsfbidAmt = '". $item2[$ri]['sucsfbidAmt'] . "', sucsfbidRate = '". $item2[$ri]['sucsfbidRate'] . "', ";
			$sql .= "rlOpengDt = '". $item2[$ri]['rlOpengDt'] . "', bidwinnrCeoNm = '". $item2[$ri]['bidwinnrCeoNm'] . "' ";
			
		} else {
			$sql .= ", prtcptCnum = '', ";
			$sql .= "bidwinnrNm = '', bidwinnrBizno = '', ";
			$sql .= "sucsfbidAmt = '', sucsfbidRate = '', ";
			$sql .= "rlOpengDt = '', bidwinnrCeoNm = '' ";
		}
			$sql .=" where bidNtceNo='".$arr['bidNtceNo']."';";
			$conn->query($sql);
			
		//}
		//echo $sql.'<br>';
		return false; // update
	} else {

		$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
		$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo,';
		$sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
		$sql .= "VALUES ('" . $idx . "', '".$arr['bidNtceNo']."', '". $arr['bidNtceOrd'] ."', '" .addslashes($arr['bidNtceNm']). "','" . addslashes($arr['ntceInsttNm']) . "','" . addslashes($arr['dminsttNm']) . "',";
		$sql .= "'".$arr['opengDt']."', '".$pss."', ";
		$sql .= "'".$arr['reNtceYn']."', '".$arr['rgstTyNm']."', ";
		$sql .= "'".$arr['ntceKindNm']."', '".$arr['bidNtceDt']."', ";
		$sql .= "'".$arr['ntceInsttCd']."', '".$arr['dminsttCd']."', ";
		$sql .= "'".$arr['bidBeginDt']."', '".$arr['bidClseDt']."', ";
		$sql .= "'".$arr['presmptPrce']."', '".$arr['bidNtceDtlUrl']."', ";
		$sql .= "'".$arr['bidNtceUrl']."', '".$arr['sucsfbidLwltRate']."', ";
		$sql .= "'".$arr['bfSpecRgstNo']."', "; 
		//  http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
		$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item2);
		if ($ri>=0) {
		$sql .= "'".$item2[$ri]['prtcptCnum']."', ";
		$sql .= "'".$item2[$ri]['bidwinnrNm']."', '".$item2[$ri]['bidwinnrBizno']."', ";
		$sql .= "'".$item2[$ri]['sucsfbidAmt']."', '".$item2[$ri]['sucsfbidRate']."', ";
		$sql .= "'".$item2[$ri]['rlOpengDt']."', '".$item2[$ri]['bidwinnrCeoNm']."') ";
		} else {
			$sql = "'','','','','','','' )";
		}
		//echo $sql.'<br>';
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else echo 'error sql='.$sql.'<br>';
		}
		$newRecord ++;
		return true;
	}
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}
function workdate($conn,$workname,$workdt) {
	$sql = "update workdate set workdt='".$workdt."' where workname='".$workname."' ";
	$conn->query($sql);
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
function insertTableBidInfo($i, $idx,$arr,$pss,$item2,$ri,$prtcptCnum,$bidwinnrNm) {

		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['opengDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$pss.'</td>';
		$tr .= '<td style="text-align: center;"> </td>';
		$tr .= '<td style="text-align: right;">'.$item2[$ri]['prtcptCnum'].'</td>';
		$tr .= '<td style="text-align: left;">'.$item2[$ri]['bidwinnrNm'].'</td>';
		$tr .= '</tr>';

	echo $tr;
}

if ($startDate == '' || $endDate == '') {
	exit;
}
/*
if ($pss == '' ) {
	//echo '물품,공사,용역 구분이 없습니다.'; exit;
} */
if (strlen($startDate) == 10) $startDate = substr($startDate,0,4).substr($startDate,5,2).substr($startDate,8,2);
if (strlen($endDate) == 10) $endDate = substr($endDate,0,4).substr($endDate,5,2).substr($endDate,8,2);
$startDate.= '0000'; //=$g2bClass->changeDateFormat($startDate);
$endDate  .= '2359'; //=$g2bClass->changeDateFormat($endDate);
echo 'startDate='.$startDate.' endDate='.$endDate;
$kwd = '';
$dminsttNm= '';
$numOfRows= 999; //8000;
$pageNo= 1;
$inqryDiv= 1; // 2. 개찰일시 : 개찰일시(opengDt)
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

//$tst = '코웨이(주)';
$inqryDiv = 2;
$pss = '물품';
$response2 = $g2bClass->getBidRslt($numOfRows,$pageNo,$inqryDiv,$startDate,$endDate,$pss);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];
$pss = '공사';
$response3 = $g2bClass->getBidRslt($numOfRows,$pageNo,$inqryDiv,$startDate,$endDate,$pss);
$json3 = json_decode($response3, true);
$item3 = $json3['response']['body']['items'];
$pss = '용역';
$response4 = $g2bClass->getBidRslt($numOfRows,$pageNo,$inqryDiv,$startDate,$endDate,$pss);
$json4 = json_decode($response4, true);
$item4 = $json4['response']['body']['items'];
$item22 = array_merge($item2,$item3,$item4);
//var_dump($item2);
// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

echo '<br>item1='.count($item10).' item2='.count($item22).'<br>';
echo 'openBidInfo='.$openBidInfo;
//var_dump ($item2[0]);
//echo '<br'.'-------------------------------------------------------------<br>';
$i=1;
$idx ++;
// main svr loop
//$ri = findjson($arr,'bidNtceNo','20180912933',$item22); // 16
//echo 'ri='.$ri.' 20180912933<br>';
foreach($item11 as $arr ) {
	$pss = '물품';
	$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item22);
	$prtcptCnum = '';
	$bidwinnrNm = '';
	if ($ri>=0) {
		$prtcptCnum = $item2[$ri]['prtcptCnum'];
		$bidwinnrNm = $item2[$ri]['bidwinnrNm'];
	}
	
	// ------------------------------ insert
	$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
	//if ($tf == false) continue;
	$idx ++;
	// ------------------------------ insert
	insertTableBidInfo($i,$idx,$arr,$pss,$item22,$ri,$prtcptCnum,$bidwinnrNm);
	
	$i++;
}
foreach($item12 as $arr ) {
	$pss = '공사';
	$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item22);
	$prtcptCnum = '';
	$bidwinnrNm = '';
	if ($ri>=0) {
		$prtcptCnum = $item2[$ri]['prtcptCnum'];
		$bidwinnrNm = $item2[$ri]['bidwinnrNm'];
	}
	
	// ------------------------------ insert
	$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
	//if ($tf == false) continue;
	$idx ++;
	// ------------------------------ insert
	insertTableBidInfo($i,$idx,$arr,$pss,$item22,$ri,$prtcptCnum,$bidwinnrNm);
	
	$i++;
}
foreach($item13 as $arr ) {
	$pss = '용역';
	$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item22);
	$prtcptCnum = '';
	$bidwinnrNm = '';
	if ($ri>=0) {
		$prtcptCnum = $item2[$ri]['prtcptCnum'];
		$bidwinnrNm = $item2[$ri]['bidwinnrNm'];
	}
	
	// ------------------------------ insert
	$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
	//if ($tf == false) continue;
	$idx ++;
	// ------------------------------ insert
	insertTableBidInfo($i,$idx,$arr,$pss,$item22,$ri,$prtcptCnum,$bidwinnrNm);
	
	$i++;
}
echo ' 새 레코드 = '.$newRecord .'건 ';
$workname = 'dailyDataFill';
$workdt = $_POST['startDate'];
workdate($conn,$workname,$workdt);

?>
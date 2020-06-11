<?
@extract($_GET);
@extract($_POST);
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();

$openBidInfo = 'openBidInfo';

$contn1 = 1;
if (isset($_POST['contn'])) {
	$contn1 = 0;
    // Checkbox is selected
} else $contn1 = 1;
$sql = "select workdt from workdate where workname = 'openHrcInfo'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc() ) {
	$workdt = $row['workdt'];
}
echo ' workdt='.$workdt.' contn='.$contn;

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
		dts=dateAddDel(frm.startDate.value, -1, 'd');
		frm.startDate.value = dts;
		frm.endDate.value = dts;
		frm.submit();
	} else {frm.contn.checked = false;
		return;
	}
}
</script>
<body  onload="javascript:donextday();">
<center>사전규격 수집</center>
<form action="getHrcsp.php" name="myForm" id="myform" method="post" >
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
						<input autocomplete="off" type="text" maxlength="10" name="endDate" id="endDate" value="<?=$endDate?>" style="width:76px;"  readonly='readonly'/> 하루씩 빼기로 ......1주일 이내-하루
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

<center><div style='font-size:14px; color:blue;font-weight:bold'>- 사전규격정보 api-> db -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>
<!-- 사전규격 = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
					등록번호			품명					배정예산금액	등록일시		실수요기관명		의견등록마감일시		입찰공고번호목록 -->
<table class="type10" id="specData">
<thead>
    <tr>
        <th scope="cols" width="5%;">순위</th>
		<th scope="cols" width="12%;">등록번호</th>
        <th scope="cols" width="23%;">품명</th>
        <th scope="cols" width="15%;">배정예산금액</th>
        <th scope="cols" width="10%;">등록일시</th>
        <th scope="cols" width="15%;">수요기관</th>
        <th scope="cols" width="10%;">의견등록마감일시</th>
        <th scope="cols" width="10%;">구분</th>
        
        
    </tr>
</thead>
<tbody>
<?
if ($startDate == '') exit;
$startDate1 = $startDate . ' 0000';
if (strlen($startDate) == 10) $startDate = substr($startDate,0,4).substr($startDate,5,2).substr($startDate,8,2);
if (strlen($endDate) == 10) $endDate = substr($endDate,0,4).substr($endDate,5,2).substr($endDate,8,2);
$startDate.= '0000'; //=$g2bClass->changeDateFormat($startDate);
$endDate  .= '2359'; //=$g2bClass->changeDateFormat($endDate);
echo 'startDate='.$startDate.' endDate='.$endDate;
if ($startDate1 >= $workdt) {
	echo '시작일자가 마지막 작업 일자 보다 같거나 큽니다. startDate='.$startDate1.' workdt='.$workdt;
	exit;
}

function insertopenBidInfo($conn,$openBidInfo, $idx,$arr,$pss,$item2,$ri) {
//	사전규격 = [ 'bfSpecRgstNo', 'prdctClsfcNoNm', 'asignBdgtAmt', 'rgstDt', 'rlDminsttNm', 'opninRgstClseDt', 'bidNtceNoList' ];
//					등록번호			품명					배정예산금액	등록일시		실수요기관명		의견등록마감일시		입찰공고번호목록
		$sql = 'insert into '.$openBidInfo.' (bidNtceNo, bidNtceOrd, bidNtceNm,';
		$sql .= 'presmptPrce,bidNtceDt,dminsttNm,bidClseDt,bidNtceDtlUrl,bidtype)';
		$sql .= "VALUES ('".$arr['bfSpecRgstNo']."', '', '" .addslashes($arr['prdctClsfcNoNm']). "','" . $arr['asignBdgtAmt'] . "','" . $arr['rgstDt'] . "',";
		$sql .= "'".addslashes($arr['rlDminsttNm'])."',  ";
		$sql .= "'".$arr['opninRgstClseDt']."', '".$arr['bidNtceNoList']."', '".$pss."')";
		
		//echo $sql.'<br>';
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else echo 'error sql='.$sql.'<br>';
		}
		$newRecord ++;
		return true;
	
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}
// --------------------------------------- function insertTableBidInfo
function insertTableBidInfo($i, $idx,$arr,$pss,$item2) {

		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bfSpecRgstNo'].'</td>';
		
		$tr .= '<td>'.$arr['prdctClsfcNoNm'].'</td>';
		$tr .= '<td>'.$arr['asignBdgtAmt'].'</td>';
		$tr .= '<td>'.$arr['rgstDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['rlDminsttNm'].'</td>';
		$tr .= '<td style="text-align: right;">'.$arr['opninRgstClseDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$pss.'</td>';
		$tr .= '</tr>';

	echo $tr;
}
$kwd = '';
$dminsttNm = '';
$numOfRows = '999';
$pss = '사물';
$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
//var_dump($response1);
//exit;
$totCnt= count($item1);
$idx=1;
$i=1;
if (count($item1)>0) {
	foreach($item1 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		insertTableBidInfo($i,$idx,$arr,$pss,$item1);
		
		$i++;
	}
}
$pss = '사공';
$response1 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
$totCnt += count($item1);
if (count($item1)>0) {
	foreach($item1 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		insertTableBidInfo($i,$idx,$arr,$pss,$item1);
		
		$i++;
	}
}

$pss = '사용';
$response1 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
$totCnt += count($item1);
if (count($item1)>0) {
	foreach($item1 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$item22,$ri);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		insertTableBidInfo($i,$idx,$arr,$pss,$item1);
		
		$i++;
	}
}
$sql = "update workdate set workdt='".$startDate1."' where workname='openHrcInfo' ";
$conn->query($sql);
echo ' total = '.$totCnt;
?>
</tbody></table>
 
<script>
document.getElementById("totalRecords").innerHTML = 'total records=' + <?=$totCnt?>>
</script>
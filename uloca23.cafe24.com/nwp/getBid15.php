<?
@extract($_GET);
@extract($_POST);
/*
낙찰정보 수정 보완
$last = 시작 공고번호 -1
22번 줄 $result = $dbConn->getBidNo($conn,$openBidSeq,$last,660); 수정요

dailyDataInsSeq15.php,	dailyDataHandle15.php
*/
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');

$g2bClass = new g2bClass;
$mobile = $g2bClass->MobileCheck(); // "Mobile" : "Computer"

require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$dbConn = new dbConn;
$conn = $dbConn->conn();

$openBidInfo = 'openBidInfo';
$openBidSeq = 'openBidSeq_2018'; //.$g2bClass->dt2duration('2018');

$contn1 = 1;
if (isset($_POST['contn'])) {
	$contn1 = 0;
    // Checkbox is selected
} else $contn1 = 1;
/*$sql = "select last from workdate where workname = 'getBid12'";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc() ) {
	$last = $row['last'];
} */
$last = 20180213072; //20180109490;
if ($last == '') $last='00';
//echo ' workdt='.$workdt.' contn='.$contn;

$sql = "select count(a.cnt) cnt from (select count(*) cnt from openBidSeq_2018 where bidNtceNo >'".$last."' and LENGTH(bidNtceNo) = 11 group by bidNtceNo) a ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc() ) {
	$lastcnt = $row['cnt'];
}
$sql = "select count(a.cnt) cnt from (select count(*) cnt from openBidSeq_2018 where bidNtceNo <='".$last."' and LENGTH(bidNtceNo) = 11 group by bidNtceNo) a ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc() ) {
	$didcnt = $row['cnt'];
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
<script>
function doit() {
	frm = document.myForm;
	frm.submit();
}
var insIdx=0;
var insTable = 'specData';
var noRow = 0;
var noSeq = 0;
var openBidInfo = 'openBidInfo';
var openBidSeq = '<?=$openBidSeq?>';
var openBidSeq_tmp = 'openBidSeq_tmp12';
function insertSeqx() {
	insTable = document.getElementById('specData');
	noRow = insTable.rows.length;
	if (noRow<2)
	{
		//alert("data가 없습니다.");
		move_stop();
		return;
	}
	move();
	insIdx=1;
	insertSeq2x();
	//clog('lth='+lth);
	
	
}
function insertSeq2x() {
	if (insIdx < noRow) // noRow
	{
		bidNtceNo = insTable.rows[insIdx].cells[1].innerHTML.split('-')[0];
			bididx = '0'; //insTable.rows[insIdx].cells[7].innerHTML;
			pss = ''; //insTable.rows[insIdx].cells[6].innerHTML;
			//clog('noRow='+noRow+'/'+bidNtceNo+'/'+bididx+'/'+insIdx);
			url = 'dailyDataInsSeq15.php?bidNtceNo='+bidNtceNo+'&bidNtceOrd=00&openBidInfo='+openBidInfo + '&openBidSeq='+openBidSeq+'&bididx='+bididx+'&pss='+pss;
			//alert(url);
			getAjax(url,searchDaily3x);
	}
}
function searchDaily3x(data) {
	// 응찰건수 컬럼
	clog('searchDaily3x /'+data+'/'+insIdx);
	datas = data.split('|');
	insTable.rows[insIdx].cells[3].innerHTML = datas[2];
	insTable.rows[insIdx].cells[4].innerHTML = datas[4];
	insIdx ++;
	noSeq += eval(datas[2]);
	document.getElementById('proccnt').value=insIdx;
	document.getElementById('seqcnt').value=datas[2];
	if (insIdx < noRow) insertSeq2x();
	else { 
		move_stop();
		movetmp();
		noRow --;
		alert('입찰정보= ' + noRow + '건 개찰정보= ' + noSeq + ' 건이 수집되었습니다.'); 
		return; 
	}
}
function movetmp() {
	url = 'dailyDataHandle15.php?openBidSeq='+openBidSeq;
			//alert(url);
			getAjax(url,searchDaily4x);
}
function searchDaily4x(data) {

}
</script>
<body>
<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 60%;margin-top: -10px; margin-left: -50px; '>
  <img src='http://uloca.net/g2b/loading3.gif' width='100px' height='100px'>
</div>

<center>응찰기록/면허제한 수집</center>
<form action="getBid12.php" name="myForm" id="myform" method="post" >

<div id='procdiv' style='visibility:inline;position: fixed; top: 220px; right: 20px;'>
<input type="text" name="totalcnt" id="totalcnt" value="" style='text-align:center;' size=3 />
<input type="text" name="proccnt" id="proccnt" value=""  style='text-align:center;' size=3 />
<input type="text" name="seqcnt" id="seqcnt" value=""  style='text-align:center;' size=3 />
</div>

<div id="contents">
<div class="detail_search" >


<table align=center cellpadding="0" cellspacing="0" width="700px">
		<colgroup>
			<col style="width:30%;" /><col style="width:auto;" />
		</colgroup>
		<tbody>

		<tr>
				<th>마지막 처리한 공고번호&nbsp;&nbsp;</th>
				<td>
					<div>&nbsp;
						
						<input autocomplete="off" type="text" maxlength="20" name="last" id="last" value="<?=$last?>" style="width:126px; text-align:center" readonly='readonly' />&nbsp;&nbsp;<?=$openBidInfo?>&nbsp;/&nbsp;<?=$openBidSeq?>
						
						
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

<center><div style='font-size:14px; color:blue;font-weight:bold'>- 응찰기록/면허제한 수집 api-> db -</div></center>
<div id='totalRecords'>total records=<?=$countItem?></div>

<table class="type10" id="specData">
<thead>
    <tr>
        <th width="5%;">순위</th>
		<th width="15%;">공고번호</th>
        <th width="20%;">응찰갯수(현재)</th>
        <th width="20%;">응찰업체(보완)</th>
        <th width="40%;">면허제한</th>

        
        
    </tr>
</thead>
<tbody>
<?

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
function insertTableBidInfo($i, $idx,$arr) {

		$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'</td>';
		
		$tr .= '<td style="text-align: center;">'.$arr['cnt'].'</td>';
		$tr .= '<td style="text-align: center;"</td>';
		$tr .= '<td ></td>';

		$tr .= '</tr>';

	echo $tr;
}

//---------------------------------------------------------------------------
//$result = $dbConn->getBidNo($conn,$openBidSeq,$last,660);
$result = $dbConn->getBidNo2($conn,$openBidSeq,'20180213072','20180209930');
//select bidNtceNo,count(*) cnt from openBidSeq_2018 where bidNtceNo <='20180213072' and bidNtceNo >='20180209930'  and LENGTH(bidNtceNo) <= 11 group by bidNtceNo desc

//---------------------------------------------------------------------------

$totCnt= $result->num_rows;
$idx=1;
$i=1;
$lastbid = '';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        insertTableBidInfo($i,$idx,$row);
		$lastbid = $row['bidNtceNo'];
		$i++;
    }
} else {
    echo "<tr><td colspan=5 style='text-align:center;color:red'> 데이타가 없습니다.</td></tr>";
}


echo '</tbody></table>';

//exit;



$sql = "update workdate set last='".$lastbid."' where workname='getBid12' ";
//$conn->query($sql);
//echo ' total = '.$totCnt;
?>

 
<script>
document.getElementById("totalRecords").innerHTML = 'total records=' + '<?=$totCnt?>'+'/ 기수집건수='+'<?=number_format($didcnt)?>'+'/ 남은건수='+'<?=number_format($lastcnt)?>';
document.getElementById("totalcnt").value = <?=$totCnt?>;
insertSeqx();
</script>
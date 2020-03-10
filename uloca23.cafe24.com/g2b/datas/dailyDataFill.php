<?
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;

$openBidInfo == 'openBidInfo_2018_2';
$openBidSeq == 'openBidSeq_2018_2';


if ($startDate == '' || $endDate == '') {
	echo '날자가 없습니다.'; exit;
}
if ($pss == '' ) {
	echo '물푸,공사,용역 구분이 없습니다.'; exit;
}
$startDate=$g2bClass->changeDateFormat($startDate);
$endDate=$g2bClass->changeDateFormat($endDate);
echo 'startDate='.$startDate.' endDate='.$endDate;
$kwd = '';
$dminsttNm= '';
$numOfRows= 8000;
$pageNo= 1;
$inqryDiv= 2; // 2. 개찰일시 : 개찰일시(opengDt)
$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
$response1 = $g2bClass->getSvrData($bidrdo1,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv);
$json1 = json_decode($response1, true);
$item1 = $json1['response']['body']['items'];
$countItem = count($item1);


?>
<center><div style='font-size:14px; color:blue;font-weight:bold'>- 입찰 정보 -</div></center>
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
// --------------------------------------- function insertopenBidInfo
function insertopenBidInfo($conn,$openBidInfo, $idx,$arr,$pss) {
	$sql = "select bidNtceOrd from ".$openBidInfo." where bidNtceNo = '".$arr['bidNtceNo']."'; ";
	$result = $conn->query($sql);
	//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
	if ($row = $result->fetch_assoc()  ) {
		if ($row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
			$sql = "update ".$openBidInfo." set bidNtceOrd ='". $arr['bidNtceOrd'] . "', ";
			$sql .= "reNtceYn = '". $arr['reNtceYn'] . "', rgstTyNm = '". $arr['rgstTyNm'] . "', ";
			$sql .= "ntceKindNm = '". $arr['ntceKindNm'] . "', bidNtceDt = '". $arr['bidNtceDt'] . "', ";
			$sql .= "ntceInsttCd = '". $arr['ntceInsttCd'] . "', dminsttCd = '". $arr['dminsttCd'] . "', ";
			$sql .= "bidBeginDt = '". $arr['bidBeginDt'] . "', bidClseDt = '". $arr['bidClseDt'] . "', ";
			$sql .= "presmptPrce = '". $arr['presmptPrce'] . "', bidNtceDtlUrl = '". $arr['bidNtceDtlUrl'] . "', ";
			$sql .= "bidNtceUrl = '". $arr['bidNtceUrl'] . "', sucsfbidLwltRate = '". $arr['sucsfbidLwltRate'] . "', ";
			$sql .= "bfSpecRgstNo = '". $arr['bfSpecRgstNo'] . "' ";
			$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item2);
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
		}
		return false; // update
	} else {

		$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
		$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo)';
		// $sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
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
		//	echo($sql);
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else echo 'error sql='.$sql.'<br>';
		}
		return true;
	}
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}

// --------------------------------------- function 
function findjson($arr,$col,$val,$item) {
	$i = 0;
	foreach($item as $arr2 ) {	// $arr['bidNtceNo']
		if ($arr2[$col] == $val) return $i;
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
		$tr .= '<td style="text-align: center;">'.$idx.'</td>';
		$tr .= '<td style="text-align: right;">'.$item2[$ri]['sucsfbidAmt'].'</td>';
		$tr .= '<td style="text-align: right;">'.$item2[$ri]['bidwinnrNm'].'</td>';
		$tr .= '</tr>';

	echo $tr;
}
$tst = '코웨이(주)';

$pss = '물품';
$response2 = $g2bClass->getBidRslt($numOfRows,$pageNo,$inqryDiv,$startDate,$endDate,$pss);
$json2 = json_decode($response2, true);
$item2 = $json2['response']['body']['items'];

//var_dump($item2);
// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

echo 'item2='.count($item2).'<br>';

//var_dump ($item2[0]);
echo '<br'.'-------------------------------------------------------------<br>';
$i=1;
$idx ++;
// main svr loop
foreach($item1 as $arr ) {
	$ri = findjson($arr,'bidNtceNo',$arr['bidNtceNo'],$item2);
	$prtcptCnum = '';
	$bidwinnrNm = '';
	if ($ri>=0) {
		$prtcptCnum = $item2[$ri]['prtcptCnum'];
		$bidwinnrNm = $item2[$ri]['bidwinnrNm'];
	}
	
	// ------------------------------ insert
	$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss);
	//if ($tf == false) continue;
	$idx ++;
	// ------------------------------ insert
	insertTableBidInfo($i,$idx,$arr,$pss,$item2,$ri,$prtcptCnum,$bidwinnrNm);
	
	$i++;
}



?>
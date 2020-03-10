<?
// http://uloca.net/g2b/updatehrc.php?startDate=2019-01-01%2000-00&endDate=2019-01-02-23-59
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'].'/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'].'/classphp/dbConn.php'); 
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn(); 
mysqli_set_charset($conn, 'utf8');


//echo $_SERVER['QUERY_STRING']."<br>"; //test -by jsj 20190102
// -------------------------------------------------------------------------------------
//  입찰공고
//-------------------------------------------------------------------------------------
$inqryDiv=1; // /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
$kwd='';
$dminsttNm='';
$numOfRows = 990;
$pageNo=1;
$startDate=$g2bClass->changeDateFormat($startDate);
$endDate1=$endDate;
$endDate=$g2bClass->changeDateFormat($endDate);
//echo 'startDate='.$startDate.' endDate='.$endDate;
//var_dump($response3);
//echo count($item);

$openBidInfo = 'openBidInfo';

// ------------------------------------------------------------------ test 삭제할것

$pss = '사전공사';
//$startDate = '201902011100';

$response1 = $g2bClass->getSvrData('hrccnstwk',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item12 = $json1['response']['body']['items'];
$totCnt += count($item12);
echo ('<br>'.$startDate.'/'.$endDate.'/'.$pss.'/'.$totCnt.'<br>');
if (count($item12)>0) {
	foreach($item12 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		
		$i++;
	}
} 
$pss = '사전물품';
//$startDate = '201902011100';

$response1 = $g2bClass->getSvrData('hrcthing',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item11 = $json1['response']['body']['items'];
$totCnt += count($item11);
echo ('<br>'.$startDate.'/'.$endDate.'/'.$pss.'/'.$totCnt.'<br>');
if (count($item11)>0) {
	foreach($item11 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		
		$i++;
	}
} 

$pss = '사전용역';
//$startDate = '201902011100';

$response1 = $g2bClass->getSvrData('hrcservc',$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,'1','1');
$json1 = json_decode($response1, true);
$item13 = $json1['response']['body']['items'];
$totCnt += count($item13);
echo ('<br>'.$startDate.'/'.$endDate.'/'.$pss.'/'.$totCnt.'<br>');
if (count($item13)>0) {
	foreach($item13 as $arr ) {
		
		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
		//if ($tf == false) continue;
		$idx ++;
		// ------------------------------ insert
		
		$i++;
	}
} 
exit;  // -----------------------------------------------------------






// --------------------------------------- function insertopenBidInfo
function insertopenBidInfoHrc($conn,$openBidInfo, $idx,$arr,$pss,$g2bClass,$itemLimit) {
	$lcnsLmtNm = ''; // 뒤에 일괄적으로 업데이트 $g2bClass->findColumn($itemLimit,'bidNtceNo',$arr['bidNtceNo'],'lcnsLmtNm');
	$bidNtceNo = $arr['bidNtceNo'];
	if ($bidNtceNo ==  '') $bidNtceNo = $arr['bfSpecRgstNo'];
	$sql = "select bidNtceOrd from ".$openBidInfo." where bidNtceNo = '".$bidNtceNo."'; ";
	$result = $conn->query($sql);
	//echo $sql;
	//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
	if ($row = $result->fetch_assoc()  ) {
		if (false) { // update 안함..$row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
			$sql = "update ".$openBidInfo." set bidNtceDt = '". $arr['rcptDt'] . "', ";
			$sql .= "ntceInsttCd = '". $arr['ntceInsttCd'] . "', dminsttCd = '". $arr['dminsttCd'] . "', ";
			$sql .= "bidBeginDt = '". $arr['rgstDt'] . "',  ";
			$sql .= "presmptPrce = '". $arr['asignBdgtAmt'] . "',  ";
			$sql .= "ModifyDT = now() ";
			/*$sql .= ", prtcptCnum = '". $arr['prtcptCnum'] . "', ";
			$sql .= "bidwinnrNm = '". $arr['bidwinnrNm'] . "', bidwinnrBizno = '". $arr['bidwinnrBizno'] . "', ";
			$sql .= "sucsfbidAmt = '". $arr['sucsfbidAmt'] . "', sucsfbidRate = '". $arr['sucsfbidRate'] . "', ";
			$sql .= "rlOpengDt = '". $arr['rlOpengDt'] . "', bidwinnrCeoNm = '". $arr['bidwinnrCeoNm'] . "' ";
			*/
			$sql .=" where bidNtceNo='".$arr['bfSpecRgstNo']."';";
			$conn->query($sql);
		}
		return false; // update
	} else {
/*품명	prdctClsfcNoNm 발주기관명	orderInsttNm 실수요기관명	rlDminsttNm 배정예산금액	asignBdgtAmt
	접수일시	rcptDt 사전규격등록번호	bfSpecRgstNo
	물품상세목록	prdctDtlList 등록일시	rgstDt */

		//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql = 'insert into '.$openBidInfo.' ( bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
		$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
		$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo,lcnsLmtNm,ModifyDT)';
		// $sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
		$sql .= "VALUES ( '".$arr['bfSpecRgstNo']."', '', '" .addslashes($arr['prdctClsfcNoNm']). "','" . addslashes($arr['orderInsttNm']) . "','" . addslashes($arr['rlDminsttNm']) . "',";
		$sql .= "'".$arr['rcptDt']."','".$pss."', ";
		$sql .= "'', '', ";
		$sql .= "'', '".$arr['rcptDt']."', ";
		$sql .= "'', '', ";
		$sql .= "'".$arr['rgstDt']."', '', ";
		$sql .= "'".$arr['asignBdgtAmt']."', ";
		$sql .= "'', '', '0',";
		$sql .= "'".$arr['bfSpecRgstNo']."', '',now() ) "; 
		/*  http://apis.data.go.kr/1230000/ScsbidInfoService/getScsbidListSttusThngPPSSrch
		$sql .= "'".$arr['prtcptCnum']."', ";
		$sql .= "'".$arr['bidwinnrNm']."', '".$arr['bidwinnrBizno']."', ";
		$sql .= "'".$arr['sucsfbidAmt']."', '".$arr['sucsfbidRate']."', ";
		$sql .= "'".$arr['rlOpengDt']."', '".$arr['bidwinnrCeoNm']."') ";
		*/
		//echo($sql);
		if ($sql != '') {
			if ($conn->query($sql) === TRUE) {}
			//else clog 'error sql='.$sql.'<br>';
		}
		return true;
	}
	//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
}
// --------------------------------------- function insertopenBidInfo
// --------------------------------------- function insertTableBidInfo
function insertTableBidInfo($i, $idx,$arr,$pss) {
	//echo('pss='+$pss+'/arr count'+count($arr));
	$tr = '<tr>';
		$tr .= '<td style="text-align: center;">'.$i.'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['bidNtceNo'].'-'.$arr['bidNtceOrd'].'</td>';
		
		$tr .= '<td>'.$arr['bidNtceNm'].'</td>';
		$tr .= '<td>'.$arr['ntceInsttNm'].'</td>';
		$tr .= '<td>'.$arr['dminsttNm'].'</td>';
		$tr .= '<td style="text-align: center;">'.$arr['opengDt'].'</td>';
		$tr .= '<td style="text-align: center;">'.$pss.'</td>';
		$tr .= '<td style="text-align: center;">'.$idx.'</td>';
		$tr .= '<td style="text-align: right;"></td>';
		$tr .= '</tr>';

	echo $tr;
}
// --------------------------------------- function insertTableBidInfo




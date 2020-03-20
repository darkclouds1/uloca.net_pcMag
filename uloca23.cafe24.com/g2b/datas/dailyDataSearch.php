<?
// http://uloca.net/g2b/datas/dailyDataSearch.php?
// startDate=2018-07-02 00:00&		// 시작일
// endDate=2018-07-03 10:59&		// 종료일
// openBidInfo=openBidInfo_2018_2   // 테이블
// &openBidSeq=openBidSeq_2018_2	// 테이블
@extract($_GET);
@extract($_POST);
ob_start();
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();
mysqli_set_charset($conn, 'utf8');


//echo $_SERVER['QUERY_STRING']."<br>"; //test -by jsj 20190102
// -------------------------------------------------------------------------------------
//  입찰공고
//-------------------------------------------------------------------------------------
$inqryDiv = 1; // /*검색하고자하는 조회구분 1:공고게시일시, 2:개찰일시, 3:입찰공고번호*/
$kwd = '';
$dminsttNm = '';
$numOfRows = 990;
$pageNo = 1;
$startDate = $g2bClass->changeDateFormat($startDate);
$endDate1 = $endDate;
$endDate = $g2bClass->changeDateFormat($endDate);

if ($openBidInfo == '' || $openBidSeq == '') {
	echo '테이블 명이 없습니다.';
	exit;
}
$openBidInfo = 'openBidInfo';
$responseLimit = $g2bClass->getSvrDataLimit($startDate, $endDate, $numOfRows, '1', '1');

$jsonLimit = json_decode($responseLimit, true);
$itemLimit = $jsonLimit['response']['body']['items'];
if (count($itemLimit) > 0) {
	foreach ($itemLimit as $key => $row) {
		$bidNtceNo[$key]  = $row['bidNtceNo'];
	}
}
if (count($item) > 1) array_multisort($bidNtceNo, SORT_DESC, $itemLimit); // 마김일시

$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
//$response = $g2bClass->getBidAllJson($startDate,$endDate,$kwd,$dminsttNm,$num,$inqryDiv);
$response1 = $g2bClass->tot_getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
$item1 = json_decode($response1, true); //['response']['body']['items'];
//var_dump($item1);
//exit;

$bidrdo2 = 'bidcnstwk'; // $bidrdo2 = '공사';
//getSvrData($bidrdo,$startDate,$endDate,$kwd,$dminsttNm,$numOfRows,$pageNo,$inqryDiv)
$response2 = $g2bClass->tot_getSvrData($bidrdo2, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
$item2 = json_decode($response2, true); //$json2['response']['body']['items'];

$bidrdo3 = 'bidservc'; // $bidrdo2 = '용역';
$response3 = $g2bClass->tot_getSvrData($bidrdo3, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
$item3 = json_decode($response3, true); //$json3['response']['body']['items'];

$countItem = count($item1) + count($item2) + count($item3);
echo '입찰공고 건수 = ' . $countItem . ' ';
//exit;

$sql = 'select max(idx) idx from ' . $openBidInfo;
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
	$idx = $row[0];
	if ($idx == 'NULL') $idx = 0;
}
if (count($item1) > 0) {
	foreach ($item1 as $arr) {
		$pss = '입찰물품'; //$g2bClass->getDivNm($arr);
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
if (count($item2) > 0) {
	foreach ($item2 as $arr) {
		$pss = '입찰공사'; //$g2bClass->getDivNm($arr);
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
if (count($item3) > 0) {
	foreach ($item3 as $arr) {
		$pss = '입찰용역'; //$g2bClass->getDivNm($arr);
		// ------------------------------ insert
		$tf = insertopenBidInfo($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
//echo 'item3';
// -------------------------------------------------------------------------------------
//  사전규격
//-------------------------------------------------------------------------------------
$kwd = '';
$dminsttNm = '';
$numOfRows = '999';
$pss = '사전물품';
$response1 = $g2bClass->tot_getSvrData('hrcthing', $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, '1', '1');
//$json1 = json_decode($response1, true);
$item11 = json_decode($response1, true); //$json1['response']['body']['items'];

//exit;
$totCnt = count($item11);
$idx = 1;
$i = 1;

if (count($item11) > 0) {
	foreach ($item11 as $arr) {

		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		//if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
//echo '사물';
$pss = '사전공사';
$response1 = $g2bClass->tot_getSvrData('hrccnstwk', $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, '1', '1');
//$json1 = json_decode($response1, true);
$item12 = json_decode($response1, true); //$json1['response']['body']['items'];
$totCnt += count($item12);
if (count($item12) > 0) {
	foreach ($item12 as $arr) {

		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		//if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
//echo '사공';
$pss = '사전용역';
$response1 = $g2bClass->tot_getSvrData('hrcservc', $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, '1', '1');
//$json1 = json_decode($response1, true);
$item13 = json_decode($response1, true); //$json1['response']['body']['items'];
$totCnt += count($item13);
if (count($item13) > 0) {
	foreach ($item13 as $arr) {

		// ------------------------------ insert
		$tf = insertopenBidInfoHrc($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit);
		//if ($tf == false) continue;
		$idx++;
		// ------------------------------ insert

		$i++;
	}
}
echo '사전규격 건수 = ' . $totCnt . ' 제한건수=' . count($itemLimit) . '<br>';
//var_dump($itemLimit);

// -------------------------------------------------------------------------------------
//  개찰일시
//-------------------------------------------------------------------------------------
$inqryDiv = 2; // 2. 개찰일시 : 개찰일시(opengDt)

$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
$response1 = $g2bClass->tot_getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
//$json1 = json_decode($response1, true);
$item1 = json_decode($response1, true); //$json1['response']['body']['items'];
//var_dump($item1);
//exit;
$bidrdo2 = 'bidcnstwk'; // $bidrdo2 = '공사';
$response2 = $g2bClass->tot_getSvrData($bidrdo2, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
//$json2 = json_decode($response2, true);
$item2 = json_decode($response2, true); //$json2['response']['body']['items'];

$bidrdo3 = 'bidservc'; // $bidrdo2 = '용역';
$response3 = $g2bClass->tot_getSvrData($bidrdo3, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
//$json3 = json_decode($response3, true);
$item3 = json_decode($response3, true); //$json3['response']['body']['items'];

$countItem = count($item1) + count($item2) + count($item3);


?>
<!--
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
-->
<!-- 입찰 결과 -->
<center>
	<div style='font-size:14px; color:blue;font-weight:bold'>- 개찰 정보 -</div>
</center>
<div id='totalRecords'>total records=<?= $countItem ?> (사전규격은 미포함)</div>

<table class="type10" id="specData">
	<thead>
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
	</thead>
	<tbody>
		<?

		// --------------------------------------- check openBidInfo
		$sql = 'select count(idx) idx from ' . $openBidInfo;
		$result = $conn->query($sql);
		if ($row = $result->fetch_assoc()) {
			$cnt = $row[idx];
		}

		$i = 0;

		$sql = 'select max(idx) idx from ' . $openBidInfo;
		$result = $conn->query($sql);
		if ($row = $result->fetch_assoc()) {
			$idx = $row[idx];
			if ($idx == 'NULL') $idx = 0;
		}
		// --------------------------------------- check openBidInfo

		function updateLcnsLmtNm($conn, $openBidInfo, $itemLimit)
		{
			$bidNtceNo = '';
			$lcnsLmtNm =  '';
			if (count($itemLimit) > 0) {
				foreach ($itemLimit as $arr) {
					if ($arr['bidNtceNo'] == $bidNtceNo) {
						$lcnsLmtNm .= ',' . $arr['lcnsLmtNm'];
					} else {
						if ($bidNtceNo != '') {
							$sql = "update " . $openBidInfo . " set lcnsLmtNm = '" . $lcnsLmtNm . "' where bidNtceNo='" . $bidNtceNo . "';";
							//echo $sql.'<br>';
							$conn->query($sql);
						}
						$lcnsLmtNm = $arr['lcnsLmtNm'];
						$bidNtceNo = $arr['bidNtceNo'];
					}
				}
			}
			if (count($itemLimit) > 0) {
				$sql = "update " . $openBidInfo . " set lcnsLmtNm = '" . $lcnsLmtNm . "' where bidNtceNo='" . $arr['bidNtceNo'] . "';";
				//echo $sql.'<br>';
				$conn->query($sql);
			}
		}
		// --------------------------------------- function insertopenBidInfo
		function insertopenBidInfo($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit)
		{
			$lcnsLmtNm = ''; // 뒤에 일괄적으로 업데이트 $g2bClass->findColumn($itemLimit,'bidNtceNo',$arr['bidNtceNo'],'lcnsLmtNm');
			$bidNtceNo = $arr['bidNtceNo'];
			if ($bidNtceNo ==  '') $bidNtceNo = $arr['bfSpecRgstNo'];
			$sql = "select bidNtceOrd from " . $openBidInfo . " where bidNtceNo = '" . $bidNtceNo . "'; ";
			$result = $conn->query($sql);
			//echo $sql;
			//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
			if ($row = $result->fetch_assoc()) {
				if ($row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
					$sql = "update " . $openBidInfo . " set bidNtceOrd ='" . $arr['bidNtceOrd'] . "', ";
					$sql .= "reNtceYn = '" . $arr['reNtceYn'] . "', rgstTyNm = '" . $arr['rgstTyNm'] . "', ";
					$sql .= "ntceKindNm = '" . $arr['ntceKindNm'] . "', bidNtceDt = '" . $arr['bidNtceDt'] . "', ";
					$sql .= "ntceInsttCd = '" . $arr['ntceInsttCd'] . "', dminsttCd = '" . $arr['dminsttCd'] . "', ";
					$sql .= "bidBeginDt = '" . $arr['bidBeginDt'] . "', bidClseDt = '" . $arr['bidClseDt'] . "', ";
					$sql .= "presmptPrce = '" . $arr['presmptPrce'] . "', bidNtceDtlUrl = '" . $arr['bidNtceDtlUrl'] . "', ";
					$sql .= "bidNtceUrl = '" . $arr['bidNtceUrl'] . "', sucsfbidLwltRate = '" . $arr['sucsfbidLwltRate'] . "', ";
					$sql .= "bfSpecRgstNo = '" . $arr['bfSpecRgstNo'] . "', ";
					$sql .= "lcnsLmtNm = '" . $lcnsLmtNm . "', ";
					$sql .= "locate = '" . $arr['prtcptLmtRgnNm'] . "', ";
					$sql .= "ModifyDT = now() ";
					$sql .= " where bidNtceNo='" . $arr['bidNtceNo'] . "';";
					$conn->query($sql);
				}
				return false; // update
			} else {

				//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
				$sql = 'insert into ' . $openBidInfo . ' ( bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
				$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
				$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo,lcnsLmtNm,locate,ModifyDT)';
				// $sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
				$sql .= "VALUES ( '" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '" . addslashes($arr['bidNtceNm']) . "','" . addslashes($arr['ntceInsttNm']) . "','" . addslashes($arr['dminsttNm']) . "',";
				$sql .= "'" . $arr['opengDt'] . "', '" . $pss . "', ";
				$sql .= "'" . $arr['reNtceYn'] . "', '" . $arr['rgstTyNm'] . "', ";
				$sql .= "'" . $arr['ntceKindNm'] . "', '" . $arr['bidNtceDt'] . "', ";
				$sql .= "'" . $arr['ntceInsttCd'] . "', '" . $arr['dminsttCd'] . "', ";
				$sql .= "'" . $arr['bidBeginDt'] . "', '" . $arr['bidClseDt'] . "', ";
				$sql .= "'" . $arr['presmptPrce'] . "', '" . $arr['bidNtceDtlUrl'] . "', ";
				$sql .= "'" . $arr['bidNtceUrl'] . "', '" . $arr['sucsfbidLwltRate'] . "', ";
				$sql .= "'" . $arr['bfSpecRgstNo'] . "', '" . $lcnsLmtNm . "', '" . $arr['prtcptLmtRgnNm'] . "',now() ) ";

				if ($sql != '') {
					if ($conn->query($sql) === TRUE) {
					}
				}
				return true;
			}
			//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
		}
		// --------------------------------------- function insertopenBidInfo
		function insertopenBidInfoHrc($conn, $openBidInfo, $idx, $arr, $pss, $g2bClass, $itemLimit)
		{
			$lcnsLmtNm = ''; // 뒤에 일괄적으로 업데이트 $g2bClass->findColumn($itemLimit,'bidNtceNo',$arr['bidNtceNo'],'lcnsLmtNm');
			$bidNtceNo = $arr['bidNtceNo'];
			if ($bidNtceNo ==  '') $bidNtceNo = $arr['bfSpecRgstNo'];
			$sql = "select bidNtceOrd from " . $openBidInfo . " where bidNtceNo = '" . $bidNtceNo . "'; ";
			$result = $conn->query($sql);
			//echo $sql;
			//if ($row = $result->fetch_assoc()) $bidNtceOrd = $row['Ord'];
			if ($row = $result->fetch_assoc()) {
				if (false) { // update 안함..$row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
					$sql = "update " . $openBidInfo . " set bidNtceDt = '" . $arr['rcptDt'] . "', ";
					$sql .= "ntceInsttCd = '" . $arr['ntceInsttCd'] . "', dminsttCd = '" . $arr['dminsttCd'] . "', ";
					$sql .= "bidBeginDt = '" . $arr['rgstDt'] . "',  ";
					$sql .= "presmptPrce = '" . $arr['asignBdgtAmt'] . "',  ";
					$sql .= "ModifyDT = now() ";
					$sql .= " where bidNtceNo='" . $arr['bfSpecRgstNo'] . "';";
					$conn->query($sql);
				}
				return false; // update
			} else {
				/*품명	prdctClsfcNoNm 발주기관명	orderInsttNm 실수요기관명	rlDminsttNm 배정예산금액	asignBdgtAmt
	접수일시	rcptDt 사전규격등록번호	bfSpecRgstNo
	물품상세목록	prdctDtlList 등록일시	rgstDt */

				//$sql = 'insert into '.$openBidInfo.' (idx, bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
				$sql = 'insert into ' . $openBidInfo . ' ( bidNtceNo, bidNtceOrd, bidNtceNm,ntceInsttNm, dminsttNm,opengDt,bidtype,';
				$sql .= 'reNtceYn,rgstTyNm,ntceKindNm,bidNtceDt,ntceInsttCd,dminsttCd,bidBeginDt,bidClseDt,';
				$sql .= 'presmptPrce,bidNtceDtlUrl,bidNtceUrl,sucsfbidLwltRate,bfSpecRgstNo,lcnsLmtNm,ModifyDT)';
				// $sql .= 'prtcptCnum,bidwinnrNm,bidwinnrBizno,sucsfbidAmt,sucsfbidRate,rlOpengDt,bidwinnrCeoNm) '; 
				$sql .= "VALUES ( '" . $arr['bfSpecRgstNo'] . "', '', '" . addslashes($arr['prdctClsfcNoNm']) . "','" . addslashes($arr['orderInsttNm']) . "','" . addslashes($arr['rlDminsttNm']) . "',";
				$sql .= "'" . $arr['rcptDt'] . "','" . $pss . "', ";
				$sql .= "'', '', ";
				$sql .= "'', '" . $arr['rcptDt'] . "', ";
				$sql .= "'', '', ";
				$sql .= "'" . $arr['rgstDt'] . "', '', ";
				$sql .= "'" . $arr['asignBdgtAmt'] . "', ";
				$sql .= "'', '', '0',";
				$sql .= "'" . $arr['bfSpecRgstNo'] . "', '',now() ) ";
				if ($sql != '') {
					if ($conn->query($sql) === TRUE) {
					}
					//else clog 'error sql='.$sql.'<br>';
				}
				return true;
			}
			//select * from openBidInfo_2018_2 where bidNtceNo ='20180626257'
		}
		// --------------------------------------- function insertopenBidInfo
		// --------------------------------------- function insertTableBidInfo
		function insertTableBidInfo($i, $idx, $arr, $pss)
		{
			//echo('pss='+$pss+'/arr count'+count($arr));
			$tr = '<tr>';
			$tr .= '<td style="text-align: center;">' . $i . '</td>';
			$tr .= '<td style="text-align: center;">' . $arr['bidNtceNo'] . '-' . $arr['bidNtceOrd'] . '</td>';

			$tr .= '<td>' . $arr['bidNtceNm'] . '</td>';
			$tr .= '<td>' . $arr['ntceInsttNm'] . '</td>';
			$tr .= '<td>' . $arr['dminsttNm'] . '</td>';
			$tr .= '<td style="text-align: center;">' . $arr['opengDt'] . '</td>';
			$tr .= '<td style="text-align: center;">' . $pss . '</td>';
			$tr .= '<td style="text-align: center;">' . $idx . '</td>';
			$tr .= '<td style="text-align: right;"></td>';
			$tr .= '</tr>';

			echo $tr;
		}
		// --------------------------------------- function insertTableBidInfo

		$i = 1;
		$idx++;
		if (count($item1) > 0) {
			foreach ($item1 as $arr) {
				$pss = '입찰물품'; //$g2bClass->getDivNm($arr);
				// ------------------------------ insert
				//$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
				//if ($tf == false) continue;
				$idx++;
				// ------------------------------ insert
				insertTableBidInfo($i, $idx, $arr, $pss);

				$i++;
			}
		}

		if (count($item2) > 0) {
			foreach ($item2 as $arr) {
				$pss = '입찰공사'; //$g2bClass->getDivNm($arr);
				// ------------------------------ insert
				//$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
				//if ($tf == false) continue;
				$idx++;
				// ------------------------------ insert
				insertTableBidInfo($i, $idx, $arr, $pss);

				$i++;
			}
		}
		if (count($item3) > 0) {
			foreach ($item3 as $arr) {
				$pss = '입찰용역'; //$g2bClass->getDivNm($arr);
				// ------------------------------ insert
				//$tf = insertopenBidInfo($conn,$openBidInfo,$idx,$arr,$pss,$g2bClass,$itemLimit);
				//if ($tf == false) continue;
				$idx++;
				// ------------------------------ insert
				insertTableBidInfo($i, $idx, $arr, $pss);

				$i++;
			}
		}

		/* 
$url = 'http://apis.data.go.kr/1230000/PubDataOpnStdService/getDataSetOpnStdScsbidInfo'; // 데이터셋 개방표준에 따른 낙찰정보
예정가격 rsrvtnPrce varchar(21)
기초금액 bssAmt varchar(21)
추가
*/
		// $bsnsDivCd; /*업무구분코드가 1이면 물품, 2면 외자, 3이면 공사, 5면 용역
		function updateopenBidInfo($conn, $openBidInfo, $idx, $arr, $bidNtceNo)
		{
			//echo '<br>'.$bidNtceNo .'/'. $arr['bidNtceNo'];
			if ($bidNtceNo == $arr['bidNtceNo']) return $bidNtceNo;
			$bidNtceNo = $arr['bidNtceNo'];

			$sql = "update openBidInfo set rsrvtnPrce = '" . $arr['rsrvtnPrce'] . "', ";
			$sql .= "bssAmt = '" . $arr['bssAmt'] . "', ";
			$sql .= "ModifyDT = now() ";
			$sql .= " where bidNtceNo='" . $bidNtceNo . "';";
			//echo $sql;
			$conn->query($sql);

			return $bidNtceNo; // update
		}
		/*
$bidNtceNo='';
$bsnsDivCd = '1'; // 물품
$items = $g2bClass->tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
$amtcnt=count($items);
echo('  예정가격 기초금액 물품---------- amtcnt='.$amtcnt);
if (count($items)>0) {
	foreach($items as $arr ) {
		// ------------------------------ update
		$bidNtceNo = updateopenBidInfo($conn,$openBidInfo,$idx,$arr,$bidNtceNo);
		//if ($tf == false) continue;
		//$idx ++;
		// ------------------------------ update
		
		$i++;
	}
}
ob_flush() ;
flush() ;
$bsnsDivCd = '3'; // 공사
$items = $g2bClass->tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
$amtcnt=count($items);
echo('<br><br>  예정가격 기초금액 공사---------- amtcnt='.$amtcnt);
if (count($items)>0) {
	foreach($items as $arr ) {
		// ------------------------------ update
		$bidNtceNo = updateopenBidInfo($conn,$openBidInfo,$idx,$arr,$bidNtceNo);
		//if ($tf == false) continue;
		//$idx ++;
		// ------------------------------ update
		
		$i++;
	}
}
ob_flush() ;
flush() ;
$bsnsDivCd = '5'; // 용역
$items = $g2bClass->tot_getSvrDataOpnStd($startDate,$endDate,$numOfRows,$pageNo,$bsnsDivCd);
$amtcnt=count($items);
echo('<br><br>  예정가격 기초금액 용역---------- amtcnt='.$amtcnt);
if (count($items)>0) {
	foreach($items as $arr ) {
		// ------------------------------ update
		$bidNtceNo = updateopenBidInfo($conn,$openBidInfo,$idx,$arr,$bidNtceNo);
		//if ($tf == false) continue;
		//$idx ++;
		// ------------------------------ update
		
		$i++;
	}
}
ob_flush() ;
flush() ;
*/

		$sql = "update workdate set workdt='" . $endDate1 . "' where workname='openBidInfo' ";
		$conn->query($sql);
		//clog $sql;

		updateLcnsLmtNm($conn, $openBidInfo, $itemLimit); // 입찰제한코드

		?>

	</tbody>
</table>
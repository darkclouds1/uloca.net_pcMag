<?

use PhpMyAdmin\Console;
use function YoastSEO_Vendor\GuzzleHttp\Psr7\str;

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

ini_set('max_execution_time', 600);
echo "max_execution_time=" .ini_get('max_execution_time'). "<br>";

@extract($_GET);
@extract($_POST);
ob_start();
// http://uloca.net/g2b/datas/dailyDataSearch.php?startDate=2018-07-02 00:00&endDate=2018-07-03 10:59&openBidInfo=openBidInfo_2018_2&openBidSeq=openBidSeq_2018_2
//http://uloca.net/nwp/dailyDataFill.php?startDate=20180930&endDate=20180930&pss=%EB%AC%BC%ED%92%88
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/g2bClass.php'); //'/g2b/classPHP/g2bClass.php');
require($_SERVER['DOCUMENT_ROOT'] . '/classphp/dbConn.php');
$g2bClass = new g2bClass;
$dbConn = new dbConn;
$conn = $dbConn->conn();

$dur = '2020'; 
$startDate = ''; $endDate = ''; $contn = 0;
$openBidInfo = 'openBidInfo';
$openBidSeq  = 'openBidSeq'. '_' .$dur;

if ($_POST['startDate'] != '') {
	$startDate = date("Ymd", strtotime($_POST['startDate']));
	$endDate   = date("Ymd", strtotime($_POST['endDate']));

} else {
	//최초 화면 열릴땐 startDate가 없으므로 DB의 lastdb를 가져옴
	$sql = " SELECT workdt, lastdt FROM workdate WHERE workname = 'dailyDataFill'";
	$result = $conn->query($sql);
	if ($row = $result->fetch_assoc()) {
		$workdt = $row['workdt'];
		$startDate = date("Y-m-d", strtotime($row['lastdt']));	
		$endDate = date("Y-m-d", strtotime($row['lastdt']));	
	}
	echo "db에서 가져온 startDate=" .$startDate. ", endDate=" .$endDate. ", workdt=" .$workdt. "<br>";
}
$dur = substr($startDate, 0, 4);

// 계속설정
if (isset($_POST['contn'])) {
	$contn = 1;    //on
} else {
	$contn = 0;
}

echo " startDate=" .$startDate. ", enddate=" .$endDate. ", contn=" .$_POST['contn']. "<br>";
var_dump ($_POST);



?>

<!DOCTYPE html>
<html>
<head>
	<title>낙찰결과 Fill/<?= $bidNtceNo ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="format-detection" content="telephone=no">
	<!--//-by jsj 전화걸기로 링크되는 것 막음 -->

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/g2b/css/g2b.css?version=20190103" />
	<link rel="stylesheet" href="/jquery/jquery-ui.css">

	<script src="/jquery/jquery.min.js"></script>
	<script src="/jquery/jquery-ui.min.js"></script>
	<script src="/js/common.js?version=20190203"></script>
	<script src="/g2b/g2b.js?version=20190203"></script>
	<script src="/g2b/g2b_2019.js?version=20190203"></script>
	<script>
		var stopOn = false;
		function doit() {
			move();
			frm = document.myForm;
			frm.submit();
		}

		function stopit() {
			var contn = document.getElementById("contn");
			sotopOn = true;
			contn.checked = false;
			alert("stop!="+stopOn)
		}

		function donextday() {
			frm = document.myForm;

			if (frm.contn.checked) {
				dts = dateAddDel(frm.startDate.value, 1, 'd');
				frm.startDate.value = dts;
				frm.endDate.value = dts;
				setTimeout(function() {
					if (!stopOn) {
						move();
						frm.submit();
					}
				}, 3000);
			}
			return;
		}
	</script>

<body onload="javascript:donextday();">
	<form action="dailyDataFill_1.php" name="myForm" id="myform" method="post">
		<div id="contents">
			<div class="detail_search">
				<table align=center cellpadding="0" cellspacing="0" width="700px">
					<colgroup>
						<col style="width:20%;" />
						<col style="width:auto;" />
					</colgroup>
					<tbody>
						<tr>
							<th>기간</th>
							<td>
									<input type="date" name="startDate" id="startDate" value="<?= $endDate ?>" onchange='document.getElementById("endDate").value = this.value' />
									~
									<input type="date" name="endDate" id="endDate" value="<?= $endDate ?>"  />

									1주일 이내-하루
									<input type="checkbox" name="contn" id="contn" <? if ($contn) { ?> checked=checked <? } ?>>계속

									<div id="datepicker"></div>
				</div>
				</td>
				</tr>
				</table>
				<div class="btn_area">
					<!-- input type="submit" class="search" value="검색" onclick="searchx()" /-->&nbsp;&nbsp;
					<a onclick="doit();" class="search">실행</a>
					<a onclick="stopit();" class="search">STOP</a>

				</div>
		</div>
	</form>
	<div id='loading' style='display: none; position: fixed; width: 100px; height: 100px; top: 35%;left: 50%;margin-top: -10px; margin-left: -50px; '>
		<img src='http://uloca23.cafe24.com/g2b/loading3.gif' width='100px' height='100px'>
	</div>

	<center>
		<div style='font-size:14px; color:blue;font-weight:bold'>- 입찰정보 / 낙찰업데이트 1등정보 (입찰/낙찰 연관없음) <br>
			입찰정보(등록일시기준): DB에 없는것만 INSERT, 낙찰정보(개찰일시기준): DB에 공고번호로 업데이트 </div>
	</center>

	<table class="type10" id="specData" style="text-align: left; border: 0px solid #dddddd; word-break:break-all;">
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

			<?php
			// 1) 입찰정보로 업데이트
			//  - 입찰방식: bidMethdNm, 입력(전자입찰, 직찰 등)
			//  - 공고종류: ntceKindNm, 일반, 변경, 취소, 재입찰, <연기>, 긴급, 갱신, 긴급갱신
			//  - 입찰방식: bidMethdNm,  전자입찰 외 직찰, 우편 등은 낙찰결과가 없음
			//  - 등록타입: '연계기관 공고건' 은 낙찰결과 없음
			function insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq)
			{
				global $newInfoRecord, $updateOpenBidInfo;
				// 기존 DB 공고번호 조회
				$sql =  " SELECT idx, bidNtceNo,  MAX(bidNtceOrd) FROM " . $openBidInfo . " WHERE 1";
				$sql .= "    AND bidNtceNo = '" . $arr['bidNtceNo'] .  "' ";
				$sql .= "    AND bidNtceOrd= '" . $arr['bidNtceOrd'] . "' ";
				if (!($dbResult = $conn->query($sql))) echo "Error sql= " . $sql;

				// 입찰정보 있으면 업데이트
				if ($row = $dbResult->fetch_assoc()) {
				 	if ($row['bidNtceOrd'] < $arr['bidNtceOrd'] || $arr['bidClseDt'] == NULL || $arr['bidClseDt'] == '') {
					// if ($dbResult->num_rows > 1) {
						$sql = " UPDATE openBidInfo ";
						$sql .= "   SET bidNtceOrd ='"        .$arr['bidNtceOrd'] . "', ";
						$sql .= "       bidtype = '"          .$pss . "', ";                       	// 물품,공사,용역
						$sql .= "       reNtceYn = '"         .$arr['reNtceYn'].         "', ";		// 재공고 여부
						$sql .= "       rgstTyNm = '"         .$arr['rgstTyNm'].         "', ";		// 등록유형명 '연계기관 공고건'
						$sql .= "       ntceKindNm = '"       .$arr['ntceKindNm'].       "', ";		// 공고종류명 '일반','변경','취소','재입찰','연기','긴급',갱신','긴급갱신'
						$sql .= "       bidNtceDt = '"        .$arr['bidNtceDt'] .       "', ";
						$sql .= "       ntceInsttCd = '"      .$arr['ntceInsttCd'] .     "', ";
						$sql .= "       dminsttCd = '"        .$arr['dminsttCd'] .       "', ";
						$sql .= "       bidMethdNm = '"       .$arr['bidMethdNm'].       "', ";		// 입찰방식명  ' 전자입찰',직찰, 우편,....
						$sql .= "       sucsfbidLwltRate = '" .$arr['sucsfbidLwltRate']. "', "; 	// 낙찰하한율
						$sql .= "       bidBeginDt =       '" .$arr['bidBeginDt'].       "', ";		// 입찰개시일자
						$sql .= "       bidClseDt =        '" .$arr['bidClseDt'].        "', ";		// 입찰마감일자
						$sql .= "       presmptPrce = '"      .$arr['presmptPrce'] .     "', ";
						$sql .= "       bidNtceDtlUrl = '"    .$arr['bidNtceDtlUrl'] .   "', ";
						$sql .= "       bidNtceUrl = '"       .$arr['bidNtceUrl'] .      "', ";
						$sql .= "       bfSpecRgstNo = '"     .$arr['bfSpecRgstNo'].     "', ";		// 사전규격 등록번호
						$sql .= "       locate = '"           .$arr['prtcptLmtRgnNm'] .  "', ";
						$sql .= "       ModifyDT = now() ";
						$sql .= " WHERE bidNtceNo = '"        .$arr['bidNtceNo'].        "'  ";
						$sql .= "   AND bidNtceOrd = '"       .$arr['bidNtceOrd'].       "'  ";

						if ($conn->query($sql)) {
							$updateOpenBidInfo++;
						} else {
							echo "ln213 error sql=" .$sql. "<br>";
							return false;
						}
					}
					return true;
				} else {
					// 입찰정보 없으면 신규입력 후 낙찰결과 입력
					// - rgstTyNm 등록유형: 입찰공고가 "연계기관 공고건", "조달청 또는 나라장터 자체 공고건","'나라장터 기타 공고건"
					// - ntceKindNm 공고종류: 공고의 공고상태명으로 일반, 변경, 취소, 재입찰, 연기, 긴급, 갱신, 긴급갱신 
					// - bidMethdNm 입찰방식명: 전자입찰,전자입찰/직찰,전자/직찰/우편/상시,직찰/우편/상시,우편/상시,전자시담,복수견적(역경매),직찰/우편,전자시담(다자간)
					$sql = 'REPLACE INTO ' . $openBidInfo . ' (bidNtceNo, bidNtceOrd, bidNtceNm, ntceInsttNm, dminsttNm, opengDt, bidtype,';
					$sql .= '                                  reNtceYn, rgstTyNm, ntceKindNm, bidMethdNm, bidNtceDt, ntceInsttCd, dminsttCd, bidBeginDt, bidClseDt,';
					$sql .= '                                  presmptPrce, bidNtceDtlUrl, bidNtceUrl, sucsfbidLwltRate, bfSpecRgstNo)';
					$sql .= "VALUES ('"     .$arr['bidNtceNo'].     "', '" . $arr['bidNtceOrd'].       "', ";
					$sql .= "'" . addslashes($arr['bidNtceNm']).    "', '" . addslashes($arr['ntceInsttNm']). "', ";
					$sql .= "'" . addslashes($arr['dminsttNm']).    "', '" . $arr['opengDt'].          "', '" .$pss. "', ";
					$sql .= "'"             .$arr['reNtceYn'].      "', '" . $arr['rgstTyNm'].         "', ";
					$sql .= "'"             .$arr['ntceKindNm'].    "', '" . $arr['bidMethdNm'].       "', '" .$arr['bidNtceDt']. "', ";
					$sql .= "'"             .$arr['ntceInsttCd'].   "', '" . $arr['dminsttCd'].        "', ";
					$sql .= "'"             .$arr['bidBeginDt'].    "', '" . $arr['bidClseDt'].        "', ";
					$sql .= "'"             .$arr['presmptPrce'].   "', '" . $arr['bidNtceDtlUrl'].    "', ";
					$sql .= "'"             .$arr['bidNtceUrl'].    "', '" . $arr['sucsfbidLwltRate']. "', ";
					$sql .= "'"             .$arr['bfSpecRgstNo'].  "') ";
					if ($conn->query($sql)) {
						$newInfoRecord++;
					} else {
						echo "ln239 error sql=" .$sql. "<br>";
					}
				}
				//-----------------------------
				// 낙찰이력 SEQ 입력
				// time-out 문제로 ajax호출필요
				//-----------------------------
				$rtf = openBidSeq_Update($g2bClass, $conn, $arr['bidNtceNo'], $arr['bidNtceOrd'], $openBidSeq);
				return true;

			}

			// 낙찰SEQ 업뎃::공고번호, 공고차수로 낙찰 API call -by jsj 20200328
			// 입찰정보에 1순위 및 낙찰_seq 두테이블에 모두 업뎃
			// $openBidSeq = 테이블네임_년도 포함
			function openBidSeq_Update($g2bClass, $conn, $bidNtceNo, $bidNtceOrd, $openBidSeq_xxxx)
			{
				// 낙찰현황 조회 getRsltData (max.999)
				// (ex.getRsltDateAll-> 999개 이상)
				$response1 = $g2bClass->getRsltDataAll($bidNtceNo, $bidNtceOrd);
				$json1 = json_decode($response1, true);
				$item1 = $json1['response']['body']['items'];
				$cnt = count($item1);
				$i = 1; // 전체 개찰 업체수
				$k = 1; // 순위 저장용


				if ($cnt == 0) return false;
				
				// echo "ln258 낙찰현황cnt=" .$cnt. " bidNtceNo=" .$bidNtceNo. " bidNtceOrd=" .$bidNtceOrd. "<br>"; exit;

				foreach ($item1 as $arr) { //foreach element in $arr
					//---------------------------
					$rmrk = addslashes($arr['rmrk']); // '낙찰하한선 미달' 등
					$k = (int) $arr["opengRank"];
					switch ($k) {
						case 0:
							$Rank_rmark = $rmrk;      // 순위 또는 비고를 임시저장
							break;
						default:
							$Rank_rmark = (string) $k; // 순위 대입
							break;
					}

					// 속도문제 고려, 입력 순위 조정 (1순위 필수)
					if ((int) $arr['opengRank'] == 0 ) continue;  // 순위 없음					
					if ($k > 1) break;  					  	// 1순위만 입력

					// if ((int)$arr['opengRank'] >  999 ) continue;  // 1순위
					// openBidInfo 에 1순위 정보 업데이트 (공고차수 전체) -by jsj 190601
					if ((int) $arr['opengRank'] == 1) {
						$sql = " UPDATE openBidInfo SET ";
						$sql .= "  prtcptCnum =    '" . $cnt .                         "', ";		// 참가업체수
						$sql .= "  bidwinnrNm =    '" . addslashes($arr['prcbdrNm']) . "', ";		// 최종낙찰업체명
						$sql .= "  bidwinnrBizno = '" . $arr['prcbdrBizno'] .          "', ";		// 사업자번호
						$sql .= "  bidwinnrCeoNm = '" . $arr['bidwinnrCeoNm'] .        "', ";		// 대표자명
						$sql .= "  sucsfbidAmt =   '" . $arr['sucsfbidAmt'] .          "', ";		// 최종낙찰금액
						$sql .= "  sucsfbidRate =  '" . $arr['bidprcrt'] .             "', ";		// 투찰율 = 투찰금액/예정가격
						$sql .= "  bidwinnrTelNo = '" . $arr['bidwinnrTelNo'] .        "', ";		// 업체연락처
						$sql .= "  rlOpengDt =     '" . $arr['rlOpengDt'] .            "' ";		// 실개찰일시
						$sql .= "  progrsDivCdNm = '"  . "개찰완료" . "', ";  		  			 	 // 개찰완료		
						$sql .= "  modifyDT = now()";
						$sql .= "  WHERE bidNtceNo='" . $bidNtceNo . "' ";
						if (!($conn->query($sql))) echo "Error $sql=" . $sql;
						global $updateOpenBidInfo_1th;
						$updateOpenBidInfo_1th++;

					}

					// 입찰공고의 idx를 낙찰정보에(bidindx) 업데이트
					$sql = " SELECT idx FROM openBidInfo WHERE bidNtceNo = '" . $bidNtceNo . "' AND bidNtceOrd = '" . $bidNtceOrd . "' ";
					if ($result0 = $conn->query($sql)) {
						$row = $result0->fetch_assoc();
						$bididx = $row['idx'];
					} else {
						echo "ln293 Error sql=" . $sql . "<br>";
						continue;
					}

					//---------------------------------------------------
					//$openBidSeq_xxxx 에 입찰이력 입력 -by jsj 190601
					//---------------------------------------------------
					$sql  = " REPLACE INTO openBidSeq_tmp ( bidNtceNo, bidNtceOrd, rbidNo, compno, tuchalamt, tuchalrate, selno, tuchaldatetime, remark, bidIdx )";
					$sql .= "  VALUES ( '" .$bidNtceNo. "','"       .$bidNtceOrd. "','"     .$arr['rbidNo']. "','"    .$arr['prcbdrBizno']. "','" .$arr['bidprcAmt']. "',";
					$sql .= "           '" .$arr['bidprcrt']. "','" .$arr['drwtNo1']. "','" .$arr['bidprcDt'] . "','" .$Rank_rmark. "',"          .$bididx . " )";
					if (!($conn->query($sql))) echo "Error sql=" . $sql . "<br>";

					// openBidSeq_xxxx 에 Insert 갯수
					global $insertSeqCnt; //
					$insertSeqCnt++;
					$i += 1;

				} //for eachpro
				return $i; // seq 갯수를 리턴
			}

			// DB에 작업일자 저장
			function workdate($conn, $workname, $workdt, $lastdt)
			{
				$sql = " UPDATE workdate SET workdt='" .$workdt. "', lastdt='" .$lastdt. "'";
				$sql .= " Where workname='" .$workname. "' ";
				if (!($conn->query($sql))) echo "SQL Error: " . $sql;
			}

			//--------------------------------------------------
			// 입찰정보, 낙찰정보, 업체별 낙찰이력 추가
			//--------------------------------------------------
			if ($startDate == '' || $endDate == '') {
				return;
			}

			if (strlen($startDate) == 10) $startDate = substr($startDate, 0, 4) . substr($startDate, 5, 2) . substr($startDate, 8, 2);
			if (strlen($endDate) == 10) $endDate = substr($endDate, 0, 4) . substr($endDate, 5, 2) . substr($endDate, 8, 2);
			$startDate .= '0000'; //=$g2bClass->changeDateFormat($startDate);
			$endDate .= '2359'; //=$g2bClass->changeDateFormat($endDate);

			$kwd = '';
			$dminsttNm = '';
			$numOfRows = 999; //8000;
			$pageNo = 1;

			//-------------------------------------------------------------------------------
			// 입찰공고:: 나라장터 검색조건에 의한 나라장터 입찰공고 정보서비스
			//-------------------------------------------------------------------------------
			$inqryDiv = 1; // 1.등록일시, 2.입찰공고번호, 3.변경일시
			$bidrdo1 = 'bidthing'; //$bidrdo2 = '물품';
			$response11 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json11 = json_decode($response11, true);
			$item11 = $json11['response']['body']['items'];
			$bidrdo1 = 'bidcnstwk'; //$bidrdo2 = '공사';
			$response12 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json12 = json_decode($response12, true);
			$item12 = $json12['response']['body']['items'];
			$bidrdo1 = 'bidservc'; //$bidrdo2 = '용역';
			$response13 = $g2bClass->getSvrData($bidrdo1, $startDate, $endDate, $kwd, $dminsttNm, $numOfRows, $pageNo, $inqryDiv);
			$json13 = json_decode($response13, true);
			$item13 = $json13['response']['body']['items'];
			// Sum
			$item10 = array_merge($item11, $item12, $item13);

			echo "입찰정보=" . count($item10) . ", ";

			//-------------------------------------------------------------------------------
			// 낙찰정보 현황조회 -by jsj 200329
			//-------------------------------------------------------------------------------
			$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
			$pss = '물품';
			$response2 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json2 = json_decode($response2, true);
			$item2 = $json2['response']['body']['items'];
			$pss = '공사';
			$response3 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json3 = json_decode($response3, true);
			$item3 = $json3['response']['body']['items'];
			$pss = '용역';
			$response4 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json4 = json_decode($response4, true);
			$item4 = $json4['response']['body']['items'];
			// 낙찰정보 sum
			$item22 = array_merge($item2, $item3, $item4);

			echo "낙찰현황=" . count($item22) . ", ";

			//-------------------------------------------------------------------------------
			// 낙찰정보 목록조회 -by jsj 200329 ? 
			// 낙찰정보 목록에 있는 낙찰진행(progrsDivCdNm (유찰, 개찰완료, 재입찰)) 
			//-------------------------------------------------------------------------------
			$inqryDiv = 3;	//1.등록일시(공고개찰일시), 2.공고일시, 3.개찰일시, 4.입찰공고번호
			$pss = '물품목록';
			$response5 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json5 = json_decode($response5, true);
			$item5 = $json5['response']['body']['items'];
			$pss = '공사목록';
			$response6 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json6 = json_decode($response6, true);
			$item6 = $json6['response']['body']['items'];
			$pss = '용역목록';
			$response7 = $g2bClass->getBidRslt($numOfRows, $pageNo, $inqryDiv, $startDate, $endDate, $pss);
			$json7 = json_decode($response7, true);
			$item7 = $json7['response']['body']['items'];
			// 낙찰정보 sum
			$item23 = array_merge($item5, $item6, $item7);

			echo "낙찰목록=" . count($item23) . " <br>";

			//var_dump ($item22); exit();
			// http://uloca.net/g2b/datas/dailyDataFill.php?startDate=2018-10-01-00:00&endDate=2018-10-01-23:59&pss=물품

			$i = 1; // 그리드 목록 번호
			$newInfoRecord = 0;	   // 입찰정보 추가 Count
			$updateOpenBidInfo = 0;
			$updateRstCnt = 0; 	   // 낙찰정보 업데이트 count
			$insertSeqCnt = 0; 	   // seq 순위 입력
			$updateProgrsDivCdNm = 0; // 낙찰목록의 진행구분을 입찰공고에 업뎃 
			$updateOpenBidInfo_1th = 0; // openBidSeq_xxxx 입력시 1순위 낙찰정보를 입찰정보에 업뎃
			// ----------------------------------------------
			// 입찰정보 INSERT or UPDATE(낙찰정보아님) -by jsj 200321
			// openBidSeq_xxxx 추가 (낙찰정보도 입력)
			// ----------------------------------------------
			foreach ($item11 as $arr) {
				$pss = '물품';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return		

			}

			foreach ($item12 as $arr) {
				$pss = '공사';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return
			}

			foreach ($item13 as $arr) {
				$pss = '용역';
				$tf = insertOpenBidInfo($g2bClass, $conn, $openBidInfo, $pss, $arr, $openBidSeq); //DB에 있던 공고번호 return
			}

			//------------------------------------------------------------
			// 낙찰목록의 진행구분 업뎃 -by jsj 20200329
			// updateProgrsDivCdNm (progrsDivCdNm: 유찰, 개찰완료, 재입찰)
			//------------------------------------------------------------
			//var_dump ($item23);
			$nonNtceNoCnt = 0;
			foreach ($item23 as $arr) {
				$sql = " SELECT idx, bidNtceOrd, bidNtceNo, progrsDivCdNm FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "' ";
				$sql .= "   AND bidNtceOrd = '" . $arr['bidNtceOrd'] . "' ";
				if (($dbResult = $conn->query($sql)) == false) echo "Error sql= " . $sql;
				if ($row = $dbResult->fetch_assoc()) {
					// 입찰공고에 progrsDivCdNm 진행구분 업데이트 (유찰, 개찰완료, 재입찰)
					if ($row['progrsDivCdNm'] == '') {
						$sql = " UPDATE openBidInfo ";
						$sql .= "   SET progrsDivCdNm = '" .$arr['progrsDivCdNm']. "' ";
						$sql .= " WHERE bidNtceNo     = '" .$row['bidNtceNo']. "' ";
						$sql .= "   AND bidNtceOrd    = '" .$row['bidNtceOrd']. "'";
						if (!($conn->query($sql))) {
							echo "REPLACE Error:" . $sql . "<br>";
						}
						$updateProgrsDivCdNm++; //진행구분 progrsDivCdNm 업데이트 건수
					}
				} else {
					// 업데이트할 공고가 없으면 continue
					// '01' Stauts 에 진행구분 progrsDivCdNm 입력
					if ($row['progrsDivCdNm'] <> '') {
						$sql =  " REPLACE INTO openBidInfo_status ( bidNtceNo, bidNtceOrd, statusCd, progrsDivCdNm )";
						$sql .= " VALUES ('" . $arr['bidNtceNo'] . "', '" . $arr['bidNtceOrd'] . "', '01', '" . $arr['progrsDivCdNm'] . "') ";
						if (!($conn->query($sql))) echo "REPLACE Error:" . $sql . "<br>";
						$nonNtceNoCnt++; // 진행구분 포함해서 Status에 임시 저장
					}
				}
			}

			//------------------------------------------- 
			// 낙찰현황 업데이트 -by jsj 200321 
			// item2~4 입찰정보 merge=item22
			//-------------------------------------------
			foreach ($item22 as $arr) {
				$pss = '낙찰';
				// DB 공고번호 조회
				$sql = " SELECT idx FROM openBidInfo ";
				$sql .= " WHERE bidNtceNo = '" . $arr['bidNtceNo'] . "' ";
				$sql .= "   AND bidNtceOrd = '" . $arr['bidNtceOrd'] . "' ";
				// $sql .= "   AND ntceKindNm NOT IN ('취소', '재입찰', '연기') ";
				// $sql .= "   AND progrsDivCdNm NOT IN ('유찰', '재입찰') ";
				if (!($dbResult = $conn->query($sql))) echo "Error sql= " . $sql;

				if ($dbResult->num_rows > 0) {
					// 낙찰정보 업데이트	
					$sql = "UPDATE openBidInfo SET "; // bidNtceOrd ='".$arr['bidNtceOrd']."', ";
					$sql .= " prtcptCnum = '"    .$arr['prtcptCnum'].    "', ";   // 참가업체수
					$sql .= " bidwinnrNm = '"    .$arr['bidwinnrNm'].    "', ";	// 최종낙찰업체명
					$sql .= " bidwinnrBizno = '" .$arr['bidwinnrBizno']. "', ";	// 최종낙찰업체사업자번호
					$sql .= " sucsfbidAmt = '"   .$arr['sucsfbidAmt'].   "', ";   // 최종낙찰금액
					$sql .= " sucsfbidRate = '"  .$arr['sucsfbidRate'].  "', ";   // 숫자
					$sql .= " rlOpengDt = '"     .$arr['rlOpengDt'].     "', ";	// 실개찰일시
					$sql .= " bidwinnrCeoNm = '" .$arr['bidwinnrCeoNm']. "', ";	// 대표자명
					$sql .= " bidwinnrTelNo = '" .$arr['bidwinnrTelNo']. "', ";	// 전화번호
					$sql .= " progrsDivCdNm =    '개찰완료' ";
					$sql .= " WHERE bidNtceNo = '"  .$arr['bidNtceNo'].  "' ";
					$sql .= "   AND bidNtceOrd = '" .$arr['bidNtceOrd']. "' ";
					if ($conn->query($sql) == false) echo "Update Error:" .$sql. "<br>";
					$updateRstCnt++;

				} else { //입찰공고가 없으면
					// 공고번호 없으면 '01' Stauts 에 낙찰결과 INSERT
					$sql =  " REPLACE INTO openBidInfo_status (";
					$sql .= "              bidNtceNo,     bidNtceOrd, statusCd, ";
					$sql .= "              bidNtceDt,     opengDt, ";
					$sql .= "              prtcptCnum,    bidwinnrNm, ";
					$sql .= "              bidwinnrBizno, sucsfbidAmt, ";
					$sql .= "              sucsfbidRate,  rlOpengDt, ";
					$sql .= "              bidwinnrCeoNm, bidwinnrTelNo, progrsDivCdNm )";
					$sql .= " VALUES ('" .$arr['bidNtceNo'] . "',     '" .$arr['bidNtceOrd'].   "', '01', ";
					$sql .= "         '" .$arr['bidNtceDt'] . "',     '" .$arr['opengDt'].      "', ";
					$sql .= "         '" .$arr['prtcptCnum'] . "',    '" .$arr['bidwinnrNm'].   "', ";
					$sql .= "         '" .$arr['bidwinnrBizno'] . "', '" .$arr['sucsfbidAmt'].  "', ";
					$sql .= "         '" .$arr['sucsfbidRate'] . "',  '" .$arr['rlOpengDt'].   "', ";
					$sql .= "         '" .$arr['bidwinnrCeoNm'] . "', '" .$arr['bidwinnrTelNo']. "', '개찰완료' )";
					if (!($conn->query($sql))) echo "REPLACE Error:" .$sql. "<br>";
					$nonNtceNoCnt++; //공고없음
					continue;
				}

				//---------------------------------------
				// 낙찰결과로 업데이트한 것만 화면그리드표시: 
				//---------------------------------------
				if ($i >= 11) continue; //  최대 10건만 화면 표시
				$tr = '<tr>';
				$tr .= '<td style="text-align: center;">' . $i . '</td>';
				$tr .= '<td style="text-align: center;">' . $arr['bidNtceNo'] . '-' . $arr['bidNtceOrd'] . '</td>';
				$tr .= '<td>' . $arr['bidNtceNm'] . '</td>';
				$tr .= '<td>' . $arr['ntceInsttNm'] . '</td>';  //공고기관
				$tr .= '<td>' . $arr['dminsttNm'] . '</td>';
				$tr .= '<td style="text-align: center;">' . $arr['rlOpengDt'] . '</td>'; //개찰일시
				$tr .= '<td style="text-align: center;">' . $pss . '</td>';
				$tr .= '<td style="text-align: center;">' . $row['idx'] . '</td>'; //공고Index
				$tr .= '<td style="text-align: right;">' . $arr['prtcptCnum'] . '</td>';
				$tr .= '<td style="text-align: left;">' . $arr['bidwinnrNm'] . '</td>';
				$tr .= '</tr>';
				echo ($tr);
				$i++;
			}

			// ------------------------------------------------------
			// 결과표시 -by jsj 20200328
			// ------------------------------------------------------
			echo "<br>---------- 결과 -----------------------------------------------------------------<br>";
			echo " 1) 입찰공고 API 작업 <br>";
			echo "  - 입찰정보 신규 newInfoRecord= '"      . $newInfoRecord .         "' 건 <br> ";		  // 입찰공고에 레코드 추가
			echo "  - 입찰정보 변경 updateOpenBidInfo= '"  . $updateOpenBidInfo .     "' 건 <br> ";
			echo "  - openBidSeq_xxxx 추가 insertSeqCnt= '" . $insertSeqCnt .       "' 건 <br> ";
			echo "  - openBidInfo (1순위 낙찰정보 업데이트) updateOpenBidInfo_1th= '" . $updateOpenBidInfo_1th . "' 건 <br> ";
			echo " 2) 낙찰정보 API 작업 <br>";
			echo "  - openBidInfo 에 진행구분 추가 updateProgrsDivCdNm= '" . $updateProgrsDivCdNm .  "' 건 <br> "; // 진행구분: 유찰,개찰완료,재입찰 업뎃건수
			echo "  - openBidInfo 에 낙찰정보 업데이트     updateRstCnt= '" . $updateRstCnt .                "' 건 <br> ";
			echo "  --> 입찰정보없음 status Cd='01'에 저장 nonNtceNoCnt= '" . $nonNtceNoCnt .                "' 건 <br> ";		  //status에 레코드 추가

			// seq_temp => seq_xxxx
			$sql = " REPLACE INTO " .$openBidSeq ;
			$sql .= " SELECT * FROM openBidSeq_tmp ";
			if ($conn->query($sql)) {
				/*
				$sql = " TRUNCATE openBidSeq_tmp; ";
				if ($conn->query($sql)==false) {
					echo "Error sql=" .$sql;
				}
				*/
			} else {
				echo "Error sql=" .$sql;
			}

			// statusCd=01 배치) 낙찰정보 업데이트 status '01'의 낙찰정보를 입찰정보에 UPDATE
			$sql = " UPDATE openBidInfo, openBidInfo_status ";
			$sql .= "   SET openBidInfo.prtcptCnum = openBidInfo_status.prtcptCnum, ";
			$sql .= "       openBidInfo.bidwinnrNm = openBidInfo_status.bidwinnrNm, ";
			$sql .= "       openBidInfo.bidwinnrBizno = openBidInfo_status.bidwinnrBizno, ";
			$sql .= "       openBidInfo.sucsfbidAmt = openBidInfo_status.sucsfbidAmt, ";
			$sql .= "       openBidInfo.sucsfbidRate = openBidInfo_status.sucsfbidRate, ";
			$sql .= "       openBidInfo.rlOpengDt = openBidInfo_status.rlOpengDt, ";
			$sql .= "       openBidInfo.bidwinnrCeoNm = openBidInfo_status.bidwinnrCeoNm, ";
			$sql .= "       openBidInfo.bidwinnrTelNo = openBidInfo_status.bidwinnrTelNo, ";
			$sql .= "       openBidInfo.progrsDivCdNm = openBidInfo_status.progrsDivCdNm, "; // 진행구분 "개찰완료"
			$sql .= "       openBidInfo_status.status_rs = 'Y' "; 							 // 처리 완료로 변경
			$sql .= " WHERE openBidInfo.bidNtceNo = openBidInfo_status.bidNtceNo ";			 // 낙찰현황 완료는 공고번호만 일치하면 전체 업데이트
			$sql .= "   AND openBidInfo_status.status_rs = 'N' "; 							 // 미처리
			$sql .= "   AND openBidInfo_status.statusCd = '01' "; 							 // 낙찰현황정보 있음
			if ($conn->query($sql) <> true) {
				echo "Error (openBidInfo_Status):" . $sql;
			}

			// 배치2) 입찰정보에 낙찰정보가 업데이트되어 있거나, 취소 공고인 경우
			// 낙찰정보있으면 완료로 변경
			$sql = " UPDATE openBidInfo_status ";
			$sql .= "   SET status_rs = 'Y' "; 		//처리완료
			$sql .= " WHERE status_rs = 'N' ";
			$sql .= "   AND bidNtceNo IN ( ";
			$sql .= "       SELECT bidNtceNo FROM openBidInfo where 1 ";
			$sql .= "          AND bidwinnrBizno <> '' ";    		   				   // 낙찰결과있음
			$sql .= "           OR ntceKindNm IN ('취소', '재입찰', '연기') ";			//  일반, < 변경, 취소, 재입찰, 연기>,  긴급, 갱신, 긴급갱신
			$sql .= "           OR bidMethdNm NOT IN ('전자입찰' )";    					// 입찰방식이 '전자입찰' 이 아니면 낙찰결과가 없음
			$sql .= "           OR progrsDivCdNm IN ('유찰', '개찰완료', '재입찰') ";   //  유찰, 개찰완료, 재입찰
			$sql .= "           OR rgstTyNm = '연계기관 공고건' )";    					// 연계기관 공고건
			if ($conn->query($sql) <> true) echo "Error (openBidInfo_Status):" . $sql;

			// 업데이트 완료된 건은 삭제 함
			//$sql = " DELETE FROM openBidInfo_status WHERE status_rs = 'Y' ";
			//if ($conn->query($sql) <> true) echo "SQL error= " . $sql . "<br>";

			//<status상태> 결과에 openBidInfo_status 상태를 보여줌
			echo "<br>------ openBidInfo_Status 상태정보 -------------<br>";
			$sql = " SELECT statusCd, status_rs, COUNT(statusCd) as Cnt from openBidInfo_status GROUP BY statusCd, status_rs ";

			echo "ln629 sql=" .$sql. "<br>";

			$dbRst = $conn->query($sql);
			while ($arr = $dbRst->fetch_assoc()) {
				switch ($arr['statusCd'] . $arr['status_rs']) {
					case "01N":
						echo " (01)낙찰목록비교 공고번호없고, 진행구분-확보= " . $arr['Cnt'] . "건,";
						break;
					case "01Y":
						echo " (완료)낙찰목록비교 공고번호없고, 완료= " . $arr['Cnt'] . "건, <br>";
						break;
					case "02N":
						echo " (02)입찰정보비교 낙찰정보없음=" . $arr['Cnt'] . "건, ";
						break;
					case "02Y":
						echo " (완료)입찰정보비교 낙찰정보없음, 완료=" . $arr['Cnt'] . "건, <br>";
						break;
					case "03N":
						echo " (03)입찰정보비교 공고신규입력=" . $arr['Cnt'] . "건, ";
						break;
					case "03Y":
						echo " (완료)입찰정보비교 공고신규입력, 완료=" . $arr['Cnt'] . "건, <br>";
						break;
				}
			}

			// endDate를 DB저장
			$workname = 'dailyDataFill';
			$workdt  = date('Ymd', strtotime(date("Ymd")));
			$endDate = substr(str_replace('-','',$endDate),0,8);
			//$endDate = $workdt;
			echo "workdt=" .$workdt. ", endDate=" .$endDate. "<br>";
			if ($workdt < $enddate){
				$endDate = $workdt;
			}
			workdate($conn, $workname, $workdt, $endDate);
			

			?>